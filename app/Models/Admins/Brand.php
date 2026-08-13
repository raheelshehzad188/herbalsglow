<?php

namespace App\Models\Admins;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory, BelongsToStore;

    protected $fillable = ['store_id', 'name', 'slug', 'status', 'title', 'description', 'keywords', 's_schema'];
}
