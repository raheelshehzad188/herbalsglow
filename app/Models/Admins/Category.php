<?php

namespace App\Models\Admins;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, BelongsToStore;

    protected $fillable = ['store_id', 'name', 'slug', 'status', 'image', 'short_description', 'sort', 'home_sort'];
}
