<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlySanitationSummary extends Model
{
    // تعيين اسم الجدول يدوياً
    protected $table = 'monthly_sanitation_summary';

    // الأعمدة القابلة للتعبئة الجماعية
    protected $fillable = [
        'id',              // مركب من الشهر + المنشأة + المهمة + الوحدة
        'month',           // الشهر بصيغة Y-m (مثل 2025-06)
        'facility_name',   // اسم المنشأة
        'task_type',       // نوع المهمة
        'unit_id',         // معرف الوحدة
        'total_seats',     // إجمالي عدد المقاعد
        'total_mirrors',   // إجمالي عدد المرايا
        'total_mixers',    // إجمالي عدد الخلاطات
        'total_doors',     // إجمالي عدد الأبواب
        'total_sinks',     // إجمالي عدد الأحواض
        'total_toilets',   // إجمالي عدد المراحيض
        'total_tasks',     // إجمالي عدد المهام
    ];

    // تحديد نوع المفتاح الأساسي كـ string لأننا نستخدم معرف مركب
    protected $keyType = 'string';
    
    // تعطيل الزيادة التلقائية لأن المفتاح الأساسي هو سلسلة نصية
    public $incrementing = false;

    // تحويل أنواع البيانات
    protected $casts = [
        'total_seats' => 'integer',
        'total_mirrors' => 'integer',
        'total_mixers' => 'integer',
        'total_doors' => 'integer',
        'total_sinks' => 'integer',
        'total_toilets' => 'integer',
        'total_tasks' => 'integer',
    ];

    /**
     * العلاقة: المهمة الصحية تنتمي إلى وحدة معينة.
     *
     * @return BelongsTo
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
