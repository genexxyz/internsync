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

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
