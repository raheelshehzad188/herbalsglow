<?php

namespace App\Http\Controllers\Front;

use App\Models\Admins\Category;
use App\Models\Admins\Blog_Post;
use App\Models\Admins\Slider;
use App\Models\Admins\Brand;
use App\Models\Admins\User;
use App\Models\Admins\SubCategory;
use App\Models\Admins\Blog_Category;
use App\Models\Admins\Product; 
use App\Models\Admins\Order;
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
use App\Helpers\Cart;
use Session;
use Mail;
use DB;
use Cookie;
use Illuminate\Support\Str;




class FrontController extends Controller
{
    
    
    public function index()
    {
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
                <loc>'.url('category/').'/'.$post->slug.'</loc> 
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
        $shap = Shap::all();
        $setting = DB::table('setting')
            ->where('id', '=', '1')
            ->first();

        $meta = DB::table('setting')
            ->where('id', '=', '1')
            ->first();
        if($meta)
        {
            $meta->url = url('/');
            $sch = array (
              '@context' => 'https://schema.org/',
              'name' => $meta->title,
              'description' => $meta->description,
              'brand' =>
              array (
                '@type' => 'Brand',
                'name' => 'UAE Disar',
              ),
              'sku' => 'ebaytelemart_diSUa',
              'gtin13' => '300024462524',
              'offers' =>
              array (
                '@type' => 'AggregateOffer',
                'url' => url()->current(),
                'priceCurrency' => 'PKR',
                'lowPrice' => '1999',
                'highPrice' => '2000',
                'offerCount' => '5',
              ),
              'aggregateRating' =>
              array (
                '@type' => 'AggregateRating',
                'ratingValue' => '5',
                'bestRating' => '5',
                'worstRating' => '1',
                'ratingCount' => '1',
                'reviewCount' => '1',
              ),
              'review' =>
              array (
                '@type' => 'Review',
                'name' => 'Fahad Khan',
                'reviewBody' => 'Best Product',
                'reviewRating' =>
                array (
                  '@type' => 'Rating',
                  'ratingValue' => '5',
                  'bestRating' => '5',
                  'worstRating' => '1',
                ),
                'datePublished' => '2022-05-01',
                'author' =>
                array (
                  '@type' => 'Person',
                  'name' => 'Ebaytelemart',
                ),
                'publisher' =>
                array (
                  '@type' => 'Organization',
                  'name' => 'Ebaytelemart',
                ),
              ),
            );
            $meta->scheme = $sch;
                        }



        $products=Product::select('products.*')->orderBy('products.id','DESC')->get();
        $fproducts=Product::select('products.*')->where('Featured','1')->orderBy('products.id','DESC')->limit(10)->get();
        
        $aproducts=Product::select('products.*')->where('New_Arrival','1')->orderBy('products.id','DESC')->limit(10)->get();
        $onslaeproducts=Product::select('products.*')->where('Sale','1')->orderBy('products.id','DESC')->limit(10)->get();
        $mostviewproducts=Product::select('products.*')->orderBy('view','DESC')->limit(10)->get();
        $home_cats = DB::table('home_cats')->where('status','=',1)->orderBy('home_cats.sort')->get();
        Session::put('title','Home');
        
        return view('front.home1',compact('page','products','categories','fproducts','setting','aproducts','mostviewproducts','Slider','Rating','meta','onslaeproducts','shap','home_cats'));
    }
    
