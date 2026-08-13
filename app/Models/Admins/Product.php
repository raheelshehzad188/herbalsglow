<?php

namespace App\Models\Admins;

use App\Models\Concerns\BelongsToStore;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, BelongsToStore;
    
    protected $fillable = [
        'store_id', 'product_name', 'slug', 'category_id', 'brand', 'product_details', 
        'short_discriiption', 'tags', 'product_code', 'sku', 'selling_price', 
        'discount_price', 'status', 'image_one', 'gallary_images'
    ];
    
    // Relationship with Brand
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand', 'id');
    }
    
    // Relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
