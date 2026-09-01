<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KitItem extends Model
{
    use HasFactory;

    protected $fillable = ['first_aid_kit_id', 'supply_id', 'quantity'];

    public function kit()
    {
        return $this->belongsTo(FirstAidKit::class, 'first_aid_kit_id');
    }

    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }
}
