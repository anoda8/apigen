<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audiences extends Model
{
    use HasFactory;

    protected $fillable = ['photoUrl', 'entry_date', 'latitude', 'longitude', 'message', 'token', 'saved'];


}
