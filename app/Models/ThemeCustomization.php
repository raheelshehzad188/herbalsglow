<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThemeCustomization extends Model
{
    protected $table = 'theme_customizations';

    protected $fillable = [
        'store_id',
        'theme_id',
        'values',
    ];

    protected $casts = [
        'values' => 'array',
        'theme_id' => 'integer',
        'store_id' => 'integer',
    ];
}
