<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaasApp extends Model
{
    protected $table = 'saas_apps';
    protected $fillable = [
        'name', 'slug', 'category', 'description', 'icon', 'color', 'sort', 'status',
    ];
    protected $casts = ['status' => 'boolean'];
}
