<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaasFeature extends Model
{
    protected $table = 'saas_features';
    protected $fillable = ['section', 'title', 'body', 'icon', 'sort', 'status'];
    protected $casts = ['status' => 'boolean'];
}
