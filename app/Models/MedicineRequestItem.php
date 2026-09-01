<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineRequestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'medicine_request_id', 'supply_id', 'quantity_requested', 'quantity_approved'
    ];

    public function request()
    {
        return $this->belongsTo(MedicineRequest::class, 'medicine_request_id');
    }

    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }
}
