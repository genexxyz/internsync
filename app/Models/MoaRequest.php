<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoaRequest extends Model
{
    protected $fillable = [
        'company_id',
        'company_number',
        'officer_name',
        'officer_position',
        'witness_name',
        'witness_position',
        'requested_by',
        'status',
        'requested_at',
        'for_pickup_at',
        'picked_up_at',
        'received_by_company_at',
        'received_by_student',
        'received_by_supervisor',
        'admin_remarks',
        'admin_id'
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'for_pickup_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'received_by_company_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'requested_by');
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class, 'received_by_supervisor');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
