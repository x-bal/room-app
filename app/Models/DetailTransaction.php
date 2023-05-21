<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaction extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function extra()
    {
        return $this->belongsTo(ExtraChange::class, 'extra_change_id');
    }
}
