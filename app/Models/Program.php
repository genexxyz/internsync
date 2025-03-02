<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $table = 'instructor_courses';
    protected $fillable = [
        'instructor_id',
        'course_id',
        'academic_year_id',
        'is_verified',
    ];

    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function academic_year()
    {
        return $this->belongsTo(Academic::class, 'academic_year_id');
    }


}
