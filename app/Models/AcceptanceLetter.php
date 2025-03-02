<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcceptanceLetter extends Model
{
    protected $fillable = [
        'student_id',
        'company_name',
        'department_name',
        'name',
        'position',
        'address',
        'contact',
        'is_generated',
        'is_verified',
        'signed_path'
    ];

    protected $casts = [
        'is_generated' => 'boolean'
    ];

    public function student()
{
    return $this->belongsTo(Student::class)->with(['user', 'section.course']);
}
}