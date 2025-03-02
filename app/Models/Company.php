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
}
