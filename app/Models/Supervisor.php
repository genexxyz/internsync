<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Supervisor extends Model
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
        'company_id',
        'company_department_id',
        'position',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'contact',
        'supporting_doc',
        'image',
        'signature_path',
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

    public function company()
{
    return $this->belongsTo(Company::class);
}

public function deployments()
{
    return $this->hasMany(Deployment::class, 'company_dept_id', 'company_department_id');
}

    public function department()
    {
        return $this->belongsTo(Department::class, 'company_department_id');
    }
    
}
