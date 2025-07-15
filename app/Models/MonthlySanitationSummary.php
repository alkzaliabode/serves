<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SanitationTask extends Model
{
    // تعيين اسم الجدول يدوياً
    protected $table = 'sanitation_tasks';

    // الأعمدة القابلة للتعبئة الجماعية
    protected $fillable = [
        'date', // تاريخ المهمة الفردية
        'facility_name',
        'task_type',
        'unit_id',
        'seats_count',    // عدد المقاعد لهذه المهمة
        'mirrors_count',  // عدد المرايا لهذه المهمة
        'mixers_count',   // عدد الخلاطات لهذه المهمة
        'doors_count',    // عدد الأبواب لهذه المهمة
        'sinks_count',    // عدد الأحواض لهذه المهمة
        'toilets_count',  // عدد المراحيض لهذه المهمة
        'notes',          // ملاحظات خاصة بهذه المهمة
    ];

    // تحديد المفتاح الأساسي كـ integer (افتراضي)
    // إذا كنت تستخدم UUIDs، يجب تعيين protected $keyType = 'string'; public $incrementing = false;

    // تحويل أنواع البيانات
    protected $casts = [
        'date' => 'date', // تحويل حقل التاريخ إلى كائن Carbon
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
