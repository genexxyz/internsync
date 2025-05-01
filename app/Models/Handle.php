<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Handle extends Model
{
    protected $table = 'instructor_sections';

    protected $fillable = [
        'instructor_id',
        'year_section_id',
        'academic_year_id',
        'is_verified',
    ];

    
    public function section()
    {
        return $this->belongsTo(Section::class , 'year_section_id');
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }
    protected $casts = [
        'is_verified' => 'boolean'
    ];
    

    
}
