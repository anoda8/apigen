<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audiences extends Model
{
    use HasFactory;

    protected $fillable = ['photoUrl', 'entry_date', 'latitude', 'longitude', 'message', 'token', 'saved', 'user_id', 'events_id'];

    public function event()
    {
        return $this->belongsTo(Events::class, 'events_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
