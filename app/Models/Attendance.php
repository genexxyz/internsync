<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'student_id', 
        'date', 
        'time_in', 
        'time_out', 
        'start_break',
        'end_break', 
        'total_hours',
        'status'
    ];

    protected $dates = ['date', 'time_in', 'time_out'];

    protected $casts = [
        'date' => 'date',
        'total_hours' => 'float',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Scope for different attendance statuses
    public function scopeRegular($query)
    {
        return $query->where('status', 'regular');
    }

    public function scopeLate($query)
    {
        return $query->where('status', 'late');
    }

    public function scopeAbsent($query)
    {
        return $query->where('status', 'absent');
    }

    public function getTotalHoursAttribute($value)
{
    if (!$value) return null;
    return \Carbon\Carbon::parse($value)->format('H:i');
}

public function calculateTotalHours()
{
    if (!$this->time_in || !$this->time_out) {
        return '00:00';
    }

    $timeIn = \Carbon\Carbon::parse($this->time_in);
    $timeOut = \Carbon\Carbon::parse($this->time_out);
    
    $totalMinutes = $timeOut->diffInMinutes($timeIn);

    // Deduct break time if exists
    if ($this->start_break && $this->end_break) {
        $breakStart = \Carbon\Carbon::parse($this->start_break);
        $breakEnd = \Carbon\Carbon::parse($this->end_break);
        $breakMinutes = $breakEnd->diffInMinutes($breakStart);
        $totalMinutes -= $breakMinutes;
    }

    // Convert minutes to hours and minutes
    $hours = floor($totalMinutes / 60);
    $minutes = $totalMinutes % 60;

    return sprintf('%02d:%02d', $hours, $minutes);
}

public static function getTotalApprovedHours($studentId)
{
    $attendances = static::where('student_id', $studentId)
        ->where('is_approved', true)
        ->get();

    $totalMinutes = 0;
    foreach ($attendances as $attendance) {
        list($hours, $minutes) = explode(':', $attendance->total_hours);
        $totalMinutes += ($hours * 60) + $minutes;
    }

    $hours = floor($totalMinutes / 60);
    $minutes = $totalMinutes % 60;

    return sprintf('%2d', $hours);
}

public static function getTotalHoursWeekly($studentId, $startDate, $endDate)
{
    $attendances = static::where('student_id', $studentId)
        ->whereBetween('date', [$startDate, $endDate])
        ->get();

    $totalMinutes = 0;
    foreach ($attendances as $attendance) {
        list($hours, $minutes) = explode(':', $attendance->total_hours);
        $totalMinutes += ($hours * 60) + $minutes;
    }

    $hours = floor($totalMinutes / 60);
    $minutes = $totalMinutes % 60;

    return sprintf('%2d hours and %2d minutes', $hours, $minutes);
}
}
