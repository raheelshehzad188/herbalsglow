<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Admins\Product;
use App\Models\Admins\Category;
use App\Models\Admins\Brand;
use App\Models\Admins\Rating;
use App\Models\Admins\Gallerie;
use App\Models\Admins\SubCategory;
use Illuminate\Http\Request;
use DB;

class ProductApiController extends Controller
{
    /**
     * Get product data in import format
     * Similar to openteleshop.pk/import.php?pid= format
     * If pid is 0, returns the first available product (next product)
     */
    public function getProductData($pid)
    {
        // Set CORS headers
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');
        header("Access-Control-Allow-Headers: X-Requested-With");
        header('Content-Type: application/json');
        
        // If pid is 0, get the first available product (next product)
        if ($pid == 0 || $pid == '0') {
            $product = Product::where('status', 1)
                ->orderBy('id', 'ASC')
                ->first();
            
            if (!$product) {
                return response()->json([
                    'error' => true,
                    'msg' => 'No products found'
                ], 404);
            }
        } else {
            $product = Product::where('id', '>', $pid)
                ->orderBy('id', 'asc')
                ->first();

            
            if (!$product) {
                return response()->json([
                    'error' => true,
                    'msg' => 'Product not found'
                ], 404);
            }
        }
        
        // Get category
        $category = Category::find($product->category_id);
        $categories = [];
        if ($category) {
            $categories[] = [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug ?? ''
            ];
        }
        
        // Get brand
        $brand = null;
        $brandName = '';
        if ($product->brand) {
            $brand = Brand::find($product->brand);
            if ($brand) {
                $brandName = $brand->name ?? '';
            }
        }
        
        // Get subcategory
        $subcategory = null;
        $subcategoryName = '';
        if ($product->subcategory_id) {
            $subcategory = SubCategory::find($product->subcategory_id);
            if ($subcategory) {
                $subcategoryName = $subcategory->name ?? '';
            }
        }
        
        // Get images
        $baseUrl = url('/');
        $imgUrl = env('IMG_URL', $baseUrl);
        
        // Featured image
        $featuredImage = '';
        if (!empty($product->image_one)) {
            // Check if it's already a full URL
            if (filter_var($product->image_one, FILTER_VALIDATE_URL)) {
                $featuredImage = $product->image_one;
            } elseif (strpos($product->image_one, 'http') === 0) {
                $featuredImage = $product->image_one;
            } else {
                // Use custom_assets helper or construct URL
                if (function_exists('custom_assets')) {
                    $featuredImage = custom_assets($product->image_one);
                } else {
                    $featuredImage = rtrim($imgUrl, '/') . '/' . ltrim($product->image_one, '/');
                }
            }
        }
        
        // Gallery images
        $galleryImages = [];
        
        // Check if product has gallery images stored in gallary_images field
        if (!empty($product->gallary_images)) {
            $galleryArray = explode(',', $product->gallary_images);
            foreach ($galleryArray as $img) {
                $img = trim($img);
                if (!empty($img)) {
                    if (filter_var($img, FILTER_VALIDATE_URL)) {
                        $galleryImages[] = $img;
                    } elseif (strpos($img, 'http') === 0) {
                        $galleryImages[] = $img;
                    } else {
                        if (function_exists('custom_assets')) {
                            $galleryImages[] = custom_assets($img);
                        } else {
                            $galleryImages[] = rtrim($imgUrl, '/') . '/' . ltrim($img, '/');
                        }
                    }
                }
            }
        }
        
        // Also check Gallerie table for additional images
        $galleryRecords = Gallerie::where('product_id', $product->id)->get();
        foreach ($galleryRecords as $gallery) {
            if (!empty($gallery->photo)) {
                $imgUrlFinal = '';
                if (filter_var($gallery->photo, FILTER_VALIDATE_URL)) {
                    $imgUrlFinal = $gallery->photo;
                } elseif (strpos($gallery->photo, 'http') === 0) {
                    $imgUrlFinal = $gallery->photo;
                } else {
                    if (function_exists('custom_assets')) {
                        $imgUrlFinal = custom_assets($gallery->photo);
                    } else {
                        $imgUrlFinal = rtrim($imgUrl, '/') . '/' . ltrim($gallery->photo, '/');
                    }
                }
                // Avoid duplicates
                if (!in_array($imgUrlFinal, $galleryImages)) {
                    $galleryImages[] = $imgUrlFinal;
                }
            }
        }
        
        // Get reviews/ratings
        $reviews = [];
        $ratings = Rating::where('pid', $product->id)
            ->where('status', 1)
            ->orderBy('id', 'DESC')
            ->get();
        
        foreach ($ratings as $rating) {
            $reviews[] = [
                'author' => $rating->name ?? 'Anonymous',
                'content' => $rating->review ?? '',
                'rating' => (int)($rating->rate ?? 5),
                'date' => $rating->created_at ?? date('Y-m-d H:i:s')
            ];
        }
        
        // Get tags
        $tags = [];
        if (!empty($product->tags)) {
            $tagArray = explode(',', $product->tags);
            foreach ($tagArray as $tag) {
                $tag = trim($tag);
                if (!empty($tag)) {
                    $tags[] = [
                        'name' => $tag,
                        'slug' => strtolower(str_replace(' ', '-', $tag))
                    ];
                }
            }
        }
        $product->image_one = str_replace('public/images/products/', '', $product->image_one);
        
        // Format response to match import.php expected format
        $response = [
            'id' => $product->id,
            'name' => $product->product_name ?? '',
            'slug' => $product->slug ?? '',
            'price' => (float)($product->discount_price ?? $product->selling_price ?? 0),
            'regular_price' => (float)($product->selling_price ?? 0),
            'sale_price' => (float)($product->discount_price ?? 0),
            'stock_quantity' => (int)($product->product_quantity ?? 0),
            'short_description' => strip_tags($product->short_discriiption ?? ''),
            'description' => strip_tags($product->product_details ?? ''),
            'categories' => $categories,
            'brand_detail' => $brand,
            'brand_name' => $brandName,
            'subcategory_name' => $subcategoryName,
            'brand' => $brand ? [
                'id' => $brand->id,
                'name' => $brand->name ?? '',
                'slug' => $brand->slug ?? ''
            ] : null,
            'image_one'=>$product->image_one,
            'images' => [
                'featured' => $featuredImage,
                'gallery' => $galleryImages
            ],
            'reviews' => [
                'comments' => $reviews
            ],
            'tags' => $tags,
            'status' => $product->status ?? 1,
            'sku' => $product->product_code ?? $product->sku ?? '',
            'created_at' => $product->created_at ?? date('Y-m-d H:i:s'),
            'updated_at' => $product->updated_at ?? date('Y-m-d H:i:s')
        ];
        
        return response()->json($response);
    }
    
