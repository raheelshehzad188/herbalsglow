<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaasFaq extends Model
{
    protected $table = 'saas_faqs';
    protected $fillable = ['question', 'answer', 'sort', 'status'];
    protected $casts = ['status' => 'boolean'];
}
