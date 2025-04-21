<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'course_name',
        'course_code',
        'required_hours',
        'custom_hours',
        'academic_year_id',
        'instructor_id',
    ];

    public function sections()
{
    return $this->hasMany(Section::class);
}
public function instructor()
{
    return $this->belongsTo(Instructor::class, 'instructor_id');
}

public function instructorCourses()
{
    return $this->hasMany(Program::class, 'course_id');
}
public function students()
{
    return $this->hasManyThrough(
        Student::class,
        Section::class,
        'course_id',
        'year_section_id'
    );
}

}
