<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','take_photo', 'take_location', 'take_time', 'start_date', 'end_date', 'notes', 'token'];

}
