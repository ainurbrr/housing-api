<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class House extends Model
{
    use HasFactory, HasApiTokens;

    protected $guarded =['id'];

    public function resident(){
        return $this->belongsTo(Resident::class, 'residents_id', 'id');
    }

    public function resident_history(){
        return $this->hasMany(HouseHistory::class);
    }

    public function payment_history(){
        return $this->hasMany(Payment::class);
    }
}
