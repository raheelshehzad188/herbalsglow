<?php

namespace App\Http\Controllers\Front;

use App\Models\Admins\Category;
use App\Models\Admins\Box;
use App\Models\Admins\Pfaq;
use App\Models\Admins\CategoriesToMeta;
use App\Models\Admins\Blog_Post;
use App\Models\Admins\Slider;
use App\Models\Admins\Client;
use App\Models\Admins\PaymentMethod;
use App\Models\Admins\Brand;
use App\Models\Admins\User;
use App\Models\Admins\SubCategory;
use App\Models\Admins\Blog_Category;
use App\Models\Admins\Product; 
use App\Models\Order;
use App\Models\Admins\Contact;
use App\Models\Admins\Setting;
use App\Models\Admins\Faq;
use App\Models\Admins\Pages;
use App\Models\Admins\Rating;
use App\Models\Admins\Blog_Comment;
use App\Models\Admins\Coupon;
use App\Models\Admins\Gallerie;
use App\Models\Admins\Learn_setting;
use App\Models\Newsletter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admins\Size;
use App\Models\Admins\Colors;
use App\Models\Admins\Shap;
use App\Models\Admins\Import;
use Symfony\Component\Mime\Part\HtmlPart;

use App\Helpers\Cart;
use Session;
use Mail;
use DB;
use Cookie;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;





class FrontController extends Controller
{
    public function __construct()
{
    
}
public function view($view, $data = array())
{
    if (\App\Support\DomainResolver::isSaasDomain()) {
        abort(404);
    }

    $them_num = 2;
    $siteSetting = null;
    $currentStore = \App\Support\CurrentStore::get();
    if (!$currentStore) {
        abort(404);
    }
    try {
        $them_num = \App\Support\CurrentStore::theme();
        $siteSetting = DB::table('setting')
            ->when(
                \Illuminate\Support\Facades\Schema::hasColumn('setting', 'store_id'),
                function ($q) use ($currentStore) {
                    $q->where('store_id', $currentStore->id);
                }
            )
            ->orderBy('id')
            ->first();
        if (!$siteSetting) {
            $siteSetting = DB::table('setting')->where('id', 1)->first();
        }
        if (in_array((int) $currentStore->active_theme, storefront_theme_ids(), true)) {
            $them_num = (int) $currentStore->active_theme;
        }
    } catch (\Exception $e) {
        // fallback to default theme
        try {
            $siteSetting = DB::table('setting')->where('id', 1)->first();
        } catch (\Exception $e2) {
            $siteSetting = null;
        }
    }
    $previewTheme = (int) request()->query('theme', 0);
    if ($previewTheme && in_array($previewTheme, storefront_theme_ids(), true)) {
        $them_num = $previewTheme;
    }
    $ctheme = 'theme'.$them_num;
    $layout = 'theme'.$them_num.'.layout';
    $assets = rtrim(url('/theme'.$them_num), '/') . '/';
    $data['layout'] = $layout;
    $data['assets_url'] = $assets;
    $data['active_theme'] = $them_num;
    $data['setting'] = $siteSetting;
    $data['currentStore'] = \App\Support\CurrentStore::get();

    $storeId = \App\Support\CurrentStore::id();
    $pageQuery = function (string $type) use ($storeId) {
        $q = DB::table('pages')->where('status', 1)->where('menu_type', $type)->orderBy('position');
        if ($storeId && \Illuminate\Support\Facades\Schema::hasColumn('pages', 'store_id')) {
            $q->where('store_id', $storeId);
        }
        return $q->get();
    };
    $data['header_pages'] = $pageQuery('header');
    $data['top_bar_pages'] = $pageQuery('top_bar');
    $data['theme_config'] = \App\Support\ThemeRegistry::get($them_num);

    if (in_array($them_num, storefront_theme_ids(), true)) {
        $headerMenuCategories = Category::where('status', 1)
            ->where('show_on_home', 1)
            ->orderBy('home_sort', 'ASC')
            ->orderBy('id', 'DESC')
            ->get();

        if ($headerMenuCategories->isNotEmpty()) {
            $subCategoriesByCategory = SubCategory::whereIn('category_id', $headerMenuCategories->pluck('id'))
                ->orderBy('name')
                ->get()
                ->groupBy('category_id');

            foreach ($headerMenuCategories as $headerCategory) {
                $headerCategory->subcategories = $subCategoriesByCategory->get($headerCategory->id, collect());
            }
        }

        $data['header_menu_categories'] = $headerMenuCategories;
        $data['footer_menus'] = footer_menus();
    }

    return view($ctheme .'.'.$view,$data);
}

    

    public function index()
    {
        
        
        // shaheer
        
        $posts= Product::latest()->get();
        $xml = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach ($posts as $post)
        {
            $xml .= '<url>
                <loc>'.url('product/').'/'.$post->slug.'</loc> 
                <priority>1.0</priority>
                <changefreq>daily</changefreq>
                </url>';
        }
        $posts= Category::latest()->get();
        foreach ($posts as $post)
        {
            $xml .= '<url>
                <loc>'.url('category/').'/'.$post->slug.'</loc> 
                <priority>1.0</priority>
                <changefreq>daily</changefreq>
                </url>';
        }
    
        $xml .= '</urlset>';
        $myfile = fopen("sitemap.xml", "w") or die("Unable to open file!");
        $r = fwrite($myfile, $xml);
        fclose($myfile);
        return $r;
    }
    
    public function categories()
    {
        $posts= Category::latest()->get();
        
        $xml = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach ($posts as $post)
        {
            $xml .= '<url>
                <loc>'.env('APP_URL').'category'.'/'.$post->slug.'</loc> 
                <priority>1.0</priority>
                <changefreq>daily</changefreq>
                </url>';
        }
    
        $xml .= '</urlset>';
        $myfile = fopen("category.xml", "w") or die("Unable to open file!");
        $r = fwrite($myfile, $xml);
        fclose($myfile);
        return $r;
    }
    
    public function products_tag()
    {
        $posts= Product::latest()->get();
        
        $xml = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach ($posts as $post)
        {
            
            $strs = explode(",",$post->tags);
            $str =  implode(" ",$strs);
            $rep = str_replace("&","and",$str);
            $repl = explode(" ",$rep);
            //print_r($repl);
           
             
            foreach ($repl as $str)
            {
                $xml .= '<url>
                    <loc>'.url('tags/').'/'.$str.'</loc> 
                    <priority>1.0</priority>
                    <changefreq>daily</changefreq>
                    </url>';
            }
            
        }
    
        $xml .= '</urlset>';
        $myfile = fopen("product_tags.xml", "w") or die("Unable to open file!");
        $r = fwrite($myfile, $xml);
        fclose($myfile);
        return $r;
    }
    
    public function home(Request $request)
    {
         $page = "home";
        $Slider=Slider::all();
        $Rating=Rating::all();
        
        $categories=Category::all();
        dd($categories);
        $shap = Shap::all();
        $setting = DB::table('setting')
            ->where('id', '=', '1')
            ->first();

        $meta = DB::table('setting')
            ->where('id', '=', '1')
            ->first();
        if ($meta) {
            $meta->url = url('/');
        }

        $products=Product::select('products.*')->where('status','1')->orderBy('products.id','DESC')->get();
    
        
        $aproducts=Product::select('products.*')->where('status','1')->orderBy('products.created_at','DESC')->limit(10)->get();
        $onslaeproducts=Product::select('products.*')->where('status','1')->where('Sale','1')->orderBy('products.id','DESC')->limit(10)->get();
        $mostviewproducts=Product::select('products.*')->where('status','1')->orderBy('view','DESC')->limit(10)->get();
        $home_cats = DB::table('home_cats')->where->orderBy('home_cats.sort')->get();
        Session::put('title','Home');
        
        return view('front.home1',compact('page','products','categories','fproducts','setting','aproducts','mostviewproducts','Slider','Rating','meta','onslaeproducts','shap','home_cats','posts'));
    }
    
     public function search(request $request)
    {
        
        $slug = $request->text;
        
 // $brands=Brand::all();
        $Slider=Slider::all();
        $categories=Category::all();
        // return $slug->input();
        $rproducts = product::where('status',1)->where('product_name', 'like', '%'.$slug.'%')->get();
        // $slug = $rproducts->slug;
        $search = 1;
        return view('front.result_detail',compact('rproducts','categories','search'));
    }

    public function error404()
{

    $page = "home";

    $Slider        = Slider::all();
    $Rating        = Rating::all();
    $posts         = Blog_Post::all();
    $posts_home    = Blog_Post::take(3)->get(); // (not used below but kept in case your view needs it)
    $categories = Category::select('*')
                      ->orderBy('id', 'desc')
                      ->get(); 
                      dd($categories);

    // attach product counts (faster: use count() query)
    foreach ($categories as $k => $v) {
        $categories[$k]->prod = Product::where('status', 1)
            ->where('category_id', $v->id)
            ->count();
    }

    $shap     = Shap::all();
    $setting  = DB::table('setting')->where('id', 1)->first();
    $meta     = DB::table('setting')->where('id', 1)->first();

    if ($meta) {
        $meta->url = url('/');
    }

    // product lists
    $products          = Product::where('status', 1)->orderBy('id', 'DESC')->get();
    $fproducts         = Product::where('status', 1)->where('Featured', 1)->orderBy('id', 'DESC')->limit(8)->get();
    $aproducts         = Product::where('status', 1)->orderBy('created_at', 'DESC')->limit(8)->get();
    $onslaeproducts    = Product::where('status', 1)->where('Sale', 1)->orderBy('id', 'DESC')->limit(10)->get();
    $mostviewproducts  = Product::where('status', 1)->orderBy('view', 'DESC')->limit(10)->get();
    $home_cats         = DB::table('home_cats')->where('status', 1)->orderBy('home_cats.sort')->get();

    Session::put('title', 'Home');

    $boxes = Box::all();

    // Fetch featured categories with their products (sorted by home_sort field)
    $featured_categories = Category::where('status', 1)
        ->where('show_on_home', 1)
        ->orderBy('home_sort', 'ASC')
        ->orderBy('id', 'DESC')
        ->get();
    
    // Load products for each featured category
    foreach($featured_categories as $category) {
        $category->products = Product::where('status', 1)
            ->where('category_id', $category->id)
            ->orderBy('id', 'DESC')
            ->limit(10)
            ->get();
    }
    $data = [
        'page'              => $page,
        'products'          => $products,
        'categories'        => $categories,
        'fproducts'         => $fproducts,
        'setting'           => $setting,
        'aproducts'         => $aproducts,
        'mostviewproducts'  => $mostviewproducts,
        'Slider'            => $Slider,
        'Rating'            => $Rating,
        'meta'              => $meta,
        'onslaeproducts'    => $onslaeproducts,
        'shap'              => $shap,
        'home_cats'         => $home_cats,
        'featured_categories' => $featured_categories,
        'posts'             => $posts,
        'boxes'             => $boxes,
    ];

    return $this->view('home', $data);
}
    public function home1(Request $request)
{
    // quick actions
    if ($request->query('import')) {
        return $this->import();
    }
    if ($request->query('text')) {
        return $this->search_detail1($request->query('text'));
    }

    $page = "home";

    $Slider        = Slider::where('status', 1)->orderBy('sort')->orderBy('id')->get();
    $Rating        = Rating::all();
    $posts         = Blog_Post::all();
    $posts_home    = Blog_Post::take(3)->get(); // (not used below but kept in case your view needs it)
    $categories = Category::select('*')
                      ->orderBy('id', 'desc')
                      ->get();

    // attach product counts (faster: use count() query)
    foreach ($categories as $k => $v) {
        $categories[$k]->prod = Product::where('status', 1)
            ->where('category_id', $v->id)
            ->count();
    }

    $shap     = Shap::all();
    $setting  = DB::table('setting')->where('id', 1)->first();
    $meta     = DB::table('setting')->where('id', 1)->first();
    $clients  = Schema::hasTable('clients')
        ? Client::orderBy('sort', 'ASC')->orderBy('id', 'DESC')->get()
        : collect();

    if ($meta) {
        $meta->url = url('/');
        $meta->scheme = seo_website_schema($meta);
        $meta->scheme1 = seo_organization_schema($meta);
    }

    // product lists
    $products          = Product::where('status', 1)->orderBy('id', 'DESC')->get();
    $fproducts         = Product::where('status', 1)->where('Featured', 1)->orderBy('id', 'DESC')->limit(8)->get();
    $aproducts         = Product::where('status', 1)->orderBy('created_at', 'DESC')->limit(8)->get();
    $onslaeproducts    = Product::where('status', 1)->where('Sale', 1)->orderBy('id', 'DESC')->limit(10)->get();
    $mostviewproducts  = Product::where('status', 1)->orderBy('view', 'DESC')->limit(10)->get();
    $home_cats         = DB::table('home_cats')->where('status', 1)->orderBy('home_cats.sort')->get();

    Session::put('title', 'Home');

    $boxes = Box::orderBy('sort', 'ASC')->orderBy('id', 'DESC')->get();

    // Fetch featured categories with their products (sorted by home_sort field)
    $featured_categories = Category::where('status', 1)
        ->where('show_on_home', 1)
        ->orderBy('home_sort', 'ASC')
        ->orderBy('id', 'DESC')
        ->get();
    
    // Load products for each featured category
    foreach($featured_categories as $category) {
        $category->products = Product::where('status', 1)
            ->where('category_id', $category->id)
            ->orderBy('id', 'DESC')
            ->limit(10)
            ->get();
    }
    $data = [
        'page'              => $page,
        'products'          => $products,
        'categories'        => $categories,
        'fproducts'         => $fproducts,
        'setting'           => $setting,
        'aproducts'         => $aproducts,
        'mostviewproducts'  => $mostviewproducts,
        'Slider'            => $Slider,
        'Rating'            => $Rating,
        'meta'              => $meta,
        'meta_file'         => 'meta.default',
        'onslaeproducts'    => $onslaeproducts,
        'shap'              => $shap,
        'home_cats'         => $home_cats,
        'featured_categories' => $featured_categories,
        'posts'             => $posts,
        'boxes'             => $boxes,
        'clients'           => $clients,
    ];

    return $this->view('home', $data);
}


