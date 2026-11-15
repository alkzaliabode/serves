<?php

namespace App\Support;

use App\Models\ActualResult;
use App\Models\ResourceTracking;
use App\Models\Unit;
use App\Models\UnitGoal;
use DateTimeImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PerformanceTransfer
{
    public static function columns(string $kind): array
    {
        if ($kind === 'resources') {
            return [
                'date' => 'التاريخ', 'unit' => 'الوحدة', 'working_hours' => 'ساعات العمل',
                'total_supplies_and_tools_score' => 'إجمالي المستلزمات والمعدات (نقاط)',
                'efficiency' => 'الكفاءة (مهمة/ساعة)', 'is_efficient' => 'كفء؟', 'notes' => 'ملاحظات',
            ];
        }
        $columns = $kind === 'gilbert' ? [
            'date' => 'التاريخ', 'unit' => 'الوحدة', 'results_percentage' => 'النتائج (%)',
            'effectiveness' => 'الفعالية (%)', 'goals_percentage' => 'الأهداف (%)',
            'relevance' => 'الملاءمة (%)', 'resources_percentage' => 'الموارد (%)',
            'efficiency' => 'الكفاءة (%)', 'overall_performance_score' => 'الأداء الإجمالي (%)',
            'quality_rating' => 'الجودة', 'completed_tasks' => 'المهام المكتملة', 'target_tasks' => 'المهام المستهدفة',
        ] : [
            'date' => 'التاريخ', 'unit' => 'الوحدة', 'target_tasks' => 'المستهدف', 'completed_tasks' => 'المكتمل',
            'effectiveness' => 'الفعالية (%)', 'efficiency' => 'الكفاءة (%)', 'relevance' => 'الملاءمة (%)',
            'overall_performance_score' => 'الأداء الإجمالي (%)', 'quality_rating' => 'الجودة',
        ];

        return $columns + [
            'working_hours' => 'ساعات العمل', 'efficiency_score' => 'درجة الكفاءة (1-100)',
            'notes' => 'ملاحظات', 'unit_goal_id' => 'معرف هدف الوحدة',
        ];
    }

    public static function title(string $kind): string
    {
        return match ($kind) {
            'resources' => 'تتبع الموارد', 'results' => 'النتائج الفعلية', 'gilbert' => 'مثلث جلبرت',
        };
    }

    public function query(string $kind, Request $request)
    {
        $query = $kind === 'resources'
            ? ResourceTracking::with('unit')
            : ActualResult::with(['unit', 'unitGoal']);
        if ($kind === 'gilbert') {
            // The chart currently compares today and yesterday for unit 1.
            return $query->where('unit_id', 1)->whereDate('date', '>=', now()->subDay()->toDateString())->whereDate('date', '<=', now()->toDateString());
        }
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->input('unit_id'));
        }
        foreach (['from_date' => '>=', 'to_date' => '<='] as $key => $operator) {
            if ($request->filled($key)) {
                $query->whereDate('date', $operator, $request->input($key));
            }
        }
        if ($kind === 'results') {
            match ($request->input('performance_level')) {
                'excellent' => $query->where('overall_performance_score', '>=', 90),
                'good' => $query->whereBetween('overall_performance_score', [70, 89]),
                'average' => $query->whereBetween('overall_performance_score', [50, 69]),
                'poor' => $query->where('overall_performance_score', '<', 50),
                default => null,
            };
        }
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('unit', fn ($unit) => $unit->where('name', 'like', '%'.$search.'%'))
                    ->orWhere('notes', 'like', '%'.$search.'%');
            });
        }

        return $query;
    }

    public function exportRows(string $kind, Request $request): array
    {
        $rows = [];
        $records = $this->query($kind, $request)->orderByDesc('date')->orderByDesc('id')->get();
        if ($kind === 'gilbert') {
            $records = $records->unique(fn ($record) => $record->date->toDateString());
        }
        foreach ($records as $record) {
            $values = $record->getAttributes();
            $values['date'] = $record->date->format('d/m/Y');
            $values['unit'] = $record->unit?->name ?? '';
            if ($kind === 'resources') {
                $values['efficiency'] = $record->efficiency;
                $values['is_efficient'] = $record->working_hours > 0 && $record->total_supplies_and_tools_score > 0 ? 'نعم' : 'لا';
            } else {
                $values['target_tasks'] = $record->unitGoal?->target_tasks ?? 0;
                $values['results_percentage'] = min(100, round($record->completed_tasks, 2));
                $values['goals_percentage'] = min(100, round($values['target_tasks'] / 50 * 100, 2));
                $values['resources_percentage'] = min(100, round($record->working_hours / 8 * 100, 2));
                // Gilbert's live chart calculates these relationships from the source values.
                if ($kind === 'gilbert') {
                    $values = array_replace($values, $this->metrics((float) $record->completed_tasks, (float) $record->working_hours, (float) $values['target_tasks'], (float) $record->quality_rating));
                    $values['overall_performance_score'] = $record->overall_performance_score;
                }
            }
            $rows[] = array_map(fn ($key) => $values[$key] ?? '', array_keys(self::columns($kind)));
        }

        return $rows;
    }

    public function normalizeDate(string $value): string
    {
        // Native Excel dates are numeric days since 1899-12-30 (1900 date system).
        if (is_numeric($value) && (float) $value >= 61 && (float) $value <= 2958465) {
            return (new DateTimeImmutable('1899-12-30'))->modify('+'.(int) $value.' days')->format('Y-m-d');
        }
        foreach (['d/m/Y', 'Y-m-d'] as $format) {
            $date = DateTimeImmutable::createFromFormat('!'.$format, $value);
            if ($date && $date->format($format) === $value) {
                return $date->format('Y-m-d');
            }
        }

        return $value;
    }

    public function validateRows(string $kind, array $rows, bool $updateExisting = false): array
    {
        $columns = self::columns($kind);
        if (($rows[1] ?? []) !== array_values($columns)) {
            throw ValidationException::withMessages(['file' => 'رؤوس الأعمدة وترتيبها يجب أن تطابق نموذج هذه الصفحة تماماً.']);
        }
        unset($rows[1]);
        $records = $errors = $seen = [];
        foreach ($rows as $number => $row) {
            if (! array_filter($row, fn ($value) => $value !== '')) {
                continue;
            }
            $data = array_combine(array_keys($columns), $row);
            $record = [
                'date' => $this->normalizeDate($data['date']),
                'working_hours' => $data['working_hours'], 'notes' => $data['notes'] ?: null,
            ];
            $rules = ['date' => 'required|date_format:Y-m-d', 'working_hours' => 'required|numeric|min:0|max:999999.99', 'notes' => 'nullable|string|max:1000'];
            if ($kind === 'resources') {
                $record['total_supplies_and_tools_score'] = $data['total_supplies_and_tools_score'];
                $rules['working_hours'] = 'required|integer|min:0|max:2147483647';
                $rules['total_supplies_and_tools_score'] = 'required|numeric|min:0|max:999999999';
            } else {
                foreach (['completed_tasks', 'quality_rating', 'efficiency_score'] as $key) {
                    $record[$key] = $data[$key] === '' ? null : $data[$key];
                }
                $rules += ['completed_tasks' => 'required|integer|min:0|max:2147483647', 'quality_rating' => 'nullable|integer|min:0|max:100', 'efficiency_score' => 'nullable|integer|min:1|max:100'];
            }
            $validator = Validator::make($record, $rules, [
                'required' => ':attribute مطلوب.', 'date_format' => ':attribute يجب أن يكون تاريخاً صحيحاً.',
                'numeric' => ':attribute يجب أن يكون رقماً.', 'integer' => ':attribute يجب أن يكون رقماً صحيحاً.',
                'min' => ':attribute أقل من الحد الأدنى المسموح (:min).',
                'max' => ':attribute يتجاوز الحد الأقصى المسموح (:max).',
                'string' => ':attribute يجب أن يكون نصاً.',
            ], $columns);
            $rowErrors = $validator->errors()->all();
            $units = Unit::where('name', $data['unit'])->get();
            if ($units->isEmpty() && ctype_digit($data['unit'])) {
                $units = Unit::whereKey($data['unit'])->get();
            }
            if ($units->count() !== 1) {
                $rowErrors[] = 'الوحدة غير موجودة أو اسمها مكرر؛ استخدم اسم وحدة صحيحاً أو معرفها.';
            }
            if (! $rowErrors) {
                $record['unit_id'] = $units->first()->id;
                if ($kind !== 'resources') {
                    $goals = UnitGoal::where('unit_id', $record['unit_id']);
                    if ($data['unit_goal_id'] !== '') {
                        $goals->whereKey($data['unit_goal_id']);
                    } else {
                        $goals->whereDate('date', $record['date']);
                    }
                    $goals = $goals->get();
                    if ($goals->count() !== 1) {
                        $rowErrors[] = 'يجب وجود هدف واحد للوحدة والتاريخ، أو تحديد معرف هدف الوحدة الصحيح.';
                    } else {
                        $goal = $goals->first();
                        $record += ['unit_goal_id' => $goal->id, 'department_goal_id' => $goal->department_goal_id];
                        $record += $this->metrics((float) $record['completed_tasks'], (float) $record['working_hours'], (float) $goal->target_tasks, (float) $record['quality_rating']);
                    }
                }
            }
            if (! $rowErrors) {
                $key = $record['date'].':'.($record['unit_goal_id'] ?? $record['unit_id']);
                if (isset($seen[$key])) {
                    $rowErrors[] = 'السجل مكرر داخل الملف (نفس التاريخ والوحدة/الهدف).';
                }
                $seen[$key] = true;
                $query = $kind === 'resources' ? ResourceTracking::query() : ActualResult::query();
                $query->whereDate('date', $record['date']);
                $kind === 'resources' ? $query->where('unit_id', $record['unit_id']) : $query->where('unit_goal_id', $record['unit_goal_id']);
                if (! $updateExisting && $query->exists()) {
                    $rowErrors[] = 'السجل موجود مسبقاً. فعّل خيار تحديث السجلات الموجودة إذا أردت تعديله.';
                }
            }
            foreach ($rowErrors as $message) {
                $errors[] = 'الصف '.$number.': '.$message;
            }
            if (count($errors) >= 50) {
                break;
            }
            $records[] = $record;
        }
        if ($errors) {
            throw ValidationException::withMessages(['file' => $errors]);
        }
        if (! $records) {
            throw ValidationException::withMessages(['file' => 'الملف لا يحتوي على بيانات للاستيراد.']);
        }

        return $records;
    }

    public function persist(string $kind, array $records, bool $updateExisting): void
    {
        DB::transaction(function () use ($kind, $records, $updateExisting) {
            $model = $kind === 'resources' ? ResourceTracking::class : ActualResult::class;
            foreach ($records as $record) {
                $key = ['date' => $record['date']];
                $key += $kind === 'resources' ? ['unit_id' => $record['unit_id']] : ['unit_goal_id' => $record['unit_goal_id']];
                $existing = null;
                if ($updateExisting) {
                    $lookup = $model::whereDate('date', $record['date']);
                    foreach (array_diff_key($key, ['date' => true]) as $field => $value) {
                        $lookup->where($field, $value);
                    }
                    $existing = $lookup->lockForUpdate()->first();
                }
                if ($existing) {
                    $existing->update($record);
                } else {
                    $model::create($record);
                }
            }
        });
    }

    public function metrics(float $completed, float $hours, float $target, float $quality): array
    {
        $effectiveness = $target > 0 ? min(100, round($completed / $target * 100, 2)) : 0.0;
        $efficiency = $hours > 0 ? min(100, round($completed / $hours * 50, 2)) : 0.0;
        $relevance = $target > 0 && $hours > 0 ? max(0, round(100 - min(100, abs($hours / $target - 0.5) * 100), 2)) : ($target == 0 && $hours == 0 ? 100.0 : 0.0);
        $values = [max(0, min(100, $quality))];
        if ($effectiveness > 0 || ($target == 0 && $completed == 0)) {
            $values[] = $effectiveness;
        }
        if ($efficiency > 0 || ($hours == 0 && $completed == 0)) {
            $values[] = $efficiency;
        }

        return [
            'effectiveness' => $effectiveness, 'efficiency' => $efficiency, 'relevance' => $relevance,
            'overall_performance_score' => round(array_sum($values) / count($values), 2),
        ];
    }
}
