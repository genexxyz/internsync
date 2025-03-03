<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    protected $fillable = [
        'student_id',
        'date',
        'text',
        'remarks',
        'is_submitted',
        'is_approved',
    ];

    protected $dates = ['date'];

    protected $casts = [
        'date' => 'date',
        'is_submitted' => 'boolean',
    'is_approved' => 'boolean'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function attendance()
{
    return $this->hasOne(Attendance::class, 'date', 'date');
}
}
