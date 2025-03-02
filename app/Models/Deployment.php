<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deployment extends Model
{
    protected $fillable = [
        'student_id',
        'instructor_id',
        'supervisor_id',
        'company_id',
        'company_dept_id',
        'academic_id',
        'custom_hours',
    ];
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }



    public function academic()
    {
        return $this->belongsTo(Academic::class);
    }

    // Optional: Attendance relationship if needed
    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'student_id', 'student_id');
    }
}