    /**
     * Get brand data in import format
     * If bid is 0, returns brand with ID 1
     * If bid is 1, returns brand with ID 2
     * Continues sequentially (bid + 1)
     */
    public function getBrandData($bid)
    {
        // Set CORS headers
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');
        header("Access-Control-Allow-Headers: X-Requested-With");
        header('Content-Type: application/json');
        
        // Convert bid to integer
        $bid = (int)$bid;
        
        // Calculate next brand ID: 0 -> 1, 1 -> 2, 2 -> 3, etc.
        $nextBrandId = $bid + 1;
        
        // Get brand by calculated ID
        $brand = Brand::where('id', $nextBrandId)->first();
        
        if (!$brand) {
            return response()->json([
                'error' => true,
                'msg' => 'Brand not found'
            ], 404);
        }
        
        // Get logo URL
        $baseUrl = url('/');
        $imgUrl = env('IMG_URL', $baseUrl);
        $logoUrl = '';
        
        if (!empty($brand->logo)) {
            // Check if it's already a full URL
            if (filter_var($brand->logo, FILTER_VALIDATE_URL)) {
                $logoUrl = $brand->logo;
            } elseif (strpos($brand->logo, 'http') === 0) {
                $logoUrl = $brand->logo;
            } else {
                // Use custom_assets helper or construct URL
                if (function_exists('custom_assets')) {
                    $logoUrl = custom_assets($brand->logo);
                } else {
                    $logoUrl = rtrim($imgUrl, '/') . '/' . ltrim($brand->logo, '/');
                }
            }
        }
        
        // Format response
        $response = [
            'id' => $brand->id,
            'name' => $brand->name ?? '',
            's_keywords' => $brand->s_keywords ?? '',
            'title' => $brand->title ?? '',
            'slug' => $brand->slug ?? '',
            'logo' => $logoUrl,
            'description' => $brand->description ?? '',
            'status' => $brand->status ?? 1
        ];
        
        return response()->json($response);
    }
}

