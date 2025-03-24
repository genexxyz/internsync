<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [
        'deployment_id',
        'supervisor_id',
        'quality_work',
        'completion_time',
        'dependability',
        'judgment',
        'cooperation',
        'attendance',
        'personality',
        'safety',
        'total_score',
        'recommendation',
    ];

    public function deployment()
    {
        return $this->belongsTo(Deployment::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }

    public function getTotalScoreAttribute()
    {
        return $this->quality_work + $this->completion_time + $this->dependability + $this->judgment + $this->cooperation + $this->attendance + $this->personality + $this->safety;
    }
}
