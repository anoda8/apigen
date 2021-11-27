<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forgot extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'kode_aktivasi'];

}
