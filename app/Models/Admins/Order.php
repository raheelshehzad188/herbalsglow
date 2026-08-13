<?php

namespace App\Models\Admins;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory, \App\Models\Concerns\BelongsToStore;
    protected $table = 'custom_order';
    protected $fillable = ['store_id'];
}
