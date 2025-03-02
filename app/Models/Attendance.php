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
}
