<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_number', 'user_id', 'symptoms', 'status',
        'staff_notes', 'approved_by', 'approved_at',
        'executive_approved_by', 'executive_approved_at'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'executive_approved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(MedicineRequestItem::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function executiveApprover()
    {
        return $this->belongsTo(User::class, 'executive_approved_by');
    }

    public static function generateRequestNumber()
    {
        $prefix = 'MR-' . date('Ymd');
        $lastRequest = static::where('request_number', 'like', $prefix . '%')
            ->orderBy('request_number', 'desc')
            ->first();

        if ($lastRequest) {
            $lastNumber = intval(substr($lastRequest->request_number, -4));
            return $prefix . '-' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        }

        return $prefix . '-0001';
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'รอดำเนินการ',
            'executive_approved' => 'อนุมัติโดยผู้บริหาร',
            'approved' => 'อนุมัติโดยพยาบาล',
            'rejected' => 'ปฏิเสธ',
            'dispensed' => 'จ่ายยาแล้ว',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'executive_approved' => 'primary',
            'approved' => 'info',
            'rejected' => 'danger',
            'dispensed' => 'success',
            default => 'secondary',
        };
    }
}
