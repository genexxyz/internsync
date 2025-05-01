<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'system_name',
            'school_name',
            'system_email',
            'school_address',
            'system_contact',
            'default_theme',
            'default_logo',
            'header_image',
            'footer_image',
            'minimum_hours',
            'updated_by',
    ];
}
