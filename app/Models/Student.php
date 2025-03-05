<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Student extends Model
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
        'student_id',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'address',
        'contact',
        'year_section_id',
        'supporting_doc',
        'image',
    ];
    protected $dates = ['birthday'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function yearSection()
    {
        return $this->belongsTo(Section::class);
    }

    public function section()
{
    return $this->belongsTo(Section::class, 'year_section_id');
}

    public function name()
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

    public function deployment()
    {
        return $this->hasOne(Deployment::class, 'student_id');
    }
    public function scopeDeployed($query)
    {
        return $query->whereHas('deployment');
    }

    public function scopeNotDeployed($query)
    {
        return $query->whereDoesntHave('deployment');
    }
    // Define the relationship between Student and Attendance
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    public function journals()
    {
        return $this->hasMany(Journal::class);
    }

    public function acceptance_letter()
    {
        return $this->hasOne(AcceptanceLetter::class);
    }

    public function weeklyReports()
    {
        return $this->hasMany(Report::class);
    }

    

}
