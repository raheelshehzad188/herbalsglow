<?php

namespace App\Models\Admins;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory, BelongsToStore;

    protected $table = 'sub_categories';

    protected $fillable = ['store_id', 'name', 'slug', 'category_id', 'status'];
}
