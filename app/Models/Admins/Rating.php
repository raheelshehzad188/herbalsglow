<?php

namespace App\Models\Admins;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory, BelongsToStore;

    protected $table = 'rating';

    protected $fillable = [
        'store_id', 'pid', 'status', 'is_read', 'name', 'email', 'review', 'rate', 'question',
    ];
}
