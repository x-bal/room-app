<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function details()
    {
        return $this->hasMany(DetailTransaction::class);
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class)->withPivot('day');
    }
}
