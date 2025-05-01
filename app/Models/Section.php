<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{

    protected $fillable = [
        'course_id',
        'year_level',
        'class_section',
        'instructor_id',
    ];

    public function course()
{
    return $this->belongsTo(Course::class, 'course_id');
}
public function students()
    {
        return $this->hasMany(Student::class,'year_section_id');
    }
    public function instructors()
    {
        return $this->belongsToMany(Instructor::class, 'instructor_sections');
    }

    public function instructor_courses()
{
    return $this->hasMany(Program::class, 'course_id', 'course_id');
}
public function handles()
{
    return $this->hasMany(Handle::class, 'year_section_id');
}

public function handle()
{
    return $this->hasMany(Handle::class, 'year_section_id');
}

}
