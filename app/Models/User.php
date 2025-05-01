<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */



    const ROLE_ADMIN = 'admin';
    const ROLE_STUDENT = 'student';
    const ROLE_INSTRUCTOR = 'instructor';
    const ROLE_SUPERVISOR = 'supervisor';


    protected $fillable = [
        'email',
        'password',
        'role',
        
        'status',
        'email_verified_at',
        'is_verified',
        'otp',
        'otp_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'form_completed' => 'boolean',
            'is_approved' => 'boolean'
        ];
    }

    public function profile()
    {
        return match ($this->role) {
            'instructor' => $this->hasOne(Instructor::class),
            'student' => $this->hasOne(Student::class),
            'supervisor' => $this->hasOne(Supervisor::class),
            default => null
        };
    }
    public function canLogin()
{
    return $this->status === 1;
}

    public function isEmailVerified()
    {
        return !is_null($this->email_verified_at);
    }

    public function markEmailAsVerified()
    {
        return $this->update([
            'email_verified_at' => now(),
            'otp' => null,
            'otp_expires_at'=> null,
        ]);
    }
    public function isUserVerified(){
        return $this->update([
            'is_verified' => 1,
        ]);
    }

    public function generateOTP()
    {
        $otp = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        $this->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(15)
        ]);

        return $otp;
    }

    public function verifyOTP($otp)
    {
        if ($this->otp == $otp && $this->otp_expires_at > now()) {
            return true;
        }
        return false;
    }

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isStudent()
    {
        return $this->role === self::ROLE_STUDENT;
    }

    public function isInstructor()
    {
        return $this->role === self::ROLE_INSTRUCTOR;
    }

    public function isSupervisor()
    {
        return $this->role === self::ROLE_SUPERVISOR;
    }

    /**
     * Get the admin associated with the user.
     */
    public function admin()
    {
        return $this->hasOne(Admin::class, 'user_id');
    }

    /**
     * Get the instructor associated with the user.
     */
    public function instructor()
    {
        return $this->hasOne(Instructor::class, 'user_id');
    }

    /**
     * Get the student associated with the user.
     */
    public function student()
    {
        return $this->hasOne(Student::class, 'user_id');
    }

    /**
     * Get the supervisor associated with the user.
     */
    public function supervisor()
    {
        return $this->hasOne(Supervisor::class, 'user_id');
    }

    /**
     * Dynamically return the associated model based on the user's role.
     */
    public function roleData()
    {
        // Depending on the role, return the correct related model
        if ($this->hasRole('admin')) {
            return $this->admin;
        } elseif ($this->hasRole('instructor')) {
            return $this->instructor;
        } elseif ($this->hasRole('student')) {
            return $this->student;
        } elseif ($this->hasRole('supervisor')) {
            return $this->supervisor;
        }

        return null;
    }

    public function getRoleInfoAttribute()
    {
        /// Dynamically call the relationship based on the role
        return $this->{$this->role} ?? null;
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
