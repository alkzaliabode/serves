<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResourceTracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'unit_id',
        'working_hours',
        'total_supplies_and_tools_score', // العمود الجديد المدمج
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'working_hours' => 'float',
        'total_supplies_and_tools_score' => 'float', // نوع البيانات للعمود الجديد
    ];

    /**
     * Get the unit that owns the resource tracking record.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Accessor for 'total_working_hours'.
     * This returns the 'working_hours' attribute but can be accessed as $resourceTracking->total_working_hours.
     *
     * @return float
     */
    public function getTotalWorkingHoursAttribute(): float
    {
        return $this->working_hours;
    }

    /**
     * Calculate the overall efficiency for this resource tracking record (tasks per hour).
     * This efficiency is for display in the resource tracking table and is different from
     * the 'Gilbert Efficiency' calculated in ActualResult model.
     *
     * @return float
     */
    public function getEfficiencyAttribute(): float
    {
        // Fetch completed tasks for the associated unit and date from both task types
        $completedGeneral = GeneralCleaningTask::where('unit_id', $this->unit_id)
            ->whereDate('date', $this->date)
            ->where('status', 'مكتمل')
            ->count();

        $completedSanitation = SanitationFacilityTask::where('unit_id', $this->unit_id)
            ->whereDate('date', $this->date)
            ->where('status', 'مكتمل')
            ->count();

        $totalCompletedTasks = $completedGeneral + $completedSanitation;
        $hours = $this->working_hours ?? 1; // Use 1 to avoid division by zero if working_hours is 0 or null

        // Calculate tasks per hour, rounded to 2 decimal places
        return round($totalCompletedTasks / max($hours, 1), 2);
    }
}
