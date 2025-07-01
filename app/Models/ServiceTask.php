<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // لاستخدام Str::limit في الوصف

class ServiceTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'unit', 'status', 'priority', 'due_date', 'assigned_to', 'order_column'
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    // قائمة بجميع Accessors التي نريد إلحاقها بكائن JSON تلقائيًا
    protected $appends = [
        'assigned_to_name',
        'formatted_due_date',
        'status_label',
        'priority_label',
        'priority_color',
        'unit_label',   // NEW
        'unit_icon',    // NEW
        'priority_icon' // NEW
    ];

    // تعريف الثوابت للحالات
    const STATUSES = [
        'pending' => 'معلقة',
        'in_progress' => 'قيد التنفيذ',
        'completed' => 'مكتملة',
        'rejected' => 'مرفوضة',
    ];

    // تعريف الثوابت للوحدات
    const UNITS = [
        'GeneralCleaning' => 'النظافة العامة',
        'SanitationFacility' => 'المنشآت الصحية',
    ];

    // تعريف الثوابت للأولويات
    const PRIORITIES = [
        'low' => 'منخفضة',
        'medium' => 'متوسطة',
        'high' => 'عالية',
        'urgent' => 'عاجلة', // إضافة أولوية عاجلة
    ];

    // العلاقة مع الموظف المسؤول
    public function assignedTo()
    {
        return $this->belongsTo(Employee::class, 'assigned_to');
    }

    // --- Accessors ---

    // للحصول على اسم الموظف المعين
    public function getAssignedToNameAttribute()
    {
        return $this->assignedTo->name ?? 'غير معين';
    }

    // لتنسيق تاريخ الاستحقاق
    public function getFormattedDueDateAttribute()
    {
        return $this->due_date ? $this->due_date->translatedFormat('d F Y') : 'غير محدد';
    }

    // للحصول على ترجمة حالة المهمة
    public function getStatusLabelAttribute()
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    // للحصول على ترجمة الأولوية
    public function getPriorityLabelAttribute()
    {
        return self::PRIORITIES[$this->priority] ?? $this->priority;
    }

    // للحصول على لون الأولوية (لأغراض العرض في الواجهة الأمامية)
    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'high' => 'red',
            'medium' => 'yellow', // أو أي لون آخر يناسبك
            'low' => 'green',
            'urgent' => 'purple', // لون جديد للأولوية العاجلة
            default => 'gray',
        };
    }

    // NEW: للحصول على ترجمة اسم الوحدة
    public function getUnitLabelAttribute()
    {
        return self::UNITS[$this->unit] ?? $this->unit;
    }

    // NEW: للحصول على أيقونة Font Awesome المناسبة للوحدة
    public function getUnitIconAttribute()
    {
        return match($this->unit) {
            'GeneralCleaning' => 'fas fa-broom',
            'SanitationFacility' => 'fas fa-hospital',
            default => 'fas fa-question-circle', // أيقونة افتراضية
        };
    }

    // NEW: للحصول على أيقونة Font Awesome المناسبة للأولوية
    public function getPriorityIconAttribute()
    {
        return match($this->priority) {
            'high' => 'fas fa-exclamation-triangle', // تحذير
            'medium' => 'fas fa-info-circle', // معلومة
            'low' => 'fas fa-check-circle', // موافق
            'urgent' => 'fas fa-fire', // أيقونة إضافية للأولوية العاجلة
            default => 'fas fa-flag',
        };
    }
}
