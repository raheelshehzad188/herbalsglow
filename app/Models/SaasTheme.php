<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaasTheme extends Model
{
    protected $table = 'saas_themes';
    protected $fillable = [
        'name', 'slug', 'category', 'description', 'image',
        'demo_url', 'engine_theme', 'sort', 'status',
    ];
    protected $casts = ['status' => 'boolean', 'engine_theme' => 'integer'];
}
