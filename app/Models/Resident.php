<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Resident extends Model
{
    use HasFactory, HasApiTokens;

    protected $guarded =['id'];

    public function houses(){
        return $this->hasMany(House::class);
    }

    public function payments(){
        return $this->hasMany(Payment::class);
    }
}
