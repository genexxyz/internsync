<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EndorsementRequest extends Model
{
    protected $table = 'endorsement_letter_requests';
    protected $fillable = [
        'company_id',
        'requested_by',
        'status',
        'requested_at',
        'for_pickup_at',
        'picked_up_at',
        'received_by',
        'admin_remarks',
        'admin_id',
    ];
    protected $casts = [
        'requested_at' => 'datetime',
        'for_pickup_at' => 'datetime',
        'picked_up_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'requested_by');
    }



    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
