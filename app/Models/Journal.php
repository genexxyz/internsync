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
        'feedback',
        'is_reopened',
        'reviewed_at',
    ];

    protected $dates = ['date'];

    protected $casts = [
        'date' => 'date',
        'is_submitted' => 'boolean',
    
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function attendance()
{
    return $this->hasOne(Attendance::class, 'date', 'date');
}
// public function taskHistories()
// {
//     return $this->hasMany(TaskHistory::class);
// }
public function tasks()
{
    return $this->hasMany(Task::class);
}

}