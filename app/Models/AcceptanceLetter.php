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
        'supervisor_name',
        'address',
        'contact',
        'email',
        'is_generated',
        'is_verified',
        'signed_path',
        'reference_link'
    ];

    protected $casts = [
        'is_generated' => 'boolean'
    ];

    public function student()
{
    return $this->belongsTo(Student::class)->with(['user', 'section.course']);
}
public function getStatusAttribute()
    {
        if (!$this->student->acceptance_letter) return 'pending';
        if ($this->student->acceptance_letter && !$this->student->deployment->company_id) return 'for_review';
        if (!$this->student->deployment->company_id && $this->student->deployment->supervisor_id) return 'pending_company';
        return 'completed';
    }

    
public function company()
{
    return $this->belongsTo(Company::class, 'company_name', 'company_name');
}

public function department()
{
    return $this->belongsTo(Department::class, 'department_name', 'department_name')
        ->where('company_id', $this->company->id);
}

public function getCompanyAttribute()
    {
        return Company::where('company_name', $this->company_name)->first();
    }

    public function getDepartmentAttribute()
    {
        $company = $this->company;
        if (!$company) return null;
        
        return Department::where('company_id', $company->id)
            ->where('department_name', $this->department_name)
            ->first();
    }
}