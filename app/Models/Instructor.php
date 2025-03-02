<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Instructor extends Model
{
    
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'instructor_id',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'contact',
        'supporting_doc',
        'image',
        'e_signature',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFullNameAttribute()
    {
        $fullName = "{$this->first_name} ";
        if ($this->middle_name) {
            $fullName .= "{$this->middle_name} ";
        }
        $fullName .= $this->last_name;
        if ($this->suffix) {
            $fullName .= " {$this->suffix}";
        }
        return $fullName;
    }


    public function handles()
{
    return $this->hasMany(Handle::class, 'instructor_id');
}



public function courses()
{
    return $this->hasMany(Course::class, 'instructor_id'); // Assuming instructor_id is in the courses table
}

public function sections()
    {
        return $this->belongsToMany(Section::class, 'instructor_sections', 
            'instructor_id', // Foreign key on instructor_sections table for instructors
            'year_section_id'    // Foreign key on instructor_sections table for sections
        );
    }

    // Add relationship for instructor courses (program head)
    public function instructorCourse()
    {
        return $this->hasOne(Program::class, 'instructor_id');
    }

    // Helper method to check if instructor is program head
    

}
