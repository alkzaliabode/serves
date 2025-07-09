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
        'custom_usages', // Existing custom usages
        'wedding_leaves', // New: Wedding leaves
        'other_leaves',   // New: Other leaves
        'monthly_hours',  // New: Monthly hours (if stored daily)
        'total_employees',
        'actual_attendance',
        'paid_leaves_count',
        'unpaid_leaves_count',
        'absences_count',
        'shortage',
        'total_required', // Added total_required to fillable
        'organizer_employee_id',
        'organizer_employee_name',
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
        'absences' => 'array',
        'long_leaves' => 'array',
        'sick_leaves' => 'array',
        'bereavement_leaves' => 'array',
        'eid_leaves' => 'array',
        'guard_rest' => 'array',
        'custom_usages' => 'array',
        'wedding_leaves' => 'array', // Cast new field as array
        'other_leaves' => 'array',   // Cast new field as array
        'monthly_hours' => 'array',  // Cast new field as array (if storing daily hours per employee)
        'organizer_employee_id' => 'integer',
        'organizer_employee_name' => 'string',
    ];
}