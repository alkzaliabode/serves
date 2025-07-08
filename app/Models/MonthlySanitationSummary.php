<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlySanitationSummary extends Model
{
    // تعيين اسم الجدول يدوياً إذا كان يختلف عن convention (monthly_sanitation_summaries)
    protected $table = 'monthly_sanitation_summary';

    // الأعمدة القابلة للتعبئة الجماعية
    protected $fillable = [
        'id', // يجب أن يكون قابلاً للتعبئة إذا كان مخصصاً
        'month',
        'facility_name',
        'task_type',
        'unit_id',
        'total_seats',
        'total_mirrors',
        'total_mixers',
        'total_doors',
        'total_sinks',
        'total_toilets',
        'total_tasks',
    ];

    // تحديد المفتاح الأساسي كـ string إذا لم يكن auto-incrementing integer
    protected $keyType = 'string';
    public $incrementing = false;

    // تحويل أنواع البيانات (إذا كان لديك أي أعمدة JSON أو تواريخ تحتاج لتحويل خاص)
    protected $casts = [
        // 'month' => 'date:Y-m', // يمكن استخدامه لتحويل الشهر إلى كائن تاريخ
    ];

    /**
     * العلاقة: الملخص الشهري ينتمي إلى وحدة معينة.
     *
     * @return BelongsTo
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
