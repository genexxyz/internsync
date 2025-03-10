<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table ="companies";

    protected $fillable = [
        'company_name',
        'address',
        'contact_person',
        'contact',
    ];

    public function supervisor()
    {
        return $this->hasMany(Supervisor::class);
    }
    public function department()
    {
        return $this->hasMany(Department::class);
    }
    public function deployments()
    {
        return $this->hasManyThrough(
            Deployment::class,
            Department::class,
            'company_id', // Foreign key on departments table
            'company_dept_id', // Foreign key on deployments table
            'id', // Local key on companies table
            'id' // Local key on departments table
        );
    }
}
