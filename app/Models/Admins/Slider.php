<?php

namespace App\Models\Admins;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'slider_image',
        'image_url',
        'cid',
        'button',
        'heading',
        'title_size',
        'p',
        'ga_id',
        'ga_name',
        'sort',
        'status',
    ];
}
