<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LetterTemplate extends Model
{
    protected $fillable = ['title', 'content', 'variables', 'is_active'];
    
    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean'
    ];
}