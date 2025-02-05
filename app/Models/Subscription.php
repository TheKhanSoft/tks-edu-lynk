<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'name',
        'price',
        'duration',
        'description',
        'features',
        'status',
    ];


    protected $casts = [
        'price' => 'decimal:2',
        'duration' => 'integer',
        'features' => 'array|json',
        'status' => 'boolean',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'duration' => 'integer',
            'features' => 'array|json',
            'status' => 'boolean',
        ];
    }
}
