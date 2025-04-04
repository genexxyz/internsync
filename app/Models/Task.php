<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'description',
        'order'
    ];

    public function histories()
    {
        return $this->hasMany(TaskHistory::class);
    }

    public function getCurrentStatusAttribute()
    {
        return $this->histories()->latest('changed_at')->first()?->status ?? 'pending';
    }

    public function getCurrentJournalAttribute()
    {
        return $this->histories()->latest('changed_at')->first()?->journal;
    }
}