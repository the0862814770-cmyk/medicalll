<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'student_id',
        'status',
        'profile_photo_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isStaff()
    {
        return $this->role === 'staff';
    }

    public function isExecutive()
    {
        return $this->role === 'executive';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    public function medicineRequests()
    {
        return $this->hasMany(MedicineRequest::class);
    }

    public function kitRequests()
    {
        return $this->hasMany(KitRequest::class);
    }

    public function approvedRequests()
    {
        return $this->hasMany(MedicineRequest::class, 'approved_by');
    }

    public function transactions()
    {
        return $this->hasMany(SupplyTransaction::class, 'performed_by');
    }
}