   function login()
    {   
         Session::put('title','Login');
        return view('front.login');

    } 
       public function slugify($string) {
  return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
}
public function getImage($url){
            $ch = curl_init ($url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
            $resource = curl_exec($ch);
            curl_close ($ch);

            return $resource;
    }
    public function import()
    {  
        
        header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, POST');

header("Access-Control-Allow-Headers: X-Requested-With");
        $id = isset($_GET['id'])?$_GET['id']:0;
        $url = 'https://shoppakistan.com.pk/fetch/product.php?id='.$id;
        $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Cookie: PHPSESSID=0f0a71e4b614f91e0bc56d876dbcf796'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$response = json_decode($response,true);
$next = 0;

if($response)
{
    $products = Product::where('old_id',$response['id'])->first();
if($products)
{
    //update
    echo $next = $response['id'];
    exit();
}
else
{
$in = array(
    
    'old_id'=>$response['id'],
    'product_name'=>$response['name'],
    'size'=>$response['size'],
    'made_in'=>$response['madein'],
    'product_quantity'=>$response['qnt'],
    'product_details'=>$response['long_dis'],
    'short_discriiption'=>$response['short_dis'],
    'tags'=>$response['tags'],
    'status'=>1,
    'selling_price'=>$response['price'],
    'discount_price'=>$response['sell_price'],
    'image_one'=>"public/images/products/old/".$response['img'],
    'slug'=>$response['slug'],
    );
    $pro = DB::table('products')->insertGetId($in);
    if($pro)
    {
        $in_meta = array(
            'pid'=> $pro,
            'title'=> $response['meta_title'],
            'description'=> $response['meta_dis'],
            );
        DB::table('products_to_meta')->insertGetId($in_meta);
        $next = $response['id'];
        $pcat = 0;
        $pcatID = 0;
        if($response['brand_name'] && $pro)
        {
            $in = array(
                'name' => $response['brand_name'],
                'slug' => $this->slugify($response['brand_slug']),
                'keywords' => $response['brand_tag_slug']
                );
                $all = DB::table('brands')->where('slug',$response['brand_slug'])->first();
                $pcatID = 0;
                if($all)
                {
                    $pcatID = $all->id;
                }
                else
                {
                $pcatID = DB::table('brands')->insertGetId($in);
                    
                }
                $up = array('brand'=> $pcatID);
                    
                    $r = DB::table('products')->where('id',$pro)->update($up);
            
        }
        if($response['parent_cat'])
        {
            $pcat = $response['parent_cat'][0];
            if($pcat && isset($pcat['name']))
            {
                $al = DB::table('categories')->where('slug',$pcat['slug'])->first();
                if(!$al)
                {
                
                $in = array(
                    'name'=> $pcat['name'],
                    'slug'=> $pcat['slug'],
                    'title'=> $pcat['meta_title'],
                    'description'=> $pcat['meta_des'],
                    );
                    $pcatID = DB::table('categories')->insertGetId($in);
                    $up = array('category_id'=> $pcatID);
                    
                    $r = DB::table('products')->where('id',$pro)->update($up);
                }
                else
                {
                    $pcatID = $al->id;
                    $up = array('category_id'=> $al->id);
                    
                    $r = DB::table('products')->where('id',$pro)->update($up);
                }
            }
        }
        if($response['reviews'])
        {
            foreach($response['reviews'] as $k=>$pcat)
            {
                
                $in = array(
                    'pid'=> $pro,
                    'status'=> 0,
                    'name'=> $pcat['name'],
                    'review'=> $pcat['review'],
                    'rate'=> $pcat['rating'],
                    'email'=> $pcat['email'],
                    );
                    $pcat = DB::table('rating')->insertGetId($in);
                    
            }
        }
        if($response['faq'])
        {
            foreach($response['faq'] as $k=>$pcat)
            {
                unset($pcat['id']);
                $pcat['product_id'] = $pro;
                
                $in = $pcat;
                    $pcat = DB::table('pfaqs')->insertGetId($in);
            }
        }
        if($response['sub_cat'])
        {
            $pcat = $response['sub_cat'][0];
            if($pcat && isset($pcat['name']))
            {
                $al = DB::table('sub_categories')->where('slug',$pcat['slug'])->first();
                if(!$al)
                {
                $in = array(
                    'name'=> $pcat['name'],
                    'slug'=> $pcat['slug'],
                    'category_id'=> $pcatID,
                    // 'title'=> $pcat['meta_title'],
                    // 'description'=> $pcat['meta_des'],
                    );
                    $pcat = DB::table('sub_categories')->insertGetId($in);
                    $up = array('subcategory_id'=> $pcat);
                    Product::where('id',$pro)->update($up);
                }
                else
                {
                    $up = array('subcategory_id'=> $al->id);
                    
                    $r = DB::table('products')->where('id',$pro)->update($up);
                }
            }
        }
    }
    
        //add here
}
}
echo $next;
exit();
        
       $products = Product::where('status','1')->get();
       
       foreach($products as $v){
          
           $cate = Category::where('old_id',$v['cate_old_id'])->first();
           if($cate){
           $update = Product::where('id', $v['id'])->update(['category_id'=>$cate->id]);
           }
       }
       if($update){
           echo "Data updated successfully!";
       }else{
           echo "Data not updated";
       }
    }
    
    //  public function import()
    // {
        
    //     $data= Import::where('post_type','product')->get();
    //     Session::put('title','import');
    //     return view('front.import',compact('data'));
    // }
    
     public function import_tag()
    {
        
        $data= Import::where('post_type','product')->get();
        Session::put('title','import');
        return view('front.import',compact('data'));
    }
    
   public function import_all($id)
{
    // Initialize cURL session
    $ch = curl_init();

    // Set the cURL options
    $url = 'https://telemartpakistan.com/wp-api.php?action=related_products&products=' . $id; // Replace with the actual API endpoint
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string instead of outputting it
    // You can set more options like headers, authentication, etc. using curl_setopt

    // Execute cURL session and store the response
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        echo 'cURL Error: ' . curl_error($ch);
    }

    // Close cURL session
    curl_close($ch);

    // Process the API response (assuming it's in JSON format)
    if ($response) {
        $api_data = json_decode($response, true);
        if (is_array($api_data) && count($api_data) > 0) {
            foreach ($api_data as $product) {
                $categories = $product['categories'];

                if (!empty($categories[0])) {
                    $name = $categories[0]['name'];
                    $slug = $categories[0]['slug'];
                    $cate_id = $categories[0]['id'];

                    // Check if category already exists in the database by slug
                    $existingCategory = Category::where('slug', $slug)->first();

                    if ($existingCategory) {
                        // Update the existing category
                        Category::where('slug', $slug)->update(['name' => $name,'old_id'=>$cate_id]);
                        $update =  Product::where('old_id', $id)->update(['cate_old_id'=>$cate_id]);
                        echo "Category updated: $name\n";
                    } else {
                        // Insert a new category
                        Category::insert(['name' => $name, 'slug' => $slug,'old_id'=>$cate_id]);
                        $update =  Product::where('old_id', $id)->update(['cate_old_id'=>$cate_id]);
                        echo "Category inserted: $name\n";
                    }
                }
            }
        } else {
            echo 'No data found in the API response.';
        }
    } else {
        echo 'No response from the API.';
    }
}

    
//   public function import_all($id)
//     {
//     // Initialize cURL session
//     $ch = curl_init();

//     // Set the cURL options
//     $url = 'https://telemartpakistan.com/wp-api.php?action=related_products&products=' . $id; // Replace with the actual API endpoint
//     curl_setopt($ch, CURLOPT_URL, $url);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string instead of outputting it
//     // You can set more options like headers, authentication, etc. using curl_setopt

//     // Execute cURL session and store the response
//     $response = curl_exec($ch);

//     // Check for cURL errors
//     if (curl_errno($ch)) {
//         echo 'cURL Error: ' . curl_error($ch);
//     }

//     // Close cURL session
//     curl_close($ch);

//     // Process the API response (assuming it's in JSON format)
//     if ($response) {
//         $api_data = json_decode($response, true);
//         if (is_array($api_data) && count($api_data) > 0) {
//             foreach ($api_data as $product) {
//                 $tags = $product['tags'];
//                 $tagArray = [];

//                 foreach ($tags as $v) {
//                     $tag = preg_replace('/\s+/', '-', $v['name']);
//                     $tagArray[] = $tag;
//                 }

//                 $tagsString = implode(',', $tagArray);

//                 $data = [
//                     'tags' => $tagsString
//                 ];

//                 $insert = Product::where('old_id', $id)->update($data);
//                 echo 'Data found in the API response And updated.';
//             }
//         } else {
//             echo 'No data found in the API response.';
//         }
//     } else {
//         echo 'No response from the API.';
//     }
// }

    
    //  public function import_all_old($id)
    // {
    //     // Initialize cURL session
    //     $ch = curl_init();
        
    //     // Set the cURL options
    //     $url = 'https://telemartpakistan.com.pk/wp-api.php?action=related_products1&products='.$id; // Replace with the actual API endpoint
    //     curl_setopt($ch, CURLOPT_URL, $url);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string instead of outputting it
    //     // You can set more options like headers, authentication, etc. using curl_setopt
        
    //     // Execute cURL session and store the response
    //     $response = curl_exec($ch);
        
    //     // Check for cURL errors
    //     if (curl_errno($ch)) {
    //         echo 'cURL Error: ' . curl_error($ch);
    //     }
        
    //     // Close cURL session
    //     curl_close($ch);
        
    //     // Process the API response (assuming it's in JSON format)
    //     if ($response) {
    //         $data = json_decode($response, true); 

    //     if (is_array($data) && count($data) > 0) {
    //         foreach ($data as $product) {
    //             $id = $product['id'];
    //             $name = $product['name'];
    //             $slug = $product['slug'];
    //             $short_description = $product['short_description'];
    //             $description = $product['description'];
    //             $price = $product['price'];
    //             $regular_price = $product['regular_price'];
    //             $sale_price = $product['sale_price'];
    //             $thumb_url = $product['thumb'];
    //             $images = $product['images'];
        
    //             $local_thumb_path = 'public/images/products/' . basename($thumb_url);
    //             file_put_contents($local_thumb_path, file_get_contents($thumb_url));
    //             $local_thumb_url =  $local_thumb_path;
    //             $data = array(
    //                 'product_name'=>$name,
    //                 'slug'=>$slug,
    //                 'product_details'=>$description,
    //                 'short_discriiption'=>$name,
    //                 'selling_price'=>$regular_price,
    //                 'discount_price'=>$sale_price,
    //                 'image_one'=>$local_thumb_url,
    //                 'product_quantity'=>'200',
    //                 'shipping_price'=>'200',
    //                 'old_id'=>$id,
    //                 );
    //             $insert = Product::insert($data);
        
    //             // foreach ($images as $image_url) {
    //             //     $local_image_path = 'path/to/save/images/' . basename($image_url);
    //             //     file_put_contents($local_image_path, file_get_contents($image_url));
                  
    //             // }
    //             echo 'Data found in the API response And inserted.';
    //         }
    //     } else {
    //         echo 'No data found in the API response.';
    //     }

    //     } else {
    //         echo 'No response from the API.';
    //     }

    // }
    
    function cart_data()
    {   
        return view('front.cart_data');

    } 
    function hearder_cart()
    {   
        return view('front.hearder_cart');

    } 
    function user_login(Request $req)
    {   
        $user = User::where(['email'=>$req->email])->first();
        $data = User::all();
        if ($user) {

            if ($req->pass == $user->password) {
                $req->session()->flash('invalid','Success');
                $req->session()->put('user',$user); 
                return redirect('/my_account');

            }else{

                // return "password not matched";
                // $req->session()->flash('invalid','Enter Your Correct Password');
                // return view('front.login');
                return redirect('/login')->with([
                'msg'=>'Enter Your Correct Password',
                'msg_type'=>'success',
            ]);
            }

        }else{
            return redirect('/login')->with([
                'msg'=>'Please Try Again',
                'msg_type'=>'danger',
            ]);
            // $req->session()->flash('invalid','Invalid Email & Password');
            // return view('front.login');
        }

    }
    function logout(Request $req)
    {
        if ($req->session()->has('user')) {
            $req->session()->pull('user');          
            return redirect('/login'); 
        }
    }
    
