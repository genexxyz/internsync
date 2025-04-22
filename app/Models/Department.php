<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'company_departments';
    protected $fillable = ['department_name', 'company_id'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function supervisor()
    {
        return $this->hasMany(Supervisor::class);
    }
    public function deployments()
    {
        return $this->hasMany(Deployment::class, 'company_dept_id');
    }
    
}
