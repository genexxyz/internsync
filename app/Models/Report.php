<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = "weekly_reports";
    protected $fillable = [
        'student_id',
        'week_number',
        'start_date',
        'end_date',
        'learning_outcomes',
        'status',
        'supervisor_feedback',
        'submitted_at',
        'reviewed_at',
    ];
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'status' => 'string',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