     function register(Request $req)
    {   
        $check_email=User::where('email',$req->email)->first();
        if(isset($check_email)){
            return redirect()->back()->with([
                'msg'=>'This Email Address already Exists',
                'msg_type'=>'danger',
            ]);
        }
        $User=new User();
        $User->name = $req->name;
        $User->email = $req->email;
        $User->password = $req->pass;
        $User->phone = $req->phone;
        $User->address = $req->address;
        $User->city = $req->city;
        $User->country = $req->country;
        $User->save();
        if($User->save()){
            $to_name = $req->name;
            $to_email = $req->email;
            $data = [
                    'name' => $req->name . '! Your Account Create Successfully. Your Details Are:',
                    'body' => '<strong>Your Name: ' . $req->name . '</strong><br>' .
                              '<strong>Your Email: ' . $req->email . '</strong><br>' .
                              '<strong>Your Password: ' . $req->pass . '</strong><br>'
                ];
            Mail::send('emails.mail', $data, function($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)
            ->subject('Account Opening’');
            $message->from('order@pakteleshop.com','Account Opening');
            });
            
    			return redirect('/login')->with([
                'msg'=>'User Registered successfully',
                'msg_type'=>'success',
            ]);
            
        }else{
            return redirect('/user_register')->with([
                'msg'=>'Please Try Again',
                'msg_type'=>'danger',
            ]);
        }

    }
     function user_update(Request $request)
    {   
        $in = array(
                
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->pass,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'country' => $request->country,
                
                );
        $id = DB::table('users')->where('id', $request->id)->update($in);
       
        if($id){
            return redirect('/my_account')->with([
                'msg'=>'User Updated successfully',
                'msg_type'=>'success',
            ]);
        }else{
            return redirect('/my_account')->with([
                'msg'=>'Please Try Again',
                'msg_type'=>'error',
            ]);
        }

    }
    
     public function forget_pass(Request $request)
    {
        if ($request->isMethod('post')) {
        $check_email=User::where('email',$request->email)->first();
        if(isset($check_email)){
            $user=User::find($check_email->id);
            $user->password = Str::random(10);
            $user->save();
            $lastid = $user->id;
            if($lastid){
                 $pass=User::where('id',$lastid)->first();
                $to_name = $check_email->name;
            $to_email = $check_email->email;
            $data = [
                    'name'=> $to_name,
                    'password' =>$pass->password ,
                    
                ];
            Mail::send('emails.password', $data, function($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)
            ->subject('Forget Password Email’');
            $message->from('order@pakteleshop.com','DealShop');
            });
            
            return redirect()->back()->with([
                'msg'=>'Your Password Is Sent To Your Email.',
                'msg_type'=>'success',
            ]);
        }
        }else{
            return redirect()->back()->with([
                'msg'=>'This Email Not Found',
                'msg_type'=>'success',
            ]);
        }
        }
        Session::put('title','Forget Password');
        return view('front.forget_pass');
    }
    
    
    public function subcribe_newsletter(Request $request)
    {
        $check_email=Newsletter::where('email',$request->email)->first();
        if(isset($check_email)){
            return redirect()->back()->with([
                'msg'=>'This Email Address already Exists',
                'msg_type'=>'error',
            ]);
        }

        $Newsletter=new Newsletter();
        $Newsletter->email=$request->email;
        $Newsletter->created_at=Date('Y-m-d h:i:s');
        $Newsletter->updated_at=Date('Y-m-d h:i:s');
        $Newsletter->save();
        return redirect()->back()->with([
            'msg'=>'Thanks for Subcribing Newsletter',
            'msg_type'=>'success',
        ]);
    }
    public function wishlist($id)
    {
        $cookie_name = "wishlist";
        $msg = '';
$list = array();
if(isset($_COOKIE[$cookie_name])) {
  $list = json_decode($_COOKIE[$cookie_name], true);
}
if (($key = array_search($id, $list)) !== false) {
    $msg = 'Item remove from wishlist successfully!';
    unset($list[$key]);
}
else
{
    $msg = 'Item add to  wishlist successfully!';
    $list[] = $id;
}
$list = json_encode($list);
setcookie($cookie_name, $list, time() + (86400 * 30), "/");
return redirect()->back()->with([
                'msg'=>$msg,
                'msg_type'=>'success',
            ]);
    }
    public function product_detail2(Request $request)
    {
         $url = url()->current();
         $parsedUrl = parse_url($url);
         $domain = $parsedUrl['host'];
         $path = ltrim($parsedUrl['path'], '/');
         $pathSegments = explode('/', $path);
         $slug = implode('/', $pathSegments);
        $allcatagories = Category::where(['status'=>1])->get();
        $Slider=Slider::all();
        $product = product::where(['slug'=>$slug ])->get();
        $rate = 0;
        $lrate = array();
        $r_name = '';
        $breed = array();
        
        $breed[] = array (
      '@type' => 'ListItem',
      'position' => 1,
      'name' => 'Home',
      'item' => url('/'),
    );
    $faq = array();
        if(count($product) > 0){
            
            $pro = $product[0];
            $cat= Category::where(['id'=>$pro->category_id])->first();
            $Galleries = Gallerie::where(['product_id'=>$pro->id])->get();
            if($cat)
        {
            $breed[] = array (
      '@type' => 'ListItem',
      'position' => 2,
      'name' => $cat->name,
      'item' => url('/category/').'/'.$pro->slug,
    );
        }
            $all_rat = Rating::where(['status'=>1,'pid'=>$product[0]->id])->get();
            $lrate = $rat = Rating::where(['status'=>1,'pid'=>$product[0]->id])->first();
            $count = 0;
            foreach($all_rat as $k=> $v)
            {
                $count++;
                $rate = $rate + $v->rate;
                
            }
            if($count)
            $rate = $rate/$count;
            if($rat){
                $r_name = $name =$rat->name ;
                $rateb =$rat->review ;
                $rate1 = $rat->rate;
                
            }else{
                $name ='' ;
                $rateb ='';
                $rate = 0;
            }
            $sett = Setting::where('id','1')->first();
            $meta = DB::table('products_to_meta')
                ->where('pid', '=', $product[0]->id)
                ->first();
                if($meta)
                {
                if( isset($meta->title) && !$meta->title)
                {
                    $meta->title = $pro->product_name;
                }
                }
                if ($meta) {
                $meta->url = url('/product/').'/'.$slug;
                            }
               
            $view = $product[0]->view;
            $category_id = $product[0]->category_id;
            $sub_cat_id = $product[0]->subcategory_id;
            $rproducts = product::where('category_id','=',$category_id)->where('id','!=',$product[0]->id)->limit('4')->get();
            $cate = Category::where(['id'=>$category_id])->first();
            $sub_cat = DB::table('sub_categories')->where('id', $sub_cat_id)->first();
            $rating = Rating::where(['status'=>1,'pid'=>$product[0]->id])->get();
            $brand = Brand::where(['id'=>$product[0]->brand])->first();
            $rcount = Rating::where(['status'=>1,'pid'=>$product[0]->id])->count();
            $faq = Schema::hasColumn('faq', 'pid')
                ? Faq::where(['status'=>1,'pid'=>$product[0]->id])->get()
                : (Schema::hasTable('pfaqs') ? DB::table('pfaqs')->where('product_id', $product[0]->id)->get() : collect());
            $fproduct=Product::find($product[0]->id);
            $fproduct->view = $view+1;
            $fproduct->save();
            $product_detail = 1;
            $meta_file  = 'meta.product';
            Session::put('title',$product[0]->product_name);
            $item  =  $product[0];
            return view('front.product_detail',compact('allcatagories','sett','product','rcount','category_id','rproducts','cate', 'sub_cat','meta','faq','rating','Slider','product_detail','meta_file','item','brand','cate'));
            
        }else{
            
            return abort(404);
        }
        
        

        return view('front.product_detail',compact('allcatagories','product','category_id','rproducts','meta','rating','Slider','cat')); 
    }
        public function product_detail($slug)
{
    \Log::info('Product Detail Called', ['slug' => $slug]);
    $allcatagories = Category::where(['status'=>1])->get();
    $Slider=Slider::all();
    $product = Product::where('status', 1)->where(function ($q) use ($slug) {
        $q->where('slug', $slug);
        if (Schema::hasColumn('products', 'shopify_handle')) {
            $q->orWhere('shopify_handle', $slug);
        }
    })->get();
    \Log::info('Product Found', ['count' => count($product)]);
    $rate = 0;
    $lrate = array();
    $r_name = '';
    $breed = array();

    $breed[] = array (
        '@type' => 'ListItem',
        'position' => 1,
        'name' => 'Home',
        'item' => url('/'),
    );
    $faq = array();

    if(count($product) > 0){
        $pro = $product[0];
        $cat= Category::where(['id'=>$pro->category_id])->first();
        $Galleries = Gallerie::where(['product_id'=>$pro->id])->get();

        if($cat) {
            $breed[] = array (
                '@type' => 'ListItem',
                'position' => 2,
                'name' => $cat->name,
                'item' => url('/category/').'/'.$pro->slug,
            );
        }

        $all_rat = Rating::where(['status'=>1,'pid'=>$product[0]->id])->get();
        $lrate = $rat = Rating::where(['status'=>1,'pid'=>$product[0]->id])->first();
        $count = 0;
        foreach($all_rat as $k=> $v) {
            $count++;
            $rate = $rate + $v->rate;
        }
        if($count)
            $rate = $rate/$count;

        if($rat){
            $r_name = $name =$rat->name ;
            $rateb =$rat->review ;
            $rate1 = $rat->rate;
        }else{
            $name ='' ;
            $rateb ='';
            $rate = 0;
        }

        $sett = Setting::where('id','1')->first();
        $meta = DB::table('products_to_meta')
            ->where('pid', '=', $product[0]->id)
            ->orderByDesc('id')
            ->first();

        if (!$meta) {
            $meta = (object) [
                'title' => 'Buy ' . $pro->product_name . ' | ' . env('WEB_NAME', 'Store'),
                'description' => strip_tags((string) ($pro->short_discriiption ?? '')),
                'keywords' => str_replace('-', ' ', (string) ($pro->tags ?? '')),
            ];
        } else {
            if (empty($meta->title)) {
                $meta->title = 'Buy ' . $pro->product_name . ' | ' . env('WEB_NAME', 'Store');
            }
            if (empty($meta->description)) {
                $meta->description = strip_tags((string) ($pro->short_discriiption ?? ''));
            }
            if (empty($meta->keywords)) {
                $meta->keywords = str_replace('-', ' ', (string) ($pro->tags ?? ''));
            }
        }
        $meta->url = product_url($pro);

        $view = $product[0]->view;
        $category_id = $product[0]->category_id;
        $sub_cat_id = $product[0]->subcategory_id;
        $rproducts = Product::where('category_id','=',$category_id)->where('id','!=',$product[0]->id)->limit('4')->get();
        $cate = Category::where(['id'=>$category_id])->first();
        $sub_cat = DB::table('sub_categories')->where('id', $sub_cat_id)->first();
        $rating = Rating::where(['status'=>1,'pid'=>$product[0]->id])->orderByDesc('id')->get();
        $brand = null;
        $brandName = null;
        $productBrand = $product[0]->brand ?? null;
        if (!empty($productBrand)) {
            if (is_numeric($productBrand)) {
                $brand = Brand::where('id', $productBrand)->first();
            }
            if (!$brand) {
                $brand = Brand::where('name', $productBrand)->orWhere('slug', $productBrand)->first();
            }
            $brandName = $brand->name ?? (is_numeric($productBrand) ? null : (string) $productBrand);
        }
        $rcount = Rating::where(['status'=>1,'pid'=>$product[0]->id])->count();
        $faq = Schema::hasColumn('faq', 'pid')
            ? Faq::where(['status'=>1,'pid'=>$product[0]->id])->get()
            : (Schema::hasTable('pfaqs') ? DB::table('pfaqs')->where('product_id', $product[0]->id)->get() : collect());
        $fproduct=Product::find($product[0]->id);
        $fproduct->view = $view+1;
        $fproduct->save();
        $product_detail = 1;
        $meta_file  = 'meta.product';
        Session::put('title',$product[0]->product_name);
        $item  =  $product[0];
        $fproducts=Product::select('products.*')->where('Featured','1')->orderBy('products.id','DESC')->limit(10)->get();

        $breadcrumbItems = [
            ['name' => 'Home', 'item' => url('/')],
        ];
        if ($cate) {
            $breadcrumbItems[] = ['name' => $cate->name, 'item' => url('/category/' . $cate->slug)];
        }
        if (!empty($sub_cat) && !empty($sub_cat->name)) {
            $breadcrumbItems[] = ['name' => $sub_cat->name, 'item' => url('/category/' . ($sub_cat->slug ?? ''))];
        }
        $breadcrumbItems[] = ['name' => $item->product_name, 'item' => $meta->url];
        $meta->scheme1 = seo_breadcrumb_schema($breadcrumbItems);
        $meta->scheme = seo_product_schema($item, $meta, $brandName, $rate, $rcount);

        return $this->view('product_detail', [
            'allcatagories' => $allcatagories,
            'sett' => $sett,
            'product' => $product,
            'rcount' => $rcount,
            'category_id' => $category_id,
            'rproducts' => $rproducts,
            'cate' => $cate,
            'fproducts' => $fproducts,
            'sub_cat' => $sub_cat,
            'meta' => $meta,
            'faq' => $faq,
            'rating' => $rating,
            'Slider' => $Slider,
            'product_detail' => $product_detail,
            'meta_file' => $meta_file,
            'item' => $item,
            'brand' => $brand,
            'brandName' => $brandName,
            'rate' => $rate,
            'cate' => $cate,
        ]);

    } else {
        return abort(404);
    }
}


    public function blog_detail($slug)
    {
        $blogs_detail = Blog_Post::where('slug', $slug)->first();
        if (!$blogs_detail) {
            abort(404);
        }
        $blog = collect([$blogs_detail]);
        $rblogQuery = Blog_Post::where('id', '!=', $blogs_detail->id);
        if (Schema::hasColumn('posts', 'category_id') && !empty($blogs_detail->category_id)) {
            $rblogQuery->where('category_id', $blogs_detail->category_id);
        }
        $rblog = $rblogQuery->orderByDesc('id')->limit(3)->get();
        $cum = collect();
        try {
            $cum = Blog_Comment::where('bid', $blogs_detail->id)->get();
        } catch (\Exception $e) {
            $cum = collect();
        }
        $bcate = Blog_Category::all();
        $meta_file = 'meta.blog_detail';
        Session::put('title', $blogs_detail->title ?? $blogs_detail->title_english ?? 'Blog Detail');
        $data = compact('blog', 'rblog', 'cum', 'bcate', 'blogs_detail', 'meta_file');
        try {
            return $this->view('blog_detail', $data);
        } catch (\InvalidArgumentException $e) {
            return view('front.blog_detail', $data);
        }
    }
    public function blod_comment(Request $request)
    {
        // dd($request);
            $rating=new Blog_Comment();

            $rating->bid=$request->bid;
            $rating->comment=$request->cum;
            $rating->name=$request->name;
            // $rating->email=$request->email;
            // $rating->review=$request->review;
            $rating->save();

            $product = Blog_Post::where(['id'=>$request->bid ])->get();
            $pro = $product[0];

            return redirect('/blog/'.$product[0]->slug)->with([
                'msg'=>'Comment submit successfully',
                'msg_type'=>'success',
            ]);
    }
    
    public function contact_us(Request $request)
    {
        // dd($request);
            $rating=new Contact();

            $rating->name=$request->name;
            $rating->email=$request->email;
            $rating->meg=$request->meg;
           
            $rating->save();

           

            return redirect('/contact')->with([
                'msg'=>'Message submited successfully',
                'msg_type'=>'success',
            ]);
    }
    
     public function my_wishlist(Request $request)
    {
        Session::put('title','My Wishlist');
        $page = "shop";
        $ids = array();
        if(isset($_COOKIE['wishlist']))
        {
            $ids = json_decode($_COOKIE['wishlist']);
        }
        if(!$ids)
        {
            return redirect()->back()->with([
                'msg'=>'You did not have anything in wishlist!',
                'msg_type'=>'danger',
            ]);
        }
        $best =Product::select('products.*')->whereIn('id', $ids)->get();
        //dd($best);
        return $this->view('my_wishlist',array('page'=>$page,'best'=>$best,'title'=>'My Wishlist'));
    }
     public function shop(Request $request)
    {
        Session::put('title','Shop');
        $page = "shop";
        $perPage = env('PRODUCTS_PER_PAGE', 12); // Default to 12, configurable via env
        $products =Product::select('products.*')->where('status','1')->orderBy('view','DESC')->paginate($perPage);
        return $this->view('list',array('page'=>$page,'products'=>$products,'title'=> 'Shop'));
    }
    
    public function loadMoreProducts(Request $request)
    {
        $page = $request->get('page', 1);
        $perPage = env('PRODUCTS_PER_PAGE', 12); // Default to 12, configurable via env
        $defaultTheme = 'theme2';
        try {
            $siteSetting = DB::table('setting')->where('id', 1)->first();
            if ($siteSetting && isset($siteSetting->active_theme)) {
                $defaultTheme = 'theme' . (int) $siteSetting->active_theme;
            }
        } catch (\Exception $e) {
            // keep default
        }
        $theme = $request->get('theme', $defaultTheme);
        $categoryId = $request->get('category_id', null);
        $subcategoryId = $request->get('subcategory_id', null);
        $brandId = $request->get('brand_id', null);
        $searchQuery = $request->get('search_query', null);
        $tagSlug = $request->get('tag_slug', null);
        
        // Extract theme number from theme string (e.g., 'theme2' -> 2, 'theme1' -> 1)
        $them_num = 2; // default
        if (preg_match('/theme(\d+)/', $theme, $matches)) {
            $them_num = (int)$matches[1];
        }
        
        $query = Product::select('products.*')->where('status','1');
        
        // If category_id is provided, filter by category
        if ($subcategoryId) {
            $query->where('subcategory_id', $subcategoryId);
        } elseif ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        
        // If brand_id is provided, filter by brand
        if ($brandId) {
            $query->where('brand', $brandId);
        }
        
        // If tag_slug is provided, filter by tags
        if ($tagSlug) {
            $tagSlugFormatted = $tagSlug;
            $tempQuery = clone $query;
            $tempQuery->where('tags', 'like', '%' . $tagSlugFormatted . '%');
            
            // If no results found, try with space-separated version
            if ($tempQuery->count() == 0) {
                $tagSlugFormatted = preg_replace("/-/", " ", $tagSlug);
            }
            $query->where('tags', 'like', '%' . $tagSlugFormatted . '%');
        }
        
        // If search_query is provided, check for category match first
        if ($searchQuery && !$categoryId) {
            // First, check if there's a category matching the search
            $matchedCategory = Category::where('status', 1)
                ->where(function($q) use ($searchQuery) {
                    $q->where('name', 'like', '%'.$searchQuery.'%')
                      ->orWhere('slug', 'like', '%'.$searchQuery.'%')
                      ->orWhere('keywords', 'like', '%'.$searchQuery.'%');
                })
                ->first();
            
            // If category found, filter by category instead of search
            if ($matchedCategory) {
                $query->where('category_id', $matchedCategory->id);
            } else {
                // If no category found, do normal product search
                $query->where(function($q) use ($searchQuery) {
                    $q->where('product_name', 'like', '%'.$searchQuery.'%')
                      ->orWhere('product_details', 'like', '%'.$searchQuery.'%')
                      ->orWhere('short_discriiption', 'like', '%'.$searchQuery.'%')
                      ->orWhere('tags', 'like', '%'.$searchQuery.'%')
                      ->orWhere('product_code', 'like', '%'.$searchQuery.'%')
                      ->orWhere('sku', 'like', '%'.$searchQuery.'%')
                      ->orWhereHas('brand', function($brandQuery) use ($searchQuery) {
                          $brandQuery->where('name', 'like', '%'.$searchQuery.'%')
                                    ->orWhere('keywords', 'like', '%'.$searchQuery.'%');
                      })
                      ->orWhereHas('category', function($catQuery) use ($searchQuery) {
                          $catQuery->where('name', 'like', '%'.$searchQuery.'%')
                                   ->orWhere('keywords', 'like', '%'.$searchQuery.'%')
                                   ->orWhere('description', 'like', '%'.$searchQuery.'%');
                      });
                });
            }
        } elseif ($searchQuery && $categoryId) {
            // If both search_query and category_id provided, do normal search within category
            $query->where(function($q) use ($searchQuery) {
                $q->where('product_name', 'like', '%'.$searchQuery.'%')
                  ->orWhere('product_details', 'like', '%'.$searchQuery.'%')
                  ->orWhere('short_discriiption', 'like', '%'.$searchQuery.'%')
                  ->orWhere('tags', 'like', '%'.$searchQuery.'%')
                  ->orWhere('product_code', 'like', '%'.$searchQuery.'%')
                  ->orWhere('sku', 'like', '%'.$searchQuery.'%');
            });
        }
        
        $products = $query->orderBy('view','DESC')
            ->paginate($perPage, ['*'], 'page', $page);
            
        // Generate HTML for products
        $html = '';
        foreach ($products as $product) {
            // Determine which product box template to use based on theme
            $template = 'theme1.product_box'; // Default
            if ($theme === 'theme2') {
                $template = 'theme2.product_box_new';
            } elseif ($theme === 'theme3') {
                $template = 'theme3.product_box_new';
            } elseif ($theme === 'theme4') {
                $template = 'theme4.product_box';
            } elseif ($theme === 'front') {
                $template = 'includes.parts.product_box';
            }
            
            $assets_url = rtrim(img_url(), '/') . '/public/theme'.$them_num.'/';
            
            $boxData = [
                'v' => $product,
                'k' => $product->id,
                'assets_url' => $assets_url,
            ];
            if ($theme === 'theme3') {
                $boxData['productColClass'] = 't3-list-product';
                $boxData['alwaysShowRating'] = true;
            }
            $boxHtml = view($template, $boxData)->render();
            if ($theme === 'theme4') {
                $html .= '<div class="col-xl-2 col-md-4">' . $boxHtml . '</div>';
            } else {
                $html .= $boxHtml;
            }
        }
        
        // Return JSON response for AJAX requests or if Accept header includes application/json
        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'html' => $html,
                'hasMore' => $products->hasMorePages(),
                'currentPage' => $products->currentPage(),
                'lastPage' => $products->lastPage()
            ]);
        }
        
        // For direct browser access, return JSON anyway (for testing/debugging)
        return response()->json([
            'html' => $html,
            'hasMore' => $products->hasMorePages(),
            'currentPage' => $products->currentPage(),
            'lastPage' => $products->lastPage()
        ]);
    }
    
    public function user_register(Request $request)
    {
        Session::put('title','User Register');
       
        return view('front.register');
    }
    
    public function scategory_detail($slug)
    {
        $Slider=Slider::all();
        $categories=Category::all();
        $best =Product::select('products.*')->orderBy('view','DESC')->limit(3)->get();

        $category_id = SubCategory::where(['slug'=>$slug ])->first();
        if($category_id)
        {
            $pcategory = Category::where(['id'=>$category_id->category_id ])->first();
            $cateid = $category_id->id;
            $sub_cat = $category_id;
            
            if(isset($category_id->name))
            {
                Session::put('title',$category_id->name);
            }

            $meta = null;
            
            $category = 1;
            $perPage = env('PRODUCTS_PER_PAGE', 12); // Default to 12, configurable via env
            $products=Product::where('status',1)->where(['subcategory_id'=>$cateid , 'status'=>1])->paginate($perPage);
            $seo = array();
            
            $meta_file = 'meta.categoy';
            $page = 'Test';
            return $this->view('list',array('page'=>$page,'products'=>$products,'title'=> 'Shop','meta_file'=>$meta_file,'meta'=>$meta,'category_id'=>$category_id,'best'=>$best,'seo'=>$seo,'category'=>$category,'sub_cat'=>$sub_cat,'pcategory'=>$pcategory));
        }
        else
        {
            return abort(404);
        }

       
    }
    
    public function category_detail($slug)
    {
        $Slider=Slider::all();
        $categories=Category::all();
        $best =Product::select('products.*')->orderBy('view','DESC')->limit(3)->get();
     
        $category_id = Category::where(['slug'=>$slug ])->first();
        
        // If category not found, check for subcategory
        if(!$category_id) {
            $subcategory_id = SubCategory::where('slug', $slug)->first();
            
            if($subcategory_id) {
                // Handle subcategory
                $cateid = $subcategory_id->id;
                $pcategory = Category::where(['id'=>$subcategory_id->category_id ])->first();
                $sub_cat = $subcategory_id;
                
                if(isset($subcategory_id->name))
                {
                    Session::put('title',$subcategory_id->name);
                }

                $meta = DB::table('categories_to_meta')
                    ->where('scid', '=', $subcategory_id->id)
                    ->first();
                if (!$meta) {
                    $meta = (object) [
                        'title' => $subcategory_id->name,
                        'description' => $subcategory_id->name,
                        'keywords' => $subcategory_id->name,
                    ];
                }
                $meta->url = url('/category/') . '/' . $slug;
                $meta->scheme = seo_collection_schema(
                    $meta->title ?? $subcategory_id->name,
                    $meta->description ?? $subcategory_id->name,
                    $meta->url
                );
                $meta->scheme1 = seo_breadcrumb_schema([
                    ['name' => 'Home', 'item' => url('/')],
                    ['name' => $pcategory->name ?? 'Category', 'item' => !empty($pcategory->slug) ? url('/category/' . $pcategory->slug) : url('/')],
                    ['name' => $subcategory_id->name, 'item' => $meta->url],
                ]);
                
                $category = 1;
                $perPage = env('PRODUCTS_PER_PAGE', 12); // Default to 12, configurable via env
                $products=Product::where('status',1)->where(['subcategory_id'=>$cateid , 'status'=>1])->paginate($perPage);
                $seo = CategoriesToMeta::where('scid','=',$cateid)->first();
                
                $meta_file = 'meta.categoy';
                $page = 'Test';
                return $this->view('list',array('page'=>$page,'products'=>$products,'title'=> 'Shop','meta_file'=>$meta_file,'meta'=>$meta,'category_id'=>$subcategory_id,'best'=>$best,'seo'=>$seo,'category'=>$category,'sub_cat'=>$sub_cat,'pcategory'=>$pcategory));
            } else {
                // Neither category nor subcategory found
                return abort(404);
            }
        }
        
        // Handle category (existing logic)
        $cateid = $category_id->id;
        $sub_cat = DB::table('sub_categories')->where('category_id', $cateid)->first();
       
        if(isset($category_id->name))
        {
            Session::put('title',$category_id->name);
        }

        $meta = DB::table('categories_to_meta')
            ->where('cid', '=', $category_id->id)
            ->first();
        if (!$meta) {
            $meta = (object) [
                'title' => $category_id->name,
                'description' => $category_id->name,
                'keywords' => $category_id->name,
            ];
        }
        $meta->url = url('/category/') . '/' . $slug;
        $meta->scheme = seo_collection_schema(
            $meta->title ?? $category_id->name,
            $meta->description ?? $category_id->name,
            $meta->url
        );
        $meta->scheme1 = seo_breadcrumb_schema([
            ['name' => 'Home', 'item' => url('/')],
            ['name' => $category_id->name, 'item' => $meta->url],
        ]);
        $category = 1;
        $perPage = env('PRODUCTS_PER_PAGE', 12); // Default to 12, configurable via env
        $products=Product::where('status',1)->where(['category_id'=>$cateid , 'status'=>1])->paginate($perPage);
        $seo = CategoriesToMeta::where('cid','=',$cateid)->first();
       
        $meta_file = 'meta.categoy';
        $page = 'Test';
        return $this->view('list',array('page'=>$page,'products'=>$products,'title'=> 'Shop','meta_file'=>$meta_file,'meta'=>$meta,'category_id'=>$category_id,'best'=>$best,'seo'=>$seo,'category'=>$category,'sub_cat'=>$sub_cat));

       
    }
    
     public function blog_category($id)
    {
        
        $cate=Blog_Category::all();
        $meta = Blog_Category::where(['slug'=>$id])->first();
        $category_id = Blog_Category::where(['slug'=>$id ])->first();
        $cateid = $category_id->id;
         if(isset($category_id->title_english))
        {
         Session::put('title',$category_id->title_english);
        }

        
        
         $post=Blog_Post::where(['category_id'=>$cateid ])->get();
       
       
        
        return view('front.blogs',compact('post','category_id','meta','cate'));

       
    }
     public function blogs()
    {
        $slug = request()->segment(count(request()->segments()));
        $meta_file = 'meta.page';
        $pages = Pages::where(['slug' => $slug])->get();
        Session::put('title', 'Blogs');
        $postQuery = Blog_Post::query();
        if (Schema::hasColumn('posts', 'status')) {
            $postQuery->where(function ($q) {
                $q->where('status', 1)->orWhereNull('status');
            });
        }
        $post = $postQuery->orderByDesc('id')->get();
        $cate = Blog_Category::limit(6)->get();
        $data = compact('post', 'cate', 'meta_file', 'pages');
        try {
            return $this->view('blog', $data);
        } catch (\InvalidArgumentException $e) {
            return view('front.blog', $data);
        }
    }
    
    public function cart()
    {
        Session::put('title', 'Cart');

        $setting = DB::table('setting')->where('id', 1)->first();
        $cartProducts = Cart::products();
        $cartProductIds = collect($cartProducts)->pluck('id')->all();

        $recommendedProducts = Product::where('status', 1)
            ->when(count($cartProductIds) > 0, function ($query) use ($cartProductIds) {
                $query->whereNotIn('id', $cartProductIds);
            })
            ->orderByDesc('view')
            ->limit(12)
            ->get();

        return $this->view('cart', compact('setting', 'cartProducts', 'recommendedProducts'));
    }
    
    public function contact()
    {
        Session::put('title','Contact Us');
        return $this->view('contact');
    }
    
    public function track_order(Request $request)
    {   
         $product = Order::where(['order_no'=>$request->num ])->get();
            $count = Order::where(['order_no'=>$request->num])->count();
            
        Session::put('title','Track Order');
        return view('front.track_order',compact('product','count'));
    }
    
    
    public function checkout()
    {
         // Check if cart is empty; if yes, redirect to home with error message
         if (!Cart::products() || count(Cart::products()) == 0) {
             return redirect('/cart')->withErrors(['msg' => 'Your cart is empty. Add items before checkout.']);
         }

         $payment_methods = Schema::hasTable('payment_methods')
             ? PaymentMethod::orderBy('sort', 'ASC')->orderBy('id', 'ASC')->get()
             : collect();
       
         if(Session::get('user')){
              $uid = Session::get('user')['id'];
        $user = User::where(['id'=>$uid ])->get();
             Session::put('title','Order Checkout');
        return $this->view('checkout', ['user' => $user, 'payment_methods' => $payment_methods]);
         }else{
        
        Session::put('title','Checkout');
        return $this->view('checkout', ['payment_methods' => $payment_methods]);
         }
    }
     public function guest_checkout()
    {
        
             Session::put('title','Order Checkout');
        return view('front.order');
        
    }
    
    public function my_account()
    {
        $uid = Session::get('user')['id'];
        $user = User::where(['id'=>$uid ])->get();
        $order = Order::where(['uid'=>$uid ])->get();
        Session::put('title','My Account');
        return view('front.myaccount',compact('user','order'));
    }

    public function order(Request $slug)
    {

       $id = $slug->product;


       $product = product::where(['id'=>$id , 'status'=>1])->get();

        return view('front.orderpage',compact('product'));
    }

    public function find($slug)
    {
        // Exclude admin routes
        if(strpos($slug, 'admin') === 0) {
            return abort(404);
        }
        
        $pages = Pages::where(['slug'=>$slug, 'status'=>1])->first();
        $product = DB::table('products')->where('slug', $slug)->where('status', 1)->first();
        $cat = DB::table('categories')->where('slug', $slug)->where('status', 1)->first();
        $scat = DB::table('sub_categories')->where('slug', $slug)->first();
        if(isset($product->slug) && $product->slug == $slug)
        { 
            return $this->product_detail($slug);
        }
        if(isset($cat->slug) && $cat->slug == $slug)
        {
            
            return $this->category_detail($slug);
        }
        if(isset($scat->slug) && $scat->slug == $slug)
        {
            return $this->scategory_detail($slug);
        }
        if(isset($pages->slug) && $pages->slug == $slug)
        {
            return $this->page_detail($slug);
        }
        return abort(404);
    }
    public function page_detail($slug)
    {
        $Slider=Slider::all();
        $size = Size::all();
        $colors = Colors::all();
        $shap = Shap::all();
        $meta_file  = 'meta.page';
       $pages = Pages::where(['slug'=>$slug, 'status'=>1])->get();
      
        $title = ''; 
        
        if(isset($pages[0]->name))
        {
            $title = $pages[0]->name;
         Session::put('title',$pages[0]->name);
        }
        if($title)
        {
        return $this->view('dynamicpage',array('title'=>$title,'pages'=> $pages,'slug'=>$slug,'Slider'=> $Slider,'meta_file'=>$meta_file,'size'=> $size,'colors'=>$colors,'shap'=> $shap));
        }
        else
        {
            return abort(404);
        }
    }
    
    public function about(Request $request)
    {
        $products=Product::select('products.*')->orderBy('products.id','DESC')->get();
        $categories=Category::all();
        $Slider=Slider::all();
        $size = Size::all();
        $colors = Colors::all();
        $shap = Shap::all();
        $meta = '';
        Session::put('title','About us');
        return $this->view('about',compact('Slider','meta','size','colors','shap','products','categories'));
    }

    public function shipping()
    {
        return $this->page_detail('shipping');
    }
    public function learn(Request $request)
    {
        $setting=Learn_setting::all();
        Session::put('title','Learn');
        return view('front.new',compact('setting'));
    }
    
     public function faq(Request $request)
    {
        $page = Pages::where('slug', 'faqs')->where('status', 1)->first();
        if ($page) {
            return $this->page_detail('faqs');
        }

        $faq = DB::table('faqs')->get();
        Session::put('title', 'FAQ');
        return view('front.faq', compact('faq'));
    }
    // public function order(Request $request)
    // {
             
    //         $setting=product::where(['id'=>$request->pid ])->get();
    //         Session::put('title','Checkout');
    //         return view('front.checkout',compact('setting'));
    // }
