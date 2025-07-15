<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlyGeneralCleaningSummary extends Model
{
    protected $table = 'monthly_general_cleaning_summary';

    // تم حذف public $timestamps = false; لأن الـ Migration يضيفها الآن
    // إذا كنت لا تريد created_at و updated_at، يجب إزالتها من الـ Migration أيضاً.

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'month',
        'location',
        'task_type',
        'unit_id', // ✅ إضافة unit_id إلى fillable
        'total_mats',
        'total_pillows',
        'total_fans',
        'total_windows',
        'total_carpets',
        'total_blankets',
        'total_beds',
        'total_beneficiaries',
        'total_trams',
        'total_laid_carpets',
        'total_large_containers',
        'total_small_containers',
        'total_tasks', // ✅ إضافة total_tasks إلى fillable
        'total_external_partitions', // ✅ تم إضافة هذا الحقل الجديد
    ];

    // علاقة مع الوحدة
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
