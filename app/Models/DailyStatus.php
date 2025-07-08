<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'hijri_date',
        'day_name',
        'periodic_leaves',
        'annual_leaves',
        'temporary_leaves',
        'unpaid_leaves',
        'absences',
        'long_leaves',
        'sick_leaves',
        'bereavement_leaves',
        'eid_leaves',
        'guard_rest',
        'total_employees',
        'actual_attendance',
        'paid_leaves_count',
        'unpaid_leaves_count',
        'absences_count',
        'shortage',
        'organizer_employee_id',
        'organizer_employee_name',
        'custom_usages',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'periodic_leaves' => 'array',
        'annual_leaves' => 'array',
        'temporary_leaves' => 'array',
        'unpaid_leaves' => 'array',
        'absences' => 'array', // هذا الحقل سيحتوي على start_date و total_days
        'long_leaves' => 'array', // هذا الحقل سيحتوي على start_date و total_days
        'sick_leaves' => 'array', // هذا الحقل سيحتوي على start_date و total_days
        'bereavement_leaves' => 'array',
        'eid_leaves' => 'array',
        'guard_rest' => 'array',
        'custom_usages' => 'array',
        'organizer_employee_id' => 'integer',
        'organizer_employee_name' => 'string',
    ];
}
