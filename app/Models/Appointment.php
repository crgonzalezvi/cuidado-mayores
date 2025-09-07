<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = ['user_id', 'title', 'date', 'time', 'location', 'notes'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
