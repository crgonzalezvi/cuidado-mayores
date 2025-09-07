<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmergencyContact extends Model
{
    protected $fillable = ['user_id', 'name', 'phone', 'email', 'relationship'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
