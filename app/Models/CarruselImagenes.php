<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarruselImagenes extends Model
{
    use HasFactory;

    protected $fillable = [
        'imagen',
        'alt'
    ];
}
