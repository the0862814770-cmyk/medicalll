<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirstAidKit extends Model
{
    use HasFactory;

    protected $fillable = ['kit_code', 'name', 'status', 'description'];

    public function items()
    {
        return $this->hasMany(KitItem::class);
    }

    public function requests()
    {
        return $this->hasMany(KitRequest::class);
    }

    public function getIsAvailableAttribute()
    {
        return $this->status === 'available';
    }
}