   function login()
    {   
         Session::put('title','Login');
        return view('front.login');

    } 
    public function import()
    {
        
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
    public function product_detail($slug)
    {
        $allcatagories = Category::where(['status'=>1])->get();
        $Slider=Slider::all();
        $product = product::where(['slug'=>$slug , 'status'=>1])->get();
        if(count($product) > 0){
            
            $pro = $product[0];
            $rat = Rating::where(['status'=>1,'pid'=>$product[0]->id])->first();
            if($rat){
                $name =$rat->name ;
                $rateb =$rat->review ;
                $rate = $rat->rate;
                
            }else{
                $name ='' ;
                $rateb ='';
                $rate = '';
            }
            $sett = Setting::where('id','1')->first();
            $meta = DB::table('products_to_meta')
                ->where('pid', '=', $product[0]->id)
                ->first();
               
            $view = $product[0]->view;
            $category_id = $product[0]->category_id;
            $rproducts = product::where('category_id','=',$category_id)->where('id','!=',$product[0]->id)->limit('4')->get();
            $cate = Category::where(['id'=>$category_id])->first();
            $rating = Rating::where(['status'=>1,'pid'=>$product[0]->id])->get();
            $rcount = Rating::where(['status'=>1,'pid'=>$product[0]->id])->count();
            $faq = Faq::where(['status'=>1,'pid'=>$product[0]->id])->get();
            $fproduct=Product::find($product[0]->id);
            $fproduct->view = $view+1;
            $fproduct->save();
            $product_detail = 1;
            Session::put('title','Product Detail');
            return view('front.product_detail',compact('allcatagories','sett','product','rcount','category_id','rproducts','cate','meta','faq','rating','Slider','product_detail'));
            
        }else{
            
            return abort(404);
        }
        
        

        return view('front.product_detail',compact('allcatagories','product','category_id','rproducts','meta','rating','Slider'));
    }
    public function blog_detail($slug)
    {
        $blog = Blog_Post::where(['slug'=>$slug])->get();
        $meta = Blog_Post::where(['slug'=>$slug])->first();
        $cid = $blog[0]->category_id;
        $rblog = Blog_Post::where('category_id','=',$cid)->where('id','!=',$blog[0]->id)->get();
        $cum = Blog_Comment::where(['bid'=>$blog[0]->id])->get();
        $bcate = Blog_Category::all();
        Session::put('title','Blog Detail');
        return view('front.blog_detail',compact('blog','rblog','cum','bcate','meta'));
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
    
     public function shop(Request $request)
    {
        Session::put('title','Shop');
        $page = "shop";
        $best =Product::select('products.*')->orderBy('view','DESC')->limit(3)->get();
        return view('front.shop',compact('page','best'));
    }
    
    public function user_register(Request $request)
    {
        Session::put('title','User Register');
       
        return view('front.register');
    }
    
    public function category_detail($slug)
    {
        $Slider=Slider::all();
        $categories=Category::all();
        $best =Product::select('products.*')->orderBy('view','DESC')->limit(3)->get();

        $category_id = Category::where(['slug'=>$slug ])->first();
        $cateid = $category_id->id;
         if(isset($category_id->name))
        {
         Session::put('title',$category_id->name);
        }

        $meta = DB::table('categories_to_meta')
            ->where('cid', '=', $category_id->id)
            ->first();
            if($meta)
            {
                $meta->url = url('/category/').'/'.$slug;
                $sch = array (
                  '@context' => 'https://schema.org/',
                  '@type' => 'Category',
                  'name' => $meta->title,
                  'description' => $meta->description,
                  'brand' =>
                  array (
                    '@type' => 'Brand',
                    'name' => 'UAE Disar',
                  ),
                  'sku' => 'ebaytelemart_diSUa',
                  'gtin13' => '300024462524',
                  'offers' =>
                  array (
                    '@type' => 'AggregateOffer',
                    'url' => url()->current(),
                    'priceCurrency' => 'PKR',
                    'lowPrice' => '1999',
                    'highPrice' => '2000',
                    'offerCount' => '5',
                  ),
                  'aggregateRating' =>
                  array (
                    '@type' => 'AggregateRating',
                    'ratingValue' => '5',
                    'bestRating' => '5',
                    'worstRating' => '1',
                    'ratingCount' => '1',
                    'reviewCount' => '1',
                  ),
                  'review' =>
                  array (
                    '@type' => 'Review',
                    'name' => 'Fahad Khan',
                    'reviewBody' => 'Best Product',
                    'reviewRating' =>
                    array (
                      '@type' => 'Rating',
                      'ratingValue' => '5',
                      'bestRating' => '5',
                      'worstRating' => '1',
                    ),
                    'datePublished' => '2022-05-01',
                    'author' =>
                    array (
                      '@type' => 'Person',
                      'name' => 'Ebaytelemart',
                    ),
                    'publisher' =>
                    array (
                      '@type' => 'Organization',
                      'name' => 'Ebaytelemart',
                    ),
                 )
                );
                $meta->scheme = $sch;
                            }
        
         $products=Product::where(['category_id'=>$cateid , 'status'=>1])->get();
       
       
        
        return view('front.category_detail',compact('meta','products','category_id','best'));

       
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
       
      
        Session::put('title','Blogs');
        $post=Blog_Post::all();
        $cate = Blog_Category::limit('6')->get();
        return view('front.blog',compact('post','cate'));

       
    }
    
    public function cart()
    {
        Session::put('title','Cart');
        return view('front.cart');
    }
    
    public function contact()
    {
        Session::put('title','Contact Us');
        return view('front.contact');
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
        //  if(Session::get('user')){
        //       $uid = Session::get('user')['id'];
        // $user = User::where(['id'=>$uid ])->get();
        //      Session::put('title','Order Checkout');
        // return view('front.order',compact('user'));
        //  }else{
        
        Session::put('title','Checkout');
        return view('front.order');
        //  }
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

    public function page_detail($slug)
    {
        $Slider=Slider::all();
        $size = Size::all();
        $colors = Colors::all();
        $shap = Shap::all();
        $meta = '';
       $pages = Pages::where(['slug'=>$slug])->get();
        $title = ''; 
        if(isset($pages[0]->name))
        {
         Session::put('title',$pages[0]->name);
        }
        return view('front.dynamicpage',compact('title','pages','slug','Slider','meta','size','colors','shap'));
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
        return view('front.about',compact('Slider','meta','size','colors','shap','products','categories'));
    }
    public function learn(Request $request)
    {
        $setting=Learn_setting::all();
        Session::put('title','Learn');
        return view('front.new',compact('setting'));
    }
    
     public function faq(Request $request)
    {
        
        $faq=Faq::all();
        Session::put('title','FAQ');
        return view('front.faq',compact('faq'));
    }
    // public function order(Request $request)
    // {
             
    //         $setting=product::where(['id'=>$request->pid ])->get();
    //         Session::put('title','Checkout');
    //         return view('front.checkout',compact('setting'));
    // }

    public function order_submit(Request $request)
    {
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
            
            $Order->amount=Session::get('cart')['amount'];
            $Order->save();
            $lastid=$Order->id; 
            $productt = Product::find($product->id);
             $productt->product_quantity=$productt->product_quantity-$product['qty'];
             $productt->update();
            }
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
            $message->from('info@markethubland.com','markethubland.com');
            });
            $to = 'info@markethubland.com	';
            $to_n = 'Admin';
            Mail::send('emails.admin_order', $data, function($message) use ($to_n, $to) {
            $message->to($to, $to_n)
            ->subject('Order Email’');
            $message->from('info@markethubland.com','markethubland.com');
            });
            }
            Session::forget('cart');
            return redirect('/my_account')->with([
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
           
            $Order->amount=Session::get('cart')['amount'];
            
            $Order->save();
            $lastid=$Order->id; 
            
            $productt = Product::find($product->id);
             $productt->product_quantity=$productt->product_quantity-$product->qty;
             $productt->update();
             
            }
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
            $message->from('info@markethubland.com','markethubland.com');
            });
            $to = 'info@markethubland.com';
            $to_n = 'Admin';
            Mail::send('emails.admin_order', $data, function($message) use ($to_n, $to) {
            $message->to($to, $to_n)
            ->subject('Order Email’');
            $message->from('info@markethubland.com','markethubland.com');
            });
            }
        
            Session::forget('cart');
            return redirect('/')->with([
                'msg'=>'Order submit successfully',
                'msg_type'=>'success',
            ]);
        }
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
        // dd($request);
            $rating=new Rating();

            $rating->pid=$request->pid;
            $rating->rate=$request->rating;
            $rating->name=$request->name;
            $rating->email=$request->email;
            $rating->review=$request->review;
            $rating->save();

            $product = product::where(['id'=>$request->pid , 'status'=>1])->get();
            $pro = $product[0];

            return redirect('/product/'.$product[0]->slug)->with([
                'message'=>'Review submit successfully',
                'msg_type'=>'success',
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
                $html .= '<div class="c-product-grid_item"><div class="c-product-grid_thumb-wrap"><a href="product/'.$product->slug.'" class="lrv-loop-product_link"><img src="'.$product->image_one.'" class="c-product-grid_thumb"><img src="'.$gallary.'" class="c-product-grid_thumb product_grid_thumb_hover"></a><div class="c-product-grid_badges"></div><div class="c-product-grid_thumb-button-list"><button class="h-cb c-product-grid_thumb-button"><i class="ip-eye c-product-grid_icon"></i> <span class="c-product-grid_icon-text">Quick view</span></button> <button data-size="" class="js-wishlist-btn"><i class="ip-heart c-product-grid_icon"></i></button></div></div><a href="product/'.$product->slug.'" class="c-product-grid_atc"><span class="c-product-grid_atc-text">View Product</span></a><div class="c-product-grid_details"><div class="c-product-grid_title-wrap"><div class="c-product-grid_category-list"><a class="c-product-grid_category-item" href="/category/'.$cateslug.'">'.$catename.'</a></div><a href="product/'.$product->slug.'" class="product_link_link"><h2 class="lrv-loop-product_title">'.$product->product_name.'</h2></a></div><div class="c-product-grid_price-wrap"><span class="price"><span class="lrv-Price-amount amount"><bdi><span class="lrv-Price-currencySymbol">$</span>'.$product->discount_price.'</bdi></span></span></div></div></div>';
                
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
                $html .= '<div class="c-product-grid_item"><div class="c-product-grid_thumb-wrap"><a href="product/'.$product->slug.'" class="lrv-loop-product_link"><img src="'.$product->image_one.'" class="c-product-grid_thumb"><img src="'.$gallary.'" class="c-product-grid_thumb product_grid_thumb_hover"></a><div class="c-product-grid_badges"></div><div class="c-product-grid_thumb-button-list"><button class="h-cb c-product-grid_thumb-button"><i class="ip-eye c-product-grid_icon"></i> <span class="c-product-grid_icon-text">Quick view</span></button> <button data-size="" class="js-wishlist-btn"><i class="ip-heart c-product-grid_icon"></i></button></div></div><a href="product/'.$product->slug.'" class="c-product-grid_atc"><span class="c-product-grid_atc-text">View Product</span></a><div class="c-product-grid_details"><div class="c-product-grid_title-wrap"><div class="c-product-grid_category-list"><a class="c-product-grid_category-item" href="/category/'.$cateslug.'">'.$catename.'</a></div><a href="product/'.$product->slug.'" class="product_link_link"><h2 class="lrv-loop-product_title">'.$product->product_name.'</h2></a></div><div class="c-product-grid_price-wrap"><span class="price"><span class="lrv-Price-amount amount"><bdi><span class="lrv-Price-currencySymbol">$</span>'.$product->discount_price.'</bdi></span></span></div></div></div>';
                
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
                $html .= '<div class="c-product-grid_item"><div class="c-product-grid_thumb-wrap"><a href="product/'.$product->slug.'" class="lrv-loop-product_link"><img src="'.$product->image_one.'" class="c-product-grid_thumb"><img src="'.$gallary.'" class="c-product-grid_thumb product_grid_thumb_hover"></a><div class="c-product-grid_badges"></div><div class="c-product-grid_thumb-button-list"><button class="h-cb c-product-grid_thumb-button"><i class="ip-eye c-product-grid_icon"></i> <span class="c-product-grid_icon-text">Quick view</span></button> <button data-size="" class="js-wishlist-btn"><i class="ip-heart c-product-grid_icon"></i></button></div></div><a href="product/'.$product->slug.'" class="c-product-grid_atc"><span class="c-product-grid_atc-text">View Product</span></a><div class="c-product-grid_details"><div class="c-product-grid_title-wrap"><div class="c-product-grid_category-list"><a class="c-product-grid_category-item" href="/category/'.$cateslug.'">'.$catename.'</a></div><a href="product/'.$product->slug.'" class="product_link_link"><h2 class="lrv-loop-product_title">'.$product->product_name.'</h2></a></div><div class="c-product-grid_price-wrap"><span class="price"><span class="lrv-Price-amount amount"><bdi><span class="lrv-Price-currencySymbol">$</span>'.$product->discount_price.'</bdi></span></span></div></div></div>';
                
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
        $brands=Brand::all();
        $Slider=Slider::all();
        $categories=Category::all();

        $brand_id = brand::where(['slug'=>$slug , 'status'=>1])->get();
        // dd($brand_id[0]->id);
        $rproducts = product::where(['brand_id'=>$brand_id[0]->id , 'status'=>1])->get();

        return view('front.result_detail',compact('rproducts','categories','brands'));
    }

    public function tags_detail($slug)
    {
        $Slider=Slider::all();
        $categories=Category::all();

        $rproducts = product::where('tags', 'like', '%'.$slug.'%')->get();

        $tags = str_replace('-', ' ', $slug);

        return view('front.result_detail',compact('rproducts','categories','tags','slug'));
    }

    // public function search_detail(Request $slug)
    // {
    //     $brands=Brand::all();
    //     $Slider=Slider::all();
    //     $categories=Category::all();
    //     // return $slug->input();
    //     $rproducts = product::where('product_name', 'like', '%'.$slug->input('q').'%')->get();

    //     return view('front.result_detail',compact('rproducts','categories','brands'));
    // }
    public function search_detail(Request $slug)
    {
        // $brands=Brand::all();
        $Slider=Slider::all();
        $categories=Category::all();
        // return $slug->input();
        $rproducts = product::where('product_name', 'like', '%'.$slug->text.'%')->get();
        // $slug = $rproducts->slug;

        return view('front.result_detail',compact('rproducts','categories'));
    }
}
