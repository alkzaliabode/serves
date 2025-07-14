<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_goal_id',
        'unit_id',
        'unit_name',
        'goal_text',
        'target_tasks',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // العلاقة مع هدف القسم
    public function departmentGoal()
    {
        return $this->belongsTo(DepartmentGoal::class);
    }

    // العلاقة مع الوحدة
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    // علاقة جديدة: الهدف لديه العديد من مهام النظافة العامة
    public function generalCleaningTasks()
    {
        return $this->hasMany(GeneralCleaningTask::class, 'related_goal_id');
    }

    // علاقة جديدة: الهدف لديه العديد من مهام منشآت الصرف الصحي
    public function sanitationFacilityTasks()
    {
        return $this->hasMany(SanitationFacilityTask::class, 'related_goal_id');
    }

    /**
     * Accessor for progress_percentage attribute.
     * Calculates the progress based on completed General Cleaning Tasks and Sanitation Facility Tasks
     * versus the total target_tasks.
     *
     * @return float
     */
    public function getProgressPercentageAttribute()
    {
        // إذا لم يكن هناك عدد مهام مستهدف، أو كان صفرًا، فإن التقدم يكون صفرًا
        if ($this->target_tasks <= 0) {
            return 0.00;
        }

        // عد المهام المكتملة من نوع النظافة العامة المرتبطة بهذا الهدف
        $completedGeneralCleaningTasks = $this->generalCleaningTasks()->where('status', 'مكتمل')->count();

        // عد المهام المكتملة من نوع منشآت الصرف الصحي المرتبطة بهذا الهدف
        $completedSanitationFacilityTasks = $this->sanitationFacilityTasks()->where('status', 'مكتمل')->count();

        // إجمالي المهام المكتملة لكلا النوعين
        $totalCompletedTasks = $completedGeneralCleaningTasks + $completedSanitationFacilityTasks;

        // حساب النسبة المئوية
        $percentage = ($totalCompletedTasks / $this->target_tasks) * 100;

        // ضمان ألا تتجاوز النسبة 100% وتقريبها لمنزلتين عشريتين
        return round(min($percentage, 100), 2);
    }
}