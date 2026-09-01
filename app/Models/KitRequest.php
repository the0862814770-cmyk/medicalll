<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KitRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_number', 'user_id', 'first_aid_kit_id', 'purpose',
        'borrow_date', 'expected_return_date', 'actual_return_date',
        'status', 'notes', 'approved_by', 'executive_approved_by', 'executive_approved_at', 'document_path',
        'activity_name', 'quantity', 'participants_count'
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'expected_return_date' => 'date',
        'actual_return_date' => 'date',
        'executive_approved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kit()
    {
        return $this->belongsTo(FirstAidKit::class, 'first_aid_kit_id');
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
        $prefix = 'KR-' . date('Ymd');
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
            'borrowed' => 'กำลังยืม',
            'return_pending' => 'รอรับคืน',
            'returned' => 'คืนแล้ว',
            'rejected' => 'ปฏิเสธ',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'executive_approved' => 'primary',
            'approved' => 'info',
            'borrowed' => 'primary',
            'return_pending' => 'secondary',
            'returned' => 'success',
            'rejected' => 'danger',
            default => 'secondary',
        };
    }
}