private function sendEmailWithSendGrid($toEmail, $toName, $subject, $content) {
    $apiKey = env('SENDGRID_API_KEY', '');

    $emailData = [
        'personalizations' => [
            [
                'to' => [
                    ['email' => $toEmail, 'name' => $toName]
                ],
                'subject' => $subject
            ]
        ],
        'from' => [
            'email' => 'info@quicknow.pk',
            'name' => 'Quicknow'
        ],
        'content' => [
            [
                'type' => 'text/html',
                'value' => $content
            ]
        ]
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.sendgrid.com/v3/mail/send');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $apiKey,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($emailData));

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($httpCode == 202) {
        return true;
        
    } else {
        return $response;
    }
}

    private function send_grid($to, $subj, $html)
{
    return $this->sendEmailWithSendGrid($to, ' ', $subj, $html);
    $arrayEmails = [$to];
    $emailSubject = $subj;

    Mail::send([], [], function ($message) use ($arrayEmails, $emailSubject, $html) {
        $message->to($arrayEmails)
                ->subject($emailSubject)
                ->setBody($html, 'text/html'); // HTML content set karne ke liye
    });

    return true;
}

    protected function resolvePaymentMethodTitle(Request $request): string
    {
        if ($request->payment_method_id && Schema::hasTable('payment_methods')) {
            $method = PaymentMethod::find($request->payment_method_id);
            if ($method) {
                return $method->title;
            }
        }

        return 'Cash on Delivery';
    }

    protected function dispatchPurchaseEvents($orderId, Request $request): void
    {
        try {
            $store = \App\Support\CurrentStore::get();
            if (!$store || !$orderId) {
                return;
            }
            $amount = (float) (optional(\App\Models\Order::withoutGlobalScope('store')->find($orderId))->amount ?? 0);
            $user = [
                'email' => $request->email,
                'phone' => $request->phone,
                'ip' => $request->ip(),
                'ua' => $request->userAgent(),
            ];
            (new \App\Services\Integrations\MetaEventsService())->send($store, 'Purchase', [
                'value' => $amount,
                'currency' => 'PKR',
                'content_ids' => [(string) $orderId],
                'event_source_url' => url('/thankyou/' . $orderId),
            ], $user);
            (new \App\Services\Integrations\TikTokEventsService())->send($store, 'CompletePayment', [
                'value' => $amount,
                'currency' => 'PKR',
                'url' => url('/thankyou/' . $orderId),
            ], $user);
        } catch (\Throwable $e) {
            \Log::warning('Purchase event dispatch failed: ' . $e->getMessage());
        }
    }

    public function order_submit(Request $request)
    {
        $paymentMethodTitle = $this->resolvePaymentMethodTitle($request);

        if(Session::get('user')){
            $Order=new Order();
            foreach(Cart::products() as $product){
              $Order->product_detail=json_encode(Session::get('cart')['items']);  
            
            $Order->uid=Session::get('user')['id'];
            $Order->order_no = rand(10,100000);
            $Order->customer_name=$request->name;
            $Order->email=$request->email;
            $Order->phone=$request->phone;
            $Order->country=$request->country;
            $Order->address=$request->address;
            $Order->city=$request->city;
            $Order->payment_method = $paymentMethodTitle;
            
            $Order->amount=Session::get('cart')['amount'];
            $Order->save();
            $lastid=$Order->id; 
            $productt = Product::find($product->id);
             $productt->product_quantity=$productt->product_quantity-$product['qty'];
             $productt->update();
            }
            if(isset($lastid))
            $order_data = Order::where('id',$lastid)->get();
            if($lastid){
                $to_name = $request->name;
            $to_email = $request->email;
            $data = [
                    'order' =>$order_data ,
                    
                ];
                $html = view('emails.order', $data)->render();
                $r = $this->send_grid($to_email,'Order Email', $html);
                $setting = DB::table('setting')
    ->where('id', '=', '1')
    ->first();
            $to = $setting->email;
            $this->send_grid($to,'Order Email', $html);
            }
            Session::forget('cart');
            $this->dispatchPurchaseEvents($lastid ?? null, $request);
            return redirect('/thankyou/'.$lastid)->with([
                'msg'=>'Order submit successfully',
                'msg_type'=>'success',
            ]);
        }else{
            
            $Order=new Order();
            foreach(Cart::products() as $product){
                
                $Order->product_detail=json_encode(Session::get('cart')['items']);  
           
            $Order->uid= rand(10,100000);
            $Order->order_no = rand(10,100000);
            $Order->customer_name=$request->name;
            $Order->email=$request->email;
            $Order->phone=$request->phone;
            $Order->country=$request->country;
            $Order->address=$request->address;
            $Order->city=$request->city;
            $Order->payment_method = $paymentMethodTitle;
           
            $Order->amount=Session::get('cart')['amount'];
            
            $Order->save();
            $lastid=$Order->id; 
            
            $productt = Product::find($product->id);
             $productt->product_quantity=$productt->product_quantity-$product->qty;
             $productt->update();
             
            }
            if(isset($lastid))
            $order_data = Order::where('id',$lastid)->get();
            if($lastid){
                $to_name = $request->name;
            $to_email = $request->email;
            $data = [
                    'order' =>$order_data ,
                    
                ];
                
                
                $html = view('emails.order', $data)->render();
                $this->send_grid($to_email,'Order Email', $html);
                $setting = DB::table('setting')
    ->where('id', '=', '1')
    ->first();
            $to = $setting->email;
            $r = $this->send_grid($to,'Order Email', $html);
            }
        
            Session::forget('cart');
            $this->dispatchPurchaseEvents($lastid ?? null, $request);
            return redirect('/thankyou/'.$lastid)->with([
                'msg'=>'Order submit successfully',
                'msg_type'=>'success',
            ]);
        }
    }
    
    public function thankyou($id)
    {
        $order = Order::where('id', $id)->get();
        if($order->isEmpty()) {
            return redirect('/')->with([
                'msg'=>'Order not found',
                'msg_type'=>'error',
            ]);
        }
        return $this->view('thankyou',['order' => $order]);
    }
    
    public function instant_order(Request $request)
    {
        // dd($request);
            $Order=new Order();
            $array = array(
                'id'=>$request->id,
                'qty'=>$request->qty
                );
                $pro[]=$array;
            $Order->product_detail=json_encode($pro);  
            $Order->uid= rand(10,100000);
            $Order->order_no = rand(10,100000);
            $Order->customer_name=$request->name;
            $Order->email=$request->email;
            $Order->phone=$request->phone;
            $Order->country=$request->country;
            $Order->address=$request->address;
            $Order->city=$request->city;
           
            $Order->amount=Session::get('cart')['amount'];
            $Order->save();
            $lastid=$Order->id;
            $order_data = Order::where('id',$lastid)->get();
            if($lastid){
                $to_name = $request->name;
            $to_email = $request->email;
            $data = [
                    'order' =>$order_data ,
                    
                ];
            Mail::send('emails.order', $data, function($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)
            ->subject('Order Email’');
            $message->from('order@pakteleshop.com','DealShop');
            });
            $to = 'order@pakteleshop.com';
            $to_n = 'Admin';
            Mail::send('emails.admin_order', $data, function($message) use ($to_n, $to) {
            $message->to($to, $to_n)
            ->subject('Order Email’');
            $message->from('order@pakteleshop.com','DealShop');
            });
            }
             $productt = Product::find($request->id);
             $productt->product_quantity=$productt->product_quantity-$request->qty;
             $productt->update();

            return redirect('/product/'.$productt->slug)->with([
                'msg'=>'Order submit successfully',
                'msg_type'=>'success',
            ]);
    }

    public function rating_submit(Request $request)
    {
        $request->validate([
            'pid' => 'required|integer',
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:191',
            'review' => 'required|string|max:2000',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $product = Product::where('id', $request->pid)->where('status', 1)->first();
        if (!$product) {
            return back()->with(['message' => 'Product not found.', 'msg_type' => 'error']);
        }

        $rating = new Rating();
        $rating->pid = (int) $product->id;
        $rating->rate = (int) $request->rating;
        $rating->name = $request->name;
        $rating->email = $request->email;
        $rating->review = $request->review;
        $rating->status = 0;
        $rating->is_read = 0;
        if (Schema::hasColumn('rating', 'store_id')) {
            $rating->store_id = (int) ($product->store_id ?: (\App\Support\CurrentStore::id() ?: 0));
        }
        if ($request->hasFile('reviewImage') && Schema::hasColumn('rating', 'image')) {
            $file = $request->file('reviewImage');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/images/reviews/'), $filename);
            $rating->image = 'public/images/reviews/' . $filename;
        }
        $rating->save();

        return redirect('/product/' . $product->slug)->with([
            'message' => 'Thanks! Your review is waiting for admin approval.',
            'msg_type' => 'success',
        ]);
    }
    public function faq_submit(Request $request)
    {
        // dd($request);
            $rating=new Faq();

            $rating->pid=$request->pid;
            $rating->question=$request->question;
            $rating->name=$request->name;
            $rating->save();
            if($rating->save()){
            $product = product::where(['id'=>$request->pid , 'status'=>1])->get();
            $pro = $product[0];

            return redirect('/product/'.$product[0]->slug)->with([
                'message'=>'Question submit successfully',
                'msg_type'=>'success',
            ]);
            }else{
                $product = product::where(['id'=>$request->pid , 'status'=>1])->get();
            $pro = $product[0];

            return redirect('/product/'.$product[0]->slug)->with([
                'message'=>'Please try Again!',
                'msg_type'=>'success',
            ]);
            }
    }
    
    public function trackorder(Request $request)
    {
        
            $product = Order::where(['order_no'=>$request->num])->get();
            $count = Order::where(['order_no'=>$request->num])->count();
            Session::put('title','Track Order');
            return view('front.track_order',compact('product','count'));
    }

    public function get_selected_shap(Request $request)
    {
            $data=$request->data;
            $old = $request->session()->get('selected_shap');
            if(!$old)
            {
                $old = array();
            }
            if($request->action == "add"){

                // $request->session()->forget('selected_shap');
                if(isset($old)){
                    if(count($old) != 0){
                        if (in_array($data, $old))
                        {

                        }
                        else
                        {
                            array_push($old,$data);
                            $request->session()->put('selected_shap',$old);
                        }

                    }else{
                        $array = array();
                        array_push($array,$data);
                        $request->session()->put('selected_shap',$array);
                    }
                }
                else{
                    $array = array();
                    array_push($array,$data);
                    $request->session()->put('selected_shap',$array);
                    // print_r($old);
                }

            }else{

                foreach (array_keys($old, $data, true) as $key) {
                    unset($old[$key]);
                }

                $request->session()->forget('selected_shap');
                $request->session()->put('selected_shap',$old);

            }
            $old = array(1);
            
            $selected_shap = $request->session()->get('selected_shap');
            $selected_color = $request->session()->get('selected_color');
            $selected_size = $request->session()->get('selected_size');
            
            $newto = $request->session()->get('to');
            $datafrom = $request->session()->get('from');
            
            $Products = Product::orderBy('id','DESC');
            if($selected_color == ''){
                $selected_color = array();
            }
            if($selected_shap == ''){
                $selected_shap = array();
            }
            if($selected_size == ''){
                $selected_size = array();
            }
            
            if(count($selected_shap) > 0 && count($selected_color) > 0 && count($selected_size) > 0){
                if($newto != '' && $datafrom != ''){
                    $Products = DB::select("SELECT * FROM `products` WHERE product_color IN (".implode(",",$selected_color).") AND product_shap IN (".implode(",",$selected_shap).") AND product_size IN (".implode(",",$selected_size).") AND discount_price BETWEEN ".$newto." AND ".$datafrom." ORDER BY id DESC ");
                }else{
                    $Products = DB::select("SELECT * FROM `products` WHERE product_color IN (".implode(",",$selected_color).") AND product_shap IN (".implode(",",$selected_shap).") AND product_size IN (".implode(",",$selected_size).") ORDER BY id DESC ");
                }
                // return "SELECT * FROM `products` WHERE product_color IN (".implode(",",$selected_color).") AND product_shap IN (".implode(",",$selected_shap).") AND product_size IN (".implode(",",$selected_size).") ORDER BY id DESC ";
                
            }elseif(count($selected_shap) == 0 && count($selected_color) == 0 && count($selected_size) == 0){
                if($newto != '' && $datafrom != ''){
                    $Products = DB::select("SELECT * FROM `products` WHERE discount_price BETWEEN ".$newto." AND ".$datafrom." ORDER BY id DESC ");
                }else{
                    $Products = DB::select("SELECT * FROM `products` ORDER BY id DESC ");
                }
                
            }else{
                
                if(count($selected_shap) == 0){
                    
                    if(count($selected_color) == 0 && count($selected_size) > 0){
                        if($newto != '' && $datafrom != ''){
                            $Products = DB::select("SELECT * FROM `products` WHERE product_size IN (".implode(",",$selected_size).") AND discount_price BETWEEN ".$newto." AND ".$datafrom." ORDER BY id DESC ");
                        }else{
                            $Products = DB::select("SELECT * FROM `products` WHERE product_size IN (".implode(",",$selected_size).") ORDER BY id DESC ");
                        }
                        
                    }elseif(count($selected_color) > 0 && count($selected_size) == 0){
                        if($newto != '' && $datafrom != ''){
                            $Products = DB::select("SELECT * FROM `products` WHERE product_color IN (".implode(",",$selected_color).") AND discount_price BETWEEN ".$newto." AND ".$datafrom." ORDER BY id DESC ");
                        }else{
                            $Products = DB::select("SELECT * FROM `products` WHERE product_color IN (".implode(",",$selected_color).") ORDER BY id DESC ");
                        }
                        
                    }else{
                        if($newto != '' && $datafrom != ''){
                            $Products = DB::select("SELECT * FROM `products` WHERE product_color IN (".implode(",",$selected_color).") and product_size IN (".implode(",",$selected_size).") AND discount_price BETWEEN ".$newto." AND ".$datafrom." ORDER BY id DESC ");
                        }else{
                            $Products = DB::select("SELECT * FROM `products` WHERE product_color IN (".implode(",",$selected_color).") and product_size IN (".implode(",",$selected_size).") ORDER BY id DESC ");
                        }
                        
                    }
                    
                }elseif(count($selected_color) == 0){
                    
                    if(count($selected_shap) > 0 && count($selected_size) > 0){
                        if($newto != '' && $datafrom != ''){
                            $Products = DB::select("SELECT * FROM `products` WHERE product_size IN (".implode(",",$selected_size).") and product_shap IN (".implode(",",$selected_shap).") AND discount_price BETWEEN ".$newto." AND ".$datafrom." ORDER BY id DESC ");
                        }else{
                            $Products = DB::select("SELECT * FROM `products` WHERE product_size IN (".implode(",",$selected_size).") and product_shap IN (".implode(",",$selected_shap).") ORDER BY id DESC ");
                        }
                    }elseif(count($selected_size) == 0 && count($selected_shap) > 0){
                        if($newto != '' && $datafrom != ''){
                            $Products = DB::select("SELECT * FROM `products` WHERE product_shap IN (".implode(",",$selected_shap).") AND discount_price BETWEEN ".$newto." AND ".$datafrom." ORDER BY id DESC ");
                        }else{
                            $Products = DB::select("SELECT * FROM `products` WHERE product_shap IN (".implode(",",$selected_shap).") ORDER BY id DESC ");
                        }
                    }elseif(count($selected_shap) == 0 && count($selected_size) > 1){
                        if($newto != '' && $datafrom != ''){
                            $Products = DB::select("SELECT * FROM `products` WHERE product_size IN (".implode(",",$selected_size).") AND discount_price BETWEEN ".$newto." AND ".$datafrom." ORDER BY id DESC ");
                        }else{
                            $Products = DB::select("SELECT * FROM `products` WHERE product_size IN (".implode(",",$selected_size).") ORDER BY id DESC ");
                        }
                    }
                    
                }elseif(count($selected_size) == 0){
                    
                    if(count($selected_shap) > 0 && count($selected_color) > 0){
                        if($newto != '' && $datafrom != ''){
                            $Products = DB::select("SELECT * FROM `products` WHERE product_color IN (".implode(",",$selected_color).") and product_shap IN (".implode(",",$selected_shap).") AND discount_price BETWEEN ".$newto." AND ".$datafrom." ORDER BY id DESC ");
                        }else{
                            $Products = DB::select("SELECT * FROM `products` WHERE product_color IN (".implode(",",$selected_color).") and product_shap IN (".implode(",",$selected_shap).") ORDER BY id DESC ");
                        }
                    }elseif(count($selected_color) == 0 && count($selected_shap) > 0){
                        if($newto != '' && $datafrom != ''){
                            $Products = Dload-more-productsB::select("SELECT * FROM `products` WHERE product_shap IN (".implode(",",$selected_shap).") AND discount_price BETWEEN ".$newto." AND ".$datafrom." ORDER BY id DESC ");
                        }else{
                            $Products = DB::select("SELECT * FROM `products` WHERE product_shap IN (".implode(",",$selected_shap).") ORDER BY id DESC ");
                        }
                    }elseif(count($selected_shap) == 0 && count($selected_color) > 1){
                        if($newto != '' && $datafrom != ''){
                            $Products = DB::select("SELECT * FROM `products` WHERE product_color IN (".implode(",",$selected_color).") AND discount_price BETWEEN ".$newto." AND ".$datafrom." ORDER BY id DESC ");
                        }else{
                            $Products = DB::select("SELECT * FROM `products` WHERE product_color IN (".implode(",",$selected_color).") ORDER BY id DESC ");
                        }
                    }
                }
                
            }
            
            $html = '';
            foreach ($Products as $key => $product) {
                // dd($product->id);
                $Galleries = Gallerie::where(['product_id'=>$product->id])->get();
                
                $Catagories = Category::where(['id'=>$product->category_id])->get();
                
                if(count($Catagories) > 0){
                    $cateslug = $Catagories[0]->slug;
                    $catename = $Catagories[0]->name;
                }else{
                    $cateslug = '';
                    $catename = '';
                }
                if(count($Galleries) > 0 ){
                    $gallary = $Galleries[0]->photo;
                }else{
                    $gallary = '';
                }
                $html .= '<div class="c-product-grid_item"><div class="c-product-grid_thumb-wrap"><a href="product/'.$product->slug.'" class="lrv-loop-product_link"><img data-src="'.$product->image_one.'" src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1 1\'%3E%3C/svg%3E" class="c-product-grid_thumb lazyload lazy-placeholder" loading="lazy"><img data-src="'.$gallary.'" src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1 1\'%3E%3C/svg%3E" class="c-product-grid_thumb product_grid_thumb_hover lazyload lazy-placeholder" loading="lazy"></a><div class="c-product-grid_badges"></div><div class="c-product-grid_thumb-button-list"><button class="h-cb c-product-grid_thumb-button"><i class="ip-eye c-product-grid_icon"></i> <span class="c-product-grid_icon-text">Quick view</span></button> <button data-size="" class="js-wishlist-btn"><i class="ip-heart c-product-grid_icon"></i></button></div></div><a href="product/'.$product->slug.'" class="c-product-grid_atc"><span class="c-product-grid_atc-text">View Product</span></a><div class="c-product-grid_details"><div class="c-product-grid_title-wrap"><div class="c-product-grid_category-list"><a class="c-product-grid_category-item" href="/category/'.$cateslug.'">'.$catename.'</a></div><a href="product/'.$product->slug.'" class="product_link_link"><h2 class="lrv-loop-product_title">'.$product->product_name.'</h2></a></div><div class="c-product-grid_price-wrap"><span class="price"><span class="lrv-Price-amount amount"><bdi><span class="lrv-Price-currencySymbol">$</span>'.$product->discount_price.'</bdi></span></span></div></div></div>';
                
            }
            
            if($html == ''){
                $html = '<span style="width: 100%;text-align: center;margin: 185px;">No Record Found!</span>';
            }else{
                $html = $html;
            }
            
            return $html;
            
            
    }

    public function get_selected_color(Request $request)
    {
        // dd($request->session()->get('selected_color'));
            $data=$request->data;
            $old = $request->session()->get('selected_color');
            if(!$old)
            {
                $old = array();
            }
            // dd($data);
            if($request->action == "add"){

                // $request->session()->forget('selected_color');
                if(isset($old)){
                    if(count($old) != 0){
                        if (in_array($data, $old))
                        {

                        }
                        else
                        {
                            array_push($old,$data);
                            $request->session()->put('selected_color',$old);
                        }

                    }else{
                        $array = array();
                        array_push($array,$data);
                        $request->session()->put('selected_color',$array);
                    }
                }
                else{
                    $array = array();
                    array_push($array,$data); 
                    $request->session()->put('selected_color',$array);
                    // print_r($old);
                }

            }else{

                foreach (array_keys($old, $data, true) as $key) {
                    unset($old[$key]);
                }

                $request->session()->forget('selected_color');
                $request->session()->put('selected_color',$old);

            }
            
            
            $old = array(1);
            
            $selected_shap = $request->session()->get('selected_shap');
            $selected_color = $request->session()->get('selected_color');
            $selected_size = $request->session()->get('selected_size');
            
            $newto = $request->session()->get('to');
            $datafrom = $request->session()->get('from');
            
            $Products = Product::orderBy('id','DESC');
            // // return $selected_color;
            if($selected_color == ''){
                $selected_color = array();
            }
            if($selected_shap == ''){
                $selected_shap = array();
            }
            if($selected_size == ''){
                $selected_size = array();
            }
            
            if(count($selected_shap) > 0 && count($selected_color) > 0 && count($selected_size) > 0){
                if($newto != '' && $datafrom != ''){
                    $Products = DB::select("SELECT * FROM `products` WHERE product_color IN (".implode(",",$selected_color).") AND product_shap IN (".implode(",",$selected_shap).") AND product_size IN (".implode(",",$selected_size).") AND discount_price BETWEEN ".$newto." AND ".$datafrom." ORDER BY id DESC ");
                }else{
                    $Products = DB::select("SELECT * FROM `products` WHERE product_color IN (".implode(",",$selected_color).") AND product_shap IN (".implode(",",$selected_shap).") AND product_size IN (".implode(",",$selected_size).") ORDER BY id DESC ");
                }
                
            }elseif(count($selected_shap) == 0 && count($selected_color) == 0 && count($selected_size) == 0){
                if($newto != '' && $datafrom != ''){
                    $Products = DB::select("SELECT * FROM `products` WHERE discount_price BETWEEN ".$newto." AND ".$datafrom." ORDER BY id DESC ");
                }else{
                    $Products = DB::select("SELECT * FROM `products` ORDER BY id DESC ");
                }
            }else{
                
                if(count($selected_shap) == 0){
                    
                    if(count($selected_color) == 0 && count($selected_size) > 0){
                        if($newto != '' && $datafrom != ''){
                            $Products = DB::select("SELECT * FROM `products` WHERE product_size IN (".implode(",",$selected_size).") AND discount_price BETWEEN ".$newto." AND ".$datafrom." ORDER BY id DESC ");
                        }else{
                            $Products = DB::select("SELECT * FROM `products` WHERE product_size IN (".implode(",",$selected_size).") ORDER BY id DESC ");
                        }
                        
                    }elseif(count($selected_color) > 0 && count($selected_size) == 0){
                        // return "SELECT * FROM `products` WHERE product_color IN (".implode(",",$selected_color).") ORDER BY id DESC ";
                        if($newto != '' && $datafrom != ''){
                            $Products = DB::select("SELECT * FROM `products` WHERE product_color IN (".implode(",",$selected_color).") AND discount_price BETWEEN ".$newto." AND ".$datafrom." ORDER BY id DESC ");
                        }else{
                            $Products = DB::select("SELECT * FROM `products` WHERE product_color IN (".implode(",",$selected_color).") ORDER BY id DESC ");
                        }
                        
                    }else{
                        if($newto != '' && $datafrom != ''){
                            $Products = DB::select("SELECT * FROM `products` WHERE product_color IN (".implode(",",$selected_color).") and product_size IN (".implode(",",$selected_size).") AND discount_price BETWEEN ".$newto." AND ".$datafrom." ORDER BY id DESC ");
                        }else{
                            $Products = DB::select("SELECT * FROM `products` WHERE product_color IN (".implode(",",$selected_color).") and product_size IN (".implode(",",$selected_size).") ORDER BY id DESC ");
                        }
                    }
                    
                }elseif(count($selected_color) == 0){
                    
                    if(count($selected_shap) > 0 && count($selected_size) > 0){
                        if($newto != '' && $datafrom != ''){
                            $Products = DB::select("SELECT * FROM `products` WHERE product_size IN (".implode(",",$selected_size).") and product_shap IN (".implode(",",$selected_shap).") AND discount_price BETWEEN ".$newto." AND ".$datafrom." ORDER BY id DESC ");
                        }else{
                            $Products = DB::select("SELECT * FROM `products` WHERE product_size IN (".implode(",",$selected_size).") and product_shap IN (".implode(",",$selected_shap).") ORDER BY id DESC ");
                        }
                        
                    }elseif(count($selected_size) == 0 && count($selected_shap) > 0){
                        if($newto != '' && $datafrom != ''){
                            $Products = DB::select("SELECT * FROM `products` WHERE product_shap IN (".implode(",",$selected_shap).")  AND discount_price BETWEEN ".$newto." AND ".$datafrom." ORDER BY id DESC ");
                        }else{
                            $Products = DB::select("SELECT * FROM `products` WHERE product_shap IN (".implode(",",$selected_shap).") ORDER BY id DESC ");
                        }
                        
                    }elseif(count($selected_shap) == 0 && count($selected_size) > 1){
                        if($newto != '' && $datafrom != ''){
                            $Products = DB::select("SELECT * FROM `products` WHERE product_size IN (".implode(",",$selected_size).") AND discount_price BETWEEN ".$newto." AND ".$datafrom." ORDER BY id DESC ");
                        }else{
                            $Products = DB::select("SELECT * FROM `products` WHERE product_size IN (".implode(",",$selected_size).") ORDER BY id DESC ");
                        }
                        
                    }
                    
                }elseif(count($selected_size) == 0){
                    
                    if(count($selected_shap) > 0 && count($selected_color) > 0){
                        if($newto != '' && $datafrom != ''){
                            $Products = DB::select("SELECT * FROM `products` WHERE product_color IN (".implode(",",$selected_color).") and product_shap IN (".implode(",",$selected_shap).") AND discount_price BETWEEN ".$newto." AND ".$datafrom."  ORDER BY id DESC ");
                        }else{
                            $Products = DB::select("SELECT * FROM `products` WHERE product_color IN (".implode(",",$selected_color).") and product_shap IN (".implode(",",$selected_shap).") ORDER BY id DESC ");
                        }
                        
                    }elseif(count($selected_color) == 0 && count($selected_shap) > 0){
                        if($newto != '' && $datafrom != ''){
                            $Products = DB::select("SELECT * FROM `products` WHERE product_shap IN (".implode(",",$selected_shap).") AND discount_price BETWEEN ".$newto." AND ".$datafrom." ORDER BY id DESC ");
                        }else{
                            $Products = DB::select("SELECT * FROM `products` WHERE product_shap IN (".implode(",",$selected_shap).") ORDER BY id DESC ");
                        }
                        
                    }elseif(count($selected_shap) == 0 && count($selected_color) > 1){
                        if($newto != '' && $datafrom != ''){
                            $Products = DB::select("SELECT * FROM `products` WHERE product_color IN (".implode(",",$selected_color).") AND discount_price BETWEEN ".$newto." AND ".$datafrom." ORDER BY id DESC ");
                        }else{
                            $Products = DB::select("SELECT * FROM `products` WHERE product_color IN (".implode(",",$selected_color).") ORDER BY id DESC ");
                        }
                        
                    }
                }
                
            }
            
            $html = '';
            foreach ($Products as $key => $product) {
                // dd($product->id);
                $Galleries = Gallerie::where(['product_id'=>$product->id])->get();
                
                $Catagories = Category::where(['id'=>$product->category_id])->get();
                
                if(count($Catagories) > 0){
                    $cateslug = $Catagories[0]->slug;
                    $catename = $Catagories[0]->name;
                }else{
                    $cateslug = '';
                    $catename = '';
                }
                if(count($Galleries) > 0 ){
                    $gallary = $Galleries[0]->photo;
                }else{
                    $gallary = '';
                }
                $html .= '<div class="c-product-grid_item"><div class="c-product-grid_thumb-wrap"><a href="product/'.$product->slug.'" class="lrv-loop-product_link"><img data-src="'.$product->image_one.'" src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1 1\'%3E%3C/svg%3E" class="c-product-grid_thumb lazyload lazy-placeholder" loading="lazy"><img data-src="'.$gallary.'" src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1 1\'%3E%3C/svg%3E" class="c-product-grid_thumb product_grid_thumb_hover lazyload lazy-placeholder" loading="lazy"></a><div class="c-product-grid_badges"></div><div class="c-product-grid_thumb-button-list"><button class="h-cb c-product-grid_thumb-button"><i class="ip-eye c-product-grid_icon"></i> <span class="c-product-grid_icon-text">Quick view</span></button> <button data-size="" class="js-wishlist-btn"><i class="ip-heart c-product-grid_icon"></i></button></div></div><a href="product/'.$product->slug.'" class="c-product-grid_atc"><span class="c-product-grid_atc-text">View Product</span></a><div class="c-product-grid_details"><div class="c-product-grid_title-wrap"><div class="c-product-grid_category-list"><a class="c-product-grid_category-item" href="/category/'.$cateslug.'">'.$catename.'</a></div><a href="product/'.$product->slug.'" class="product_link_link"><h2 class="lrv-loop-product_title">'.$product->product_name.'</h2></a></div><div class="c-product-grid_price-wrap"><span class="price"><span class="lrv-Price-amount amount"><bdi><span class="lrv-Price-currencySymbol">$</span>'.$product->discount_price.'</bdi></span></span></div></div></div>';
                
            }
            // return $html;
            if($html == ''){
                $html = '<span style="width: 100%;text-align: center;margin: 185px;">No Record Found!</span>';
            }else{
                $html = $html;
            }
            
            return $html;
            
    }
    
    public function get_selected_size(Request $request)
    {
            $data=$request->data;
            $old = $request->session()->get('selected_size');
            if(!$old)
            {
                $old = array();
            }
            // dd($request->session()->get('selected_size'));
            if($request->action == "add"){

                // $request->session()->forget('selected_size');
                if(isset($old)){
                    if(count($old) != 0){
                        if (in_array($data, $old))
                        {

                        }
                        else
                        {
                            array_push($old,$data);
                            $request->session()->put('selected_size',$old);
                        }

                    }else{
                        $array = array();
                        array_push($array,$data);
                        $request->session()->put('selected_size',$array);
                    }
                }
                else{
                    $array = array();
                    array_push($array,$data); 
                    $request->session()->put('selected_size',$array);
                    // print_r($old);
                }

            }else{

                foreach (array_keys($old, $data, true) as $key) {
                    unset($old[$key]);
                }

                $request->session()->forget('selected_size');
                $request->session()->put('selected_size',$old);

            }
            // dd($old);
            
            $selected_shap = $request->session()->get('selected_shap');
            $selected_color = $request->session()->get('selected_color');
            $selected_size = $request->session()->get('selected_size');
            
            $newto = $request->session()->get('to');
            $datafrom = $request->session()->get('from');
            
            $Products = Product::orderBy('id','DESC');
            // // return $selected_color;
            if($selected_color == ''){
                $selected_color = array();
            }
            if($selected_shap == ''){
                $selected_shap = array();
            }
            if($selected_size == ''){
                $selected_size = array();
            }
            
            if(count($selected_shap) > 0 && count($selected_color) > 0 && count($selected_size) > 0){
                if($newto != '' && $datafrom != ''){
                    $Products = DB::select("SELECT * FROM `products` WHERE product_color IN (".implode(",",$selected_color).") AND product_shap IN (".implode(",",$selected_shap).") AND product_size IN (".implode(",",$selected_size).") AND discount_price BETWEEN ".$newto." AND ".$datafrom."  ORDER BY id DESC ");
                }else{
                    $Products = DB::select("SELECT * FROM `products` WHERE product_color IN (".implode(",",$selected_color).") AND product_shap IN (".implode(",",$selected_shap).") AND product_size IN (".implode(",",$selected_size).") ORDER BY id DESC ");    
                }
                
            }elseif(count($selected_shap) == 0 && count($selected_color) == 0 && count($selected_size) == 0){
                if($newto != '' && $datafrom != ''){
                    $Products = DB::select("SELECT * FROM `products` WHERE discount_price BETWEEN ".$newto." AND ".$datafrom." ORDER BY id DESC ");
                }else{
                    $Products = DB::select("SELECT * FROM `products` ORDER BY id DESC ");    
                }
                
            }else{
                
                if(count($selected_shap) == 0){
                    
                    if(count($selected_color) == 0 && count($selected_size) > 0){
                        if($newto != '' && $datafrom != ''){
                            $Products = DB::select("SELECT * FROM `products` WHERE product_size IN (".implode(",",$selected_size).") AND discount_price BETWEEN ".$newto." AND ".$datafrom." ORDER BY id DESC ");
                        }else{
                            // return "SELECT * FROM `products` WHERE product_size IN (".implode(",",$selected_size).") ORDER BY id DESC ";
                            $Products = DB::select("SELECT * FROM `products` WHERE product_size IN (".implode(",",$selected_size).") ORDER BY id DESC ");
                        }
                        
                    }elseif(count($selected_color) > 0 && count($selected_size) == 0){
                        if($newto != '' && $datafrom != ''){
                            $Products = DB::select("SELECT * FROM `products` WHERE product_color IN (".implode(",",$selected_color).") AND discount_price BETWEEN ".$newto." AND ".$datafrom." ORDER BY id DESC ");
                        }else{
                            $Products = DB::select("SELECT * FROM `products` WHERE product_color IN (".implode(",",$selected_color).") ORDER BY id DESC "); 
                        }
                    }else{
                        if($newto != '' && $datafrom != ''){
                            $Products = DB::select("SELECT * FROM `products` WHERE product_color IN (".implode(",",$selected_color).") and product_size IN (".implode(",",$selected_size).") AND discount_price BETWEEN ".$newto." AND ".$datafrom." ORDER BY id DESC ");
                        }else{
                            $Products = DB::select("SELECT * FROM `products` WHERE product_color IN (".implode(",",$selected_color).") and product_size IN (".implode(",",$selected_size).") ORDER BY id DESC ");
                        }
                    }
                    
                }elseif(count($selected_color) == 0){
                    
                    if(count($selected_shap) > 0 && count($selected_size) > 0){
                        if($newto != '' && $datafrom != ''){
                            $Products = DB::select("SELECT * FROM `products` WHERE product_size IN (".implode(",",$selected_size).") and product_shap IN (".implode(",",$selected_shap).") AND discount_price BETWEEN ".$newto." AND ".$datafrom." ORDER BY id DESC ");
                        }else{
                            $Products = DB::select("SELECT * FROM `products` WHERE product_size IN (".implode(",",$selected_size).") and product_shap IN (".implode(",",$selected_shap).") ORDER BY id DESC ");
                        }
                        
                    }elseif(count($selected_size) == 0 && count($selected_shap) > 0){
                        if($newto != '' && $datafrom != ''){
                            $Products = DB::select("SELECT * FROM `products` WHERE product_shap IN (".implode(",",$selected_shap).") AND discount_price BETWEEN ".$newto." AND ".$datafrom." ORDER BY id DESC ");
                        }else{
                            $Products = DB::select("SELECT * FROM `products` WHERE product_shap IN (".implode(",",$selected_shap).") ORDER BY id DESC ");
                        }
                    }elseif(count($selected_shap) == 0 && count($selected_size) > 1){
                        if($newto != '' && $datafrom != ''){
                            $Products = DB::select("SELECT * FROM `products` WHERE product_size IN (".implode(",",$selected_size).") AND discount_price BETWEEN ".$newto." AND ".$datafrom." ORDER BY id DESC ");
                        }else{
                            $Products = DB::select("SELECT * FROM `products` WHERE product_size IN (".implode(",",$selected_size).") ORDER BY id DESC ");
                        }
                        
                    }
                    
                }elseif(count($selected_size) == 0){
                    
                    if(count($selected_shap) > 0 && count($selected_color) > 0){
                        if($newto != '' && $datafrom != ''){
                            $Products = DB::select("SELECT * FROM `products` WHERE product_color IN (".implode(",",$selected_color).") and product_shap IN (".implode(",",$selected_shap).") AND discount_price BETWEEN ".$newto." AND ".$datafrom." ORDER BY id DESC ");
                        }else{
                            $Products = DB::select("SELECT * FROM `products` WHERE product_color IN (".implode(",",$selected_color).") and product_shap IN (".implode(",",$selected_shap).") ORDER BY id DESC ");
                        }
                    }elseif(count($selected_color) == 0 && count($selected_shap) > 0){
                        if($newto != '' && $datafrom != ''){
                            $Products = DB::select("SELECT * FROM `products` WHERE product_shap IN (".implode(",",$selected_shap).") AND discount_price BETWEEN ".$newto." AND ".$datafrom." ORDER BY id DESC ");
                        }else{
                            $Products = DB::select("SELECT * FROM `products` WHERE product_shap IN (".implode(",",$selected_shap).") ORDER BY id DESC ");
                        }
                    }elseif(count($selected_shap) == 0 && count($selected_color) > 1){
                        if($newto != '' && $datafrom != ''){
                            $Products = DB::select("SELECT * FROM `products` WHERE product_color IN (".implode(",",$selected_color).") AND discount_price BETWEEN ".$newto." AND ".$datafrom." ORDER BY id DESC ");
                        }else{
                            $Products = DB::select("SELECT * FROM `products` WHERE product_color IN (".implode(",",$selected_color).") ORDER BY id DESC ");
                        }
                    }
                }
                
            }
            
            $html = '';
            foreach ($Products as $key => $product) {
                // dd($product->id);
                $Galleries = Gallerie::where(['product_id'=>$product->id])->get();
                
                $Catagories = Category::where(['id'=>$product->category_id])->get();
                
                if(count($Catagories) > 0){
                    $cateslug = $Catagories[0]->slug;
                    $catename = $Catagories[0]->name;
                }else{
                    $cateslug = '';
                    $catename = '';
                }
                if(count($Galleries) > 0 ){
                    $gallary = $Galleries[0]->photo;
                }else{
                    $gallary = '';
                }
                $html .= '<div class="c-product-grid_item"><div class="c-product-grid_thumb-wrap"><a href="product/'.$product->slug.'" class="lrv-loop-product_link"><img data-src="'.$product->image_one.'" src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1 1\'%3E%3C/svg%3E" class="c-product-grid_thumb lazyload lazy-placeholder" loading="lazy"><img data-src="'.$gallary.'" src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1 1\'%3E%3C/svg%3E" class="c-product-grid_thumb product_grid_thumb_hover lazyload lazy-placeholder" loading="lazy"></a><div class="c-product-grid_badges"></div><div class="c-product-grid_thumb-button-list"><button class="h-cb c-product-grid_thumb-button"><i class="ip-eye c-product-grid_icon"></i> <span class="c-product-grid_icon-text">Quick view</span></button> <button data-size="" class="js-wishlist-btn"><i class="ip-heart c-product-grid_icon"></i></button></div></div><a href="product/'.$product->slug.'" class="c-product-grid_atc"><span class="c-product-grid_atc-text">View Product</span></a><div class="c-product-grid_details"><div class="c-product-grid_title-wrap"><div class="c-product-grid_category-list"><a class="c-product-grid_category-item" href="/category/'.$cateslug.'">'.$catename.'</a></div><a href="product/'.$product->slug.'" class="product_link_link"><h2 class="lrv-loop-product_title">'.$product->product_name.'</h2></a></div><div class="c-product-grid_price-wrap"><span class="price"><span class="lrv-Price-amount amount"><bdi><span class="lrv-Price-currencySymbol">$</span>'.$product->discount_price.'</bdi></span></span></div></div></div>';
                
            }
            // return $html;
            if($html == ''){
                $html = '<span style="width: 100%;text-align: center;margin: 185px;">No Record Found!</span>';
            }else{
                $html = $html;
            }
            
            return $html;
            
            
    }
    
    public function get_selected_price(Request $request)
    {
        $html = '';
            $products=Product::select('products.*')->orderBy('products.id','DESC')->get();
            // dd($products);
            //price filter
            $npro = array();
            foreach ($products as $key => $product) {
                if(isset($request->min_price) && isset($request->max_price))
                {
                    //var_dump( $product->discount_price);
                    $price = str_replace(',', '', $product->discount_price);
                    $price = (float) $price;
                    $price = ceil($price);
                     
                    if($price >= $request->min_price && $price <= $request->max_price)
                    {
                        
                        $npro[] =  $product; 
                    }
                }
                else
                {
                    $npro[] =  $product;
                }
            }
            $products = $npro;
            if($request->shap)
            {
                $shap = explode(',',$request->shap);
                //  dd($shap);
            $npro = array();
            foreach ($products as $key => $product) {
                $pshaps = array();
                if($product->product_shap)
                {
                $pshaps = explode(',',$product->product_shap); 
                // dd(array_intersect($pshaps,$shap));
                }
                
                if( array_intersect($pshaps,$shap))
                {
                    $npro[] =  $product;
                }
            }
            // dd($npro); 
            $products = $npro;
            }
            if($request->size)
            {
                $size = explode(',',$request->size);
                //  dd($size);
            $npro = array();
            foreach ($products as $key => $product) {
                $pshaps = array();
                if($product->product_clarity)
                {
                    $pshaps = explode(',',$product->product_clarity); 
                // dd(array_intersect($pshaps,$shap));
                }
                
                if( array_intersect($pshaps,$size))
                {
                    $npro[] =  $product;
                }
            }
            // dd($npro); 
            $products = $npro;
            }
            if($request->category)
            {
                //  dd($size);
            $npro = array();
            foreach ($products as $key => $product) {
                $pshaps = array();
                if($product->category_id)
                {
                    $pshaps = explode(',',$product->category_id); 
                // dd(array_intersect($pshaps,$shap)); 
                }
                // if($product->id == 141)
                // {
                //     var_dump($request->category);
                //     var_dump($pshaps);
                //     die("OK");
                // }
                
                
                if(in_array($request->category, $pshaps))
                {
                    $npro[] =  $product;
                }
            }
            // dd($npro); 
            $products = $npro;
            }
            if($request->colors)
            {
                $size = explode(',',$request->colors);
                //  dd($size);
            $npro = array();
            foreach ($products as $key => $product) {
                $pshaps = array();
                if($product->product_color)
                {
                    $pshaps = explode(',',$product->product_color); 
                // dd(array_intersect($pshaps,$shap));
                }
                
                if( array_intersect($pshaps,$size))
                {
                    $npro[] =  $product;
                }
            }
            // dd($npro); 
            $products = $npro;
            }
            //price filter
            foreach ($products as $key => $product) {
                // dd($product->id);
                $Galleries = Gallerie::where(['product_id'=>$product->id])->get();
                
                $Catagories = Category::where(['id'=>$product->category_id])->get();
                
                if(count($Catagories) > 0){
                    $cateslug = $Catagories[0]->slug;
                    $catename = $Catagories[0]->name;
                }else{
                    $cateslug = '';
                    $catename = '';
                }
                if(count($Galleries) > 0 ){
                    $gallary = $Galleries[0]->photo;
                }else{
                    $gallary = '';
                }
                $html .= '<div class="c-product-grid_item"><div class="c-product-grid_thumb-wrap"><a href="'.url('/').'/product/'.$product->slug.'" class="lrv-loop-product_link"><img src="/'.$product->image_one.'" class="c-product-grid_thumb"><img src="'.$gallary.'" class="c-product-grid_thumb product_grid_thumb_hover"></a><div class="c-product-grid_badges"></div><div class="c-product-grid_thumb-button-list"><button class="h-cb c-product-grid_thumb-button"><i class="ip-eye c-product-grid_icon"></i> <span class="c-product-grid_icon-text">Quick view</span></button> <button data-size="" class="js-wishlist-btn"><i class="ip-heart c-product-grid_icon"></i></button></div></div><a href="product/'.$product->slug.'" class="c-product-grid_atc"><span class="c-product-grid_atc-text">View Product</span></a><div class="c-product-grid_details"><div class="c-product-grid_title-wrap"><div class="c-product-grid_category-list"><a class="c-product-grid_category-item" href="/category/'.$cateslug.'">'.$catename.'</a></div><a href="product/'.$product->slug.'" class="product_link_link"><h2 class="lrv-loop-product_title">'.$product->product_name.'-'.$product->category_id.'</h2></a></div><div class="c-product-grid_price-wrap"><span class="price"><span class="lrv-Price-amount amount"><bdi><span class="lrv-Price-currencySymbol">$</span>'.$product->discount_price.'</bdi></span></span></div></div></div>';
                
            }
            // var_dump($html);
            
            if($html == ''){
                $html = '<span style="width: 100%;text-align: center;margin: 185px;">No Record Found!</span>';
            }else{
                $html = $html;
            }
            
            return $html;
            
            
    }
    
    public function get_selected_detail(Request $request)
    {
        $data=$request->data;
        $old = $request->session()->get('selected_color');
        
        if($request->action == "add" && $request->method == "colors"){

            // $request->session()->forget('selected_color');
            if(isset($old)){
                if(count($old) != 0){
                    if (in_array($data, $old))
                    {

                    }
                    else
                    {
                        array_push($old,$data);
                        $request->session()->put('selected_color',$old);
                    }

                }else{
                    $array = array();
                    array_push($array,$data);
                    $request->session()->put('selected_color',$array);
                }
            }
            else{
                $array = array();
                array_push($array,$data); 
                $request->session()->put('selected_color',$array);
                // print_r($old);
            }

        }elseif($request->action == "remove" && $request->method == "colors"){

            foreach (array_keys($old, $data, true) as $key) {
                unset($old[$key]);
            }

            $request->session()->forget('selected_color');
            $request->session()->put('selected_color',$old);

        }
        
        $oldone = $request->session()->get('selected_size');
        if($request->action == "add" && $request->method == "colors"){

            // $request->session()->forget('selected_size');
            if(isset($oldone)){
                if(count($oldone) != 0){
                    if (in_array($data, $oldone))
                    {

                    }
                    else
                    {
                        array_push($oldone,$data);
                        $request->session()->put('selected_size',$oldone);
                    }

                }else{
                    $array = array();
                    array_push($array,$data);
                    $request->session()->put('selected_size',$array);
                }
            }
            else{
                $array = array();
                array_push($array,$data); 
                $request->session()->put('selected_size',$array);
                // print_r($oldone);
            }

        }elseif($request->action == "remove" && $request->method == "colors"){

            foreach (array_keys($oldone, $data, true) as $key) {
                unset($oldone[$key]);
            }

            $request->session()->forget('selected_size');
            $request->session()->put('selected_size',$oldone);

        }
        
            
    }

    public function brand_detail($slug)
    {
        Session::put('title','Brand');
        $page = "brand";
        $perPage = env('PRODUCTS_PER_PAGE', 12); // Default to 12, configurable via env
        
        $brand_id = Brand::where(['slug'=>$slug , 'status'=>1])->first();
        if(!$brand_id) {
            return abort(404);
        }
        
        $products = Product::select('products.*')
            ->where('status','1')
            ->where('brand', $brand_id->id)
            ->orderBy('view','DESC')
            ->paginate($perPage);
            
        return $this->view('list',array(
            'page'=>$page,
            'products'=>$products,
            'title'=> $brand_id->name,
            'brand_name' => $brand_id->name,
            'brand_slug' => $brand_id->slug,
            'brand_id' => $brand_id->id,
            'brand' => $brand_id,
            'meta_file' => 'meta.brand',
            'meta' => (object) [
                'title' => ($brand_id->title ?? $brand_id->name) . ' - ' . seo_site_name(),
                'description' => $brand_id->description ?? $brand_id->name,
                'keywords' => $brand_id->s_keywords ?? $brand_id->keywords ?? $brand_id->name,
                'url' => url('brand/' . $brand_id->slug),
                'scheme' => seo_collection_schema(
                    $brand_id->name,
                    (string) ($brand_id->description ?? $brand_id->name),
                    url('brand/' . $brand_id->slug)
                ),
                'scheme1' => seo_breadcrumb_schema([
                    ['name' => 'Home', 'item' => url('/')],
                    ['name' => $brand_id->name, 'item' => url('brand/' . $brand_id->slug)],
                ]),
            ],
        ));
    }
    
    
     public function brandstags($id)
    {
        $brands=Brand::all();
        $Slider=Slider::all();
        $categories=Category::all();
        $tags = $btags = trim(str_replace('-', ' ', $id));
        $brand_id = Brand::where('keywords', 'like', '%'.$id.'%')->get();
       
        $brand = array();
        if(isset($brand_id[0]))
        {
            $brand = $brand_id[0];
        }
        $rproducts =  array();
        if(isset($brand_id[0]))
        {
        $rproducts = product::where(['brand'=>$brand_id[0]->id , 'status'=>1])->get();
        }
        
        
        $product = $rproducts; 
        // dd($product);
        $meta_file  = 'meta.brand_tag';
        if($brand)
        return view('front.brand_tags',compact('rproducts','categories','brands','brand','meta_file','btags','product'));
        else
        abort(404);
    }

    public function tags_detail($slug)
    {
        $slug = Str::slug((string) $slug);
        if ($slug === '') {
            abort(404);
        }
        $label = trim(str_replace('-', ' ', $slug));
        $title = ucwords($label);
        $perPage = env('PRODUCTS_PER_PAGE', 12);

        $query = Product::where('status', 1)->where(function ($q) use ($slug, $label) {
            $q->where('tags', 'like', '%' . $label . '%')
                ->orWhere('tags', 'like', '%' . str_replace('-', '_', $slug) . '%')
                ->orWhere('tags', 'like', '%' . $slug . '%');
        });

        $rproducts = $query->orderBy('view', 'DESC')->paginate($perPage);
        Session::put('title', $title);

        return $this->view('list', [
            'products' => $rproducts,
            'title' => $title,
            'tags' => $label,
            'slug' => $slug,
            'meta_file' => 'meta.product_tag',
            'product' => $rproducts,
            'pagination' => 0,
        ]);
    }

    public function search_detail(Request $slug)
    {
        $brands=Brand::all();
        $Slider=Slider::all();
        $categories=Category::all();
        // return $slug->input();
        $rproducts = product::where('product_name', 'like', '%'.$slug->input('q').'%')->where('status','1')->get();

        return view('front.result_detail',compact('rproducts','categories','brands'));
     }
    public function search_results(Request $request)
    {
        $search_query = $request->get('q', '');
        
        if(empty($search_query)) {
            return redirect('/');
        }
        
        $categories = Category::all();
        $setting = DB::table('setting')->where('id', 1)->first();
        $perPage = env('PRODUCTS_PER_PAGE', 12);
        
        // First, check if there's a category with exact or similar name
        $matchedCategory = Category::where('status', 1)
            ->where(function($query) use ($search_query) {
                $query->where('name', 'like',$search_query);
            })
            ->first();
        
        // If category found, show all products from that category
        if($matchedCategory) {
            $rproducts = Product::where('status', 1)
                ->where('category_id', $matchedCategory->id)
                ->with(['brand', 'category'])
                ->orderBy('view', 'DESC')
                ->paginate($perPage);
            
            Session::put('title', $matchedCategory->name);
            
            return $this->view('list', [
                'products' => $rproducts,
                'title' => $matchedCategory->name,
                'page' => 'category',
                'search_query' => $search_query,
                'matched_category' => $matchedCategory
            ]);
        }
        
        // If no category found, do normal product search
        $rproducts = Product::where('status', 1)
            ->where(function($query) use ($search_query) {
                $query->where('product_name', 'like', '%'.$search_query.'%')
                      ->orWhere('product_details', 'like', '%'.$search_query.'%')
                      ->orWhere('short_discriiption', 'like', '%'.$search_query.'%')
                      ->orWhere('tags', 'like', '%'.$search_query.'%')
                      ->orWhere('product_code', 'like', '%'.$search_query.'%')
                      ->orWhere('sku', 'like', '%'.$search_query.'%')
                      ->orWhereHas('brand', function($q) use ($search_query) {
                          $q->where('name', 'like', '%'.$search_query.'%')
                            ->orWhere('keywords', 'like', '%'.$search_query.'%');
                      })
                      ->orWhereHas('category', function($q) use ($search_query) {
                          $q->where('name', 'like', '%'.$search_query.'%')
                            ->orWhere('keywords', 'like', '%'.$search_query.'%')
                            ->orWhere('description', 'like', '%'.$search_query.'%');
                      });
            })
            ->with(['brand', 'category'])
            ->orderBy('product_name', 'asc')
            ->paginate($perPage);
        
        // Set page title for SEO
        Session::put('title', 'Search Results for "' . $search_query . '"');
        
        return $this->view('list', [
            'products' => $rproducts,
            'title' => 'Search Results for "' . $search_query . '"',
            'page' => 'search',
            'search_query' => $search_query
        ]);
    }

    public function liveSearch(Request $request)
    {
        $search_query = $request->get('q', '');
        
        if(empty($search_query) || strlen($search_query) < 2) {
            return response()->json(['products' => []]);
        }
        
        // First, check if there's a category matching the search
        $matchedCategory = Category::where('status', 1)
            ->where(function($query) use ($search_query) {
                $query->where('name', 'like', '%'.$search_query.'%')
                      ->orWhere('slug', 'like', '%'.$search_query.'%')
                      ->orWhere('keywords', 'like', '%'.$search_query.'%');
            })
            ->first();
        
        // If category found, show products from that category
        if($matchedCategory) {
            $products = Product::where('status', 1)
                ->where('category_id', $matchedCategory->id)
                ->with(['brand', 'category'])
                ->orderBy('view', 'DESC')
                ->limit(10)
                ->get();
        } else {
            // If no category found, do normal product search
            $products = Product::where('status', 1)
                ->where(function($query) use ($search_query) {
                    $query->where('product_name', 'like', '%'.$search_query.'%')
                          ->orWhere('product_details', 'like', '%'.$search_query.'%')
                          ->orWhere('short_discriiption', 'like', '%'.$search_query.'%')
                          ->orWhere('tags', 'like', '%'.$search_query.'%')
                          ->orWhere('product_code', 'like', '%'.$search_query.'%')
                          ->orWhere('sku', 'like', '%'.$search_query.'%')
                          ->orWhereHas('brand', function($q) use ($search_query) {
                              $q->where('name', 'like', '%'.$search_query.'%')
                                ->orWhere('keywords', 'like', '%'.$search_query.'%');
                          })
                          ->orWhereHas('category', function($q) use ($search_query) {
                              $q->where('name', 'like', '%'.$search_query.'%')
                                ->orWhere('keywords', 'like', '%'.$search_query.'%')
                                ->orWhere('description', 'like', '%'.$search_query.'%');
                          });
                })
                ->with(['brand', 'category'])
                ->orderBy('product_name', 'asc')
                ->limit(10)
                ->get();
        }
        
        $results = [];
        foreach($products as $product) {
            $imageUrl = '';
            // Check which image field exists
            $imageField = $product->image_one ?? $product->image ?? null;
            
            if ($imageField) {
                if (strpos($imageField, 'http') === 0) {
                    $imageUrl = $imageField;
                } else {
                    // Use custom_assets helper if available, otherwise construct URL
                    $imageUrl = img_url($imageField);
                }
            }

            $listPrice = (float) ($product->selling_price ?? $product->price ?? 0);
            $salePrice = (float) ($product->discount_price ?? 0);
            $finalPrice = ($salePrice > 0 && $salePrice < $listPrice) ? $salePrice : $listPrice;
            
            $results[] = [
                'id' => $product->id,
                'name' => $product->product_name,
                'slug' => $product->slug ?? '',
                'image' => $imageUrl,
                'price' => $finalPrice,
                'url' => product_url($product)
            ];
        }
        
        return response()->json(['products' => $results]);
    }

    public function search_detail1($slug)
    {
        // $brands=Brand::all();
        $Slider=Slider::all();
        $categories=Category::all();
        // return $slug->input();
        $rproducts = product::where('status',1)->where('product_name', 'like', '%'.$slug.'%')->get();
        // $slug = $rproducts->slug;
        $search = 1;
        return view('theme1.search',compact('rproducts','categories','search'));
    }
}
