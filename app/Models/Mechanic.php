<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mechanic extends Model
{
    use HasFactory;

    protected $table = 'mechanics';

    protected $fillable = [

        'document',
        'full_name',
        'phone',
        'commission',
        'email',
        'status',

    ];

    protected $casts = [

        'status' => 'boolean',
    ];
}
