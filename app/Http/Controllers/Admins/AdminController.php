<?php

namespace App\Http\Controllers\Admins;

use App\Models\Admins\Category;
use App\Models\Admins\Box;
use App\Models\Admins\Brand;
use App\Models\Admins\Colors;
use App\Models\Admins\Size;
use App\Models\Admins\Shap;
use App\Models\Admins\Media;
use App\Models\Admins\Blog_Post;
use App\Models\Admins\Slider;
use App\Models\Admins\Client;
use App\Models\Admins\PaymentMethod;
use App\Models\Admins\Contact;
use App\Models\Admins\Faq;
use App\Models\Admins\Gallerie;
use App\Models\Admins\SubCategory;
use App\Models\Admins\Admin;
use App\Models\Admins\Blog_Category;
use App\Models\Admins\Product;
use App\Models\Admins\Order;
use App\Models\Admins\Pages;
use App\Models\Admins\Setting;
use App\Models\Admins\Learn_setting;
use App\Models\Admins\Rating;
use App\Models\Admins\ProductsToMeta;
use App\Models\Admins\CategoriesToMeta;
use App\Models\Admins\Coupon;
use App\Models\Newsletter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Mail;
use Intervention\Image\Facades\Image as ImageFacade;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Validation\ValidationException;
use App\Support\StoreContext;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    public function __construct()
    {
        // Laravel handles sessions automatically
        // session_start(); // Remove this line as Laravel manages sessions
    }

    protected function storeScopedQuery($query, ?string $table = null)
    {
        return StoreContext::apply($query, $table);
    }

    protected function assertStoreOwns($model)
    {
        if ($model && !StoreContext::owns($model)) {
            abort(403, 'This record belongs to another store.');
        }
        return $model;
    }

    public function adminloginpage()
    {
        try {
            if(Session::has('admin'))
            {
                return redirect(url('/admin/dashboard'));
            }
            return view('admins.login');
        } catch (\Exception $e) {
            // Log error for debugging
            \Log::error('Admin login page error: ' . $e->getMessage());
            return view('admins.login');
        }
    }

    function admin_login_submit(Request $req)
    {   
        $user = Admin::where(['email'=>$req->email])->first();
        
        if ($user) {
            if (Hash::check($req->password, $user->password)) {
                // Store admin data in Laravel session
                Session::put('admin', $user);
                $req->session()->put('admin', $user);
                return redirect('admin/dashboard');
            } else {
                $req->session()->flash('invalid','Enter Your Correct Password');
                return redirect()->back();
            }
        } else {
            $req->session()->flash('invalid','Invalid Email & Password');
            return redirect()->back();
        }
    }
    
    function admin(){
        $edit=Admin::all();
        return view('admins.admin',compact('edit'));
    }
    
    function create_admin(Request $req)
    {
        $req->validate([
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:6'
        ]);
        
        $admin = new Admin();
        $admin->email = $req->email;
        $admin->password = Hash::make($req->password);
        $admin->save();
        
        return redirect(route('admins.admin'))->with([
            'msg'=>'Admin created Successfully',
            'msg_type'=>'success',
        ]);
    }
    
    function update_admin(Request $req)
    {
        $req->validate([
            'email' => 'required|email|unique:admins,email,'.$req->id,
            'password' => 'required|min:6'
        ]);
        
        $admin = Admin::find($req->id);
        $admin->email = $req->email;
        $admin->password = Hash::make($req->password);
        $admin->save();
        
        return redirect(route('admins.admin'))->with([
            'msg'=>'Admin updated Successfully',
            'msg_type'=>'success',
        ]);
    }
    
    function delete_admin($id)
    {
        // Prevent deleting the current logged-in admin
        if(Session::has('admin') && Session::get('admin')->id == $id) {
            return redirect(route('admins.admin'))->with([
                'msg'=>'You cannot delete your own account',
                'msg_type'=>'error',
            ]);
        }
        
        $admin = Admin::find($id);
        if($admin) {
            $admin->delete();
            return redirect(route('admins.admin'))->with([
                'msg'=>'Admin deleted Successfully',
                'msg_type'=>'success',
            ]);
        } else {
            return redirect(route('admins.admin'))->with([
                'msg'=>'Admin not found',
                'msg_type'=>'error',
            ]);
        }
    }


    function logout(Request $req)
    {
        // Clear Laravel session
        Session::flush();
        $req->session()->flush();
        $req->session()->invalidate();
        $req->session()->regenerateToken();

        return redirect('admin/login');
    }
    function login()
    {
        return view('admin/login'); 
    }

    public function dashboard(){
        
        $categories=Category::all();
        $products=Product::all();
        $rating=Rating::all();
        $urreviews = $r = DB::table('rating')->where('is_read',0)->get();
        $corders = Order::where('status',3)->get();
        $unrorders = Order::where('status',0)->get();
        return view('admins.dashboard',compact('categories','products','rating','corders','unrorders','urreviews'));
    }
    
    public function boxs(Request $request,$id=0,$delete=null){
        $edit=null;
        $seo = null;
        if(isset($delete) && $id>0){
            Box::find($id)->delete();
            return redirect(route('admins.boxs'))->with([
                'msg'=>'Box Deleted Successfully',
                'msg_type'=>'success',
            ]);
        }
        if($id>0 && !isset($delete)){
            $edit=Box::find($id);
        }
        if ($request->isMethod('post')) {

            if($request->has('hidden_id')){
                
                $category=Box::find($request->hidden_id);
                $category->icon=$request->icon;
                $category->txt=$request->txt;
                $category->heading=$request->heading;
                $category->sort=$request->sort ?? 0;
                
                $category->updated_at=Date('Y-m-d h:i:s');
                $category->save();
                
                
            }else{
                
                $category=new Box();
                $category->created_at=Date('Y-m-d h:i:s');
                $category->icon=$request->icon;
                $category->txt=$request->txt;
                $category->heading=$request->heading;
                $category->sort=$request->sort ?? 0;
                $category->updated_at=Date('Y-m-d h:i:s');
                $category->save();
                
                
            }
            
            
           
            return redirect(route('admins.boxs'))->with([
                'msg'=>'Category Saved Successfully',
                'msg_type'=>'success',
            ]);
        }
        $categories=Box::orderBy('sort', 'ASC')->orderBy('id', 'DESC')->get();
        return view('admins.box',compact('categories','edit','seo'));
    }
    public function brand(Request $request,$id=0,$delete=null){
        $edit=null;
        $seo = null;
        if(isset($delete) && $id>0){
            $del = Brand::find($id);
            $this->assertStoreOwns($del);
            if ($del) { $del->delete(); }
            return redirect(route('admins.brand'))->with([
                'msg'=>'Brand Deleted Successfully',
                'msg_type'=>'success',
            ]);
        }
        if($id>0 && !isset($delete)){
            $edit=$this->assertStoreOwns(Brand::find($id));
        }
        if ($request->isMethod('post')) {
            $request->validate([
                'name'=>'required|unique:categories,name,'.$request->hidden_id,
            ]);

            if($request->has('hidden_id')){
                
                $category=$this->assertStoreOwns(Brand::find($request->hidden_id));
                $category->name=$request->name;
                $category->status=$request->status;
                $category->slug= strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->name)));
                 $category->title=$request->title;
                $category->description=$request->description;
                 $tags = preg_replace('/\s+/', '-', $request->tags);
               
                $category->keywords=$tags;
                 $category->s_schema=$request->s_schema;
               
                
                /*if(isset($request->image_one)){
                    $imageone = $request->image_one;
                    $pimage_name = time().$imageone->getClientOriginalName();
                    $imageone->move(public_path('/images/category/'),$pimage_name);
                    $category->image= 'public/images/category/'.$pimage_name;
                }*/
                
                
                $category->updated_at=Date('Y-m-d h:i:s');
                $category->save();
                
                
            }else{
                
                $category=new Brand();
                StoreContext::assignToModel($category);
                $category->created_at=Date('Y-m-d h:i:s');
                $category->name=$request->name;
                $category->status=$request->status;
                $category->title=$request->title;
                $category->description=$request->description;
                $tags = preg_replace('/\s+/', '-', $request->tags);
               
                $category->keywords=$tags; 
               
                // if(isset($request->image_one)){
                //     $imageone = $request->image_one;
                //     $pimage_name = time().$imageone->getClientOriginalName();
                //     $imageone->move(public_path('/images/category/'),$pimage_name);
                //     $category->image= 'public/images/category/'.$pimage_name;
                // }
                $category->slug= strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->name)));
                $category->updated_at=Date('Y-m-d h:i:s');
                $category->save();
                
                
                
                
            }
            
            
           
            return redirect(route('admins.brand'))->with([
                'msg'=>'Brand Saved Successfully',
                'msg_type'=>'success',
            ]);
        }
        $categories = Brand::orderBy('id', 'desc')->get();
        return view('admins.brand',compact('categories','edit','seo'));
    }
    public function category(Request $request,$id=0,$delete=null){
        $edit=null;
        $seo = null;
        if(isset($delete) && $id>0){
            $del = Category::find($id);
            $this->assertStoreOwns($del);
            if ($del) { $del->delete(); }
            return redirect(route('admins.category'))->with([
                'msg'=>'Category Deleted Successfully',
                'msg_type'=>'success',
            ]);
        }
        if($id>0 && !isset($delete)){
            $seo = CategoriesToMeta::where('cid', '=',  $id)->first();
            $edit=$this->assertStoreOwns(Category::find($id));
        }
        if ($request->isMethod('post')) {
            $request->validate([
                'name'=>'required|unique:categories,name,'.$request->hidden_id,
            ]);

            if($request->has('hidden_id')){
                
                $category=$this->assertStoreOwns(Category::find($request->hidden_id));
                $category->name=$request->name;
                $category->status=$request->status;
                $category->short_description=$request->short_description;
                $category->sort=$request->sort ?? 0;
                $category->home_sort=$request->home_sort ?? 0;
                $category->slug= strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->name)));
                /*if(isset($request->image_one)){
                    $imageone = $request->image_one;
                    $pimage_name = time().$imageone->getClientOriginalName();
                    $imageone->move(public_path('/images/category/'),$pimage_name);
                    $category->image= 'public/images/category/'.$pimage_name;
                }*/
                
                if ($request->hasFile('image_one')) {
                    $file = $request->file('image_one');
                    $extension = $file->getClientOriginalExtension();
                    $extension = 'webp';
                    // Rename the file with a new extension
                    $name = time() . '.' . $extension;
                
                    // Move the uploaded file to the desired directory
                    $file->move(public_path('/images/category/'), $name);
                
                    $category['image'] = 'public/images/category/'.$name;
                }
                
                $category->updated_at=Date('Y-m-d h:i:s');
                $category->save();
                
                $seo = CategoriesToMeta::where('cid', '=',  $request->hidden_id)->first();
            
                if($seo){
                    $seo->title = $request->stitle;
                    $seo->description = $request->sdescription;
                    $seo->keywords = $request->skeywords;
                    $seo->save();
                }else{
                    $categoriesmeta = new CategoriesToMeta();
                
                    $categoriesmeta->cid = $category->id;
                    $categoriesmeta->title = $request->stitle;
                    $categoriesmeta->description = $request->sdescription;
                    $categoriesmeta->keywords = $request->skeywords;
                    $categoriesmeta->save();
                }
                
                
            }else{
                
                $category=new Category();
                StoreContext::assignToModel($category);
                $category->created_at=Date('Y-m-d h:i:s');
                $category->name=$request->name;
                $category->status=$request->status;
                $category->short_description=$request->short_description;
                $category->sort=$request->sort ?? 0;
                $category->home_sort=$request->home_sort ?? 0;
                $category->sort=$request->sort ?? 0;
                // if(isset($request->image_one)){
                //     $imageone = $request->image_one;
                //     $pimage_name = time().$imageone->getClientOriginalName();
                //     $imageone->move(public_path('/images/category/'),$pimage_name);
                //     $category->image= 'public/images/category/'.$pimage_name;
                // }
                if ($request->hasFile('image_one')) {
                    $file = $request->file('image_one');
                    $extension = $file->getClientOriginalExtension();
                    $extension = 'webp';
                    // Rename the file with a new extension
                    $name = time() . '.' . $extension;
                
                    // Move the uploaded file to the desired directory
                    $file->move(public_path('/images/category/'), $name);
                
                    $category['image'] = 'public/images/category/'.$name;
                }
                $category->slug= strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->name)));
                $category->updated_at=Date('Y-m-d h:i:s');
                $category->save();
                
                $categoriesmeta = new CategoriesToMeta();
            
                $categoriesmeta->cid = $category->id;
                $categoriesmeta->title = $request->stitle;
                $categoriesmeta->description = $request->sdescription;
                $categoriesmeta->keywords = $request->skeywords;
                $categoriesmeta->save();
                
                
            }
            
            
           
            return redirect(route('admins.category'))->with([
                'msg'=>'Category Saved Successfully',
                'msg_type'=>'success',
            ]);
        }
        $categories=Category::orderBy('sort', 'ASC')->orderBy('id', 'DESC')->get();
        return view('admins.category',compact('categories','edit','seo'));
    }
    
    public function colors(Request $request,$id=0,$delete=null){
        $edit=null;
        if(isset($delete) && $id>0){
            Colors::find($id)->delete();
            return redirect(route('admins.colors'))->with([
                'msg'=>'Colors Deleted Successfully',
                'msg_type'=>'success',
            ]);
        }
        if($id>0 && !isset($delete)){
            $edit=Colors::find($id);
        }
        if ($request->isMethod('post')) {

            $request->validate([
                'name'=>'required|unique:categories,name,'.$request->hidden_id,
            ]);

            if($request->has('hidden_id')){
                $category=Colors::find($request->hidden_id);$category->name=$request->name;
                $category->updated_at=Date('Y-m-d h:i:s');
                $category->save();
                
            }else{
                
                $category=new Colors();
                $category->created_at=Date('Y-m-d h:i:s');
                $category->name=$request->name;
                $category->updated_at=Date('Y-m-d h:i:s');
                $category->save();
                
            }
            
            
           
            return redirect(route('admins.colors'))->with([
                'msg'=>'colors Saved Successfully',
                'msg_type'=>'success',
            ]);
        }
        $categories=Colors::all();
        return view('admins.colors',compact('categories','edit'));
    }
    
    public function shap(Request $request,$id=0,$delete=null){
        $edit=null;
        if(isset($delete) && $id>0){
            Shap::find($id)->delete();
            return redirect(route('admins.shap'))->with([
                'msg'=>'Shap Deleted Successfully',
                'msg_type'=>'success',
            ]);
        }
        if($id>0 && !isset($delete)){
            $edit=Shap::find($id);
        }
        if ($request->isMethod('post')) {

            $request->validate([
                'name'=>'required|unique:categories,name,'.$request->hidden_id,
            ]);

            if($request->has('hidden_id')){
                $category=Shap::find($request->hidden_id);$category->name=$request->name;
                $category->updated_at=Date('Y-m-d h:i:s');
                $category->save();
                
            }else{
                
                $category=new Shap();
                $category->created_at=Date('Y-m-d h:i:s');
                $category->name=$request->name;
                $category->updated_at=Date('Y-m-d h:i:s');
                $category->save();
                
            }
            
            
           
            return redirect(route('admins.shap'))->with([
                'msg'=>'Shap Saved Successfully',
                'msg_type'=>'success',
            ]);
        }
        $categories=Shap::all();
        return view('admins.shap',compact('categories','edit'));
    }
    
    public function home_cats(Request $request,$id=0,$delete=null){
        $edit=null;
        if(isset($delete) && $id>0){
            DB::table('home_cats')->delete($id);
            return redirect(route('admins.home_cats'))->with([
                'msg'=>'Category Deleted Successfully',
                'msg_type'=>'success',
            ]);
        }
        if($id>0 && !isset($delete)){
            $edit=DB::table('home_cats')->where('home_cats.id', $id)->first();
            // dd($edit);
        }
        if ($request->isMethod('post')) {

            $request->validate([
                'name'=>'required|unique:categories,name,'.$request->hidden_id,
            ]);

            if($request->has('hidden_id')){
                $in = array(
                        'updated_at' => Date('Y-m-d h:i:s'),
                        'name' => $request->name,
                        'status' => $request->status,
                        'sort' => $request->sort,
                        );
                $id = DB::table('home_cats')->where('id', $request->hidden_id)->update($in);
                
            }else{
                $in = array(
                        'updated_at' => Date('Y-m-d h:i:s'),
                        'created_at' => Date('Y-m-d h:i:s'),
                        'name' => $request->name,
                        'status' => $request->status,
                        'sort' => $request->sort,
                        );
                $id = DB::table('home_cats')->insert($in);;
                
            }
            
            
           
            return redirect(route('admins.home_cats'))->with([
                'msg'=>'Category Saved Successfully',
                'msg_type'=>'success',
            ]);
        }
        $categories=DB::table('home_cats')->get();;
        return view('admins.home_cats',compact('categories','edit'));
    }
    
    public function clarity(Request $request,$id=0,$delete=null){
        $edit=null;
        if(isset($delete) && $id>0){
            DB::table('calarity')->delete($id);
            return redirect(route('admins.clarity'))->with([
                'msg'=>'Clarity Deleted Successfully',
                'msg_type'=>'success',
            ]);
        }
        if($id>0 && !isset($delete)){
            $edit=DB::table('calarity')->where('calarity.id', $id)->first();
            // dd($edit);
        }
        if ($request->isMethod('post')) {

            $request->validate([
                'name'=>'required|unique:categories,name,'.$request->hidden_id,
            ]);

            if($request->has('hidden_id')){
                $in = array(
                        'updated_at' => Date('Y-m-d h:i:s'),
                        'name' => $request->name,
                        );
                $id = DB::table('calarity')->where('id', $request->hidden_id)->update($in);
                
            }else{
                $in = array(
                        'updated_at' => Date('Y-m-d h:i:s'),
                        'created_at' => Date('Y-m-d h:i:s'),
                        'name' => $request->name,
                        );
                $id = DB::table('calarity')->insert($in);;
                
            }
            
            
           
            return redirect(route('admins.clarity'))->with([
                'msg'=>'Size Saved Successfully',
                'msg_type'=>'success',
            ]);
        }
        $categories=DB::table('calarity')->get();;
        return view('admins.calarity',compact('categories','edit'));
    }
    public function size(Request $request,$id=0,$delete=null){
        $edit=null;
        if(isset($delete) && $id>0){
            Size::find($id)->delete();
            return redirect(route('admins.size'))->with([
                'msg'=>'Size Deleted Successfully',
                'msg_type'=>'success',
            ]);
        }
        if($id>0 && !isset($delete)){
            $edit=Size::find($id);
        }
        if ($request->isMethod('post')) {

            $request->validate([
                'name'=>'required|unique:categories,name,'.$request->hidden_id,
            ]);

            if($request->has('hidden_id')){
                $category=Size::find($request->hidden_id);$category->name=$request->name;
                $category->updated_at=Date('Y-m-d h:i:s');
                $category->save();
                
            }else{
                
                $category=new Size();
                $category->created_at=Date('Y-m-d h:i:s');
                $category->name=$request->name;
                $category->updated_at=Date('Y-m-d h:i:s');
                $category->save();
                
            }
            
            
           
            return redirect(route('admins.size'))->with([
                'msg'=>'Size Saved Successfully',
                'msg_type'=>'success',
            ]);
        }
        $categories=Size::all();
        return view('admins.size',compact('categories','edit'));
    }
    
    public function subcategory(Request $request,$id=0,$delete=null)
    {
        $edit=null;
        $seo = null;
        if(isset($delete) && $id>0){
            SubCategory::find($id)->delete();
            return redirect(route('admins.subcategory'))->with([
                'msg'=>'SubCategory Deleted Successfully',
                'msg_type'=>'success',
            ]);
        }
        if($id>0 && !isset($delete)){
             $seo = CategoriesToMeta::where('id', '=',  $id)->first();
            $edit=SubCategory::find($id);
        }
        if ($request->isMethod('post')) {

            $request->validate([
                'name'=>'required|unique:sub_categories,name,' . $request->hidden_id,
                'category_id'=>'required',
            ],['category_id.required'=> 'Category Must be Required']);

            $request->validate([
                'name'=>'required',
                'category_id'=>'required',
            ]);

            if($request->has('hidden_id')){
              
                $category=SubCategory::find($request->hidden_id);
                $seo = CategoriesToMeta::where('id', '=',  $request->hidden_id)->first(); 
                
                 
            }else{
                $category=new SubCategory();
                $category->created_at=Date('Y-m-d h:i:s');
            }
            
            
           
            $category->name=$request->name;
            $category->slug=strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->name)));
            $category->category_id=$request->category_id;
            $category->updated_at=Date('Y-m-d h:i:s');
            $category->save();
            
          
             
            
                
            return redirect(route('admins.subcategory'))->with([
                'msg'=>'SubCategory Saved Successfully',
                'msg_type'=>'success',
            ]);
        }
        $categories=Category::all();
        $sub_categories=$users = SubCategory::leftJoin('categories', 'categories.id', '=', 'sub_categories.category_id')
        ->select('sub_categories.*', 'categories.name AS parent_category')
        ->get();
        return view('admins.subcategory',compact('categories','sub_categories','edit' , 'seo'));
    }
    
    public function news_letters(Request $request,$id=0,$delete=null)
    {
        if(isset($delete) && $id>0){
            Newsletter::find($id)->delete();
            return redirect(route('admins.news_letters'))->with([
                'msg'=>'Subcriber Deleted Successfully',
                'msg_type'=>'success',
            ]);
        }
        $news_letters=Newsletter::get();
        return view('admins.news_letters',compact('news_letters'));
    }
    
    public function coupon(Request $request,$id=0,$delete=null){
        $edit=null;
        if(isset($delete) && $id>0){
            Coupon::find($id)->delete();
            return redirect(route('admins.coupon'))->with([
                'msg'=>'Coupon Deleted Successfully',
                'msg_type'=>'success',
            ]);
        }
        if($id>0 && !isset($delete)){
            $edit=Coupon::find($id);
        }
        if ($request->isMethod('post')) {

            $request->validate([
                'code'=>'required|unique:coupons,code,'.$request->hidden_id,
                'discount'=>'required',
            ]);

            if($request->has('hidden_id')){
                $coupon=Coupon::find($request->hidden_id);
            }else{
                $coupon=new Coupon();
                $coupon->created_at=Date('Y-m-d h:i:s');
            }
            $coupon->code=$request->code;
            $coupon->discount=$request->discount;
            $coupon->updated_at=Date('Y-m-d h:i:s');
            $coupon->save();
            return redirect(route('admins.coupon'))->with([
                'msg'=>'Coupon Saved Successfully',
                'msg_type'=>'success',
            ]);
        }
        $coupons=Coupon::all();
        return view('admins.coupons',compact('coupons','edit'));
    }
    
    public function products(Request $request,$id=0,$delete=null){
        
        $products=Product::select('products.*')->orderBy('products.id','DESC')->get();
        return view('admins.products',compact('products'));
    }
    
    
    
     public function products_api()
{
    
    $start = $_GET['start'];
    $length = $_GET['length'];
    $srch = $_GET['search']['value'];

$query = Product::select('products.*')->orderBy('products.id','DESC');

if (!empty($srch)) {
    $query->where(function($q) use ($srch) {
        $q->where('product_name', 'like', '%' . $srch . '%')
          ->orWhere('product_code', 'like', '%' . $srch . '%')
          ->orWhere('product_quantity', 'like', '%' . $srch . '%');
          
    });
}


$tot = $query->get();

$ret = $query->offset($start)->limit($length)->get();

    $data = array();
    $i = 0;
    foreach($ret as $k => $v)
    {
        $i++;
        $st = '<div class="switch">
        <div class="onoffswitch">
            <input type="checkbox" name="product_status" data-id="' . $v->id . '" ' . ($v->status == 1 ? 'checked' : '') . ' class="onoffswitch-checkbox" id="example-' . $v->id . '">
            <label class="onoffswitch-label" for="example-' . $v->id . '">
                <span class="onoffswitch-inner"></span>
                <span class="onoffswitch-switch"></span>
            </label>
        </div>
    </div>';
      $action = '<a href="' . route('admins.product_form', $v->id) . '" class="btn btn-success btn-sm"><i class="fa fa-edit"></i>&nbsp;Edit</a>' .
          '<a  class="btn btn-danger btn-sm"  onclick="deleteProduct(' . $v->id . ', \'' . route('admins.product_delete', ['id' => $v->id]) . '\')"><i class="fa fa-times"></i>&nbsp;Delete</a>';


          $category_id = Category::where(['id'=>$v->category_id])->get();   
        $category_name = (isset($category_id[0]->name)?$category_id[0]->name:'');
        $image = '<img src="' . asset($v->image_one) . '" alt="">';

         

        $data[] = array($start + $i, $image, $v->product_code, $v->product_name, $category_name,$v->product_quantity, $st,$action);
    }
    
    $r = array('draw' => $_GET['draw'], 'recordsTotal' => count($tot), 'recordsFiltered' => count($tot), 'data' => $data);
    echo json_encode($r);
    exit();
}
    
    public function review(Request $request,$id=0,$delete=null){
        $reviews = DB::table('rating')->orderBy('id','DESC')->get();
        $pendingreviews = Rating::where('status', 0)->get();
        $r = DB::table('rating')->update(array('is_read'=>1));
        return view('admins.review',compact('reviews' , 'pendingreviews'));
    }
    
    public function pages(Request $request,$id=0,$delete=null){
        $pages = Pages::all();
        return view('admins.pages',compact('pages'));
    }
    public function msections(Request $request,$id=0,$delete=null){
        $pages = DB::table('sections')->get();
        foreach($pages as $k => $v)
        {
            $page = DB::table('pages')->where('pages.id', $v->menu)->first();
            if(isset($page->name))
            {
            $v->menu = $page->name;
            }
            else
            {
                $v->menu = ' ';
            }
        }
        return view('admins.msections',compact('pages'));
    }
    
    public function setting(Request $request,$id=0,$delete=null){
        if ($request->isMethod('post')){
            // dd($request);
            if($request->hidden_id){
                
                $setting=Setting::find($request->hidden_id);
                
                // if(isset($request->logo1)){
                //     $imageone = $request->logo1;
                //     $pimage_name = time().$imageone->getClientOriginalName();
                //     $imageone->move(public_path('/images/'),$pimage_name);
                //     $setting->logo1 = 'public/images/'.$pimage_name;
                // }
                
                if ($request->hasFile('logo1')) {
                    $file = $request->file('logo1');
                    $extension = strtolower($file->getClientOriginalExtension() ?: 'webp');
                    $name = time() . '_favicon.' . $extension;
                    $file->move(public_path('images'), $name);
                    $setting->logo1 = 'public/images/' . $name;
                }

               if ($request->hasFile('wlogo')) {
                    $file = $request->file('wlogo');
                    $extension = strtolower($file->getClientOriginalExtension() ?: 'webp');
                    $name = time() . '_wlogo.' . $extension;
                    $file->move(public_path('images'), $name);
                    $setting->wlogo = 'public/images/' . $name;
                }

               if ($request->hasFile('logo')) {
                    $file = $request->file('logo');
                    $extension = strtolower($file->getClientOriginalExtension() ?: 'webp');
                    $name = time() . '_logo.' . $extension;
                    $file->move(public_path('images'), $name);
                    $setting->logo = 'public/images/' . $name;
                }
                
                // if(isset($request->homepage_image_one)){
                //     $imageone = $request->homepage_image_one;
                //     $pimage_name = time().$imageone->getClientOriginalName();
                //     $imageone->move(public_path('/images/'),$pimage_name);
                //     $setting->homepage_image_one = 'public/images/'.$pimage_name;
                // }
                
                if ($request->hasFile('homepage_image_one')) {
                    $file = $request->file('homepage_image_one');
                    $extension = $file->getClientOriginalExtension();
                    $extension = 'webp';
                    // Rename the file with a new extension
                    $name = time() . '.' . $extension;
                
                    // Move the uploaded file to the desired directory
                    $file->move(public_path('/images/'), $name);
                
                    $setting['homepage_image_one'] = 'public/images/'.$name;
                }
                
                // if(isset($request->homepage_image_two)){
                //     $imageone = $request->homepage_image_two;
                //     $pimage_name = time().$imageone->getClientOriginalName();
                //     $imageone->move(public_path('/images/'),$pimage_name);
                //     $setting->homepage_image_two = 'public/images/'.$pimage_name;
                // }
                
                if ($request->hasFile('homepage_image_two')) {
                    $file = $request->file('homepage_image_two');
                    $extension = $file->getClientOriginalExtension();
                    $extension = 'webp';
                    // Rename the file with a new extension
                    $name = time() . '.' . $extension;
                
                    // Move the uploaded file to the desired directory
                    $file->move(public_path('/images/'), $name);
                
                    $setting['homepage_image_two'] = 'public/images/'.$name;
                }
                
                // if(isset($request->homepage_image_3)){
                //     $imageone = $request->homepage_image_3;
                //     $pimage_name = time().$imageone->getClientOriginalName();
                //     $imageone->move(public_path('/images/'),$pimage_name);
                //     $setting->homepage_image_3 = 'public/images/'.$pimage_name;
                // } 
                
                if ($request->hasFile('homepage_image_3')) {
                    $file = $request->file('homepage_image_3');
                    $extension = $file->getClientOriginalExtension();
                    $extension = 'webp';
                    // Rename the file with a new extension
                    $name = time() . '.' . $extension;
                
                    // Move the uploaded file to the desired directory
                    $file->move(public_path('/images/'), $name);
                
                    $setting['homepage_image_3'] = 'public/images/'.$name;
                }
                
                // if(isset($request->homepage_image_4)){
                //     $imageone = $request->homepage_image_4;
                //     $pimage_name = time().$imageone->getClientOriginalName();
                //     $imageone->move(public_path('/images/'),$pimage_name);
                //     $setting->homepage_image_4 = 'public/images/'.$pimage_name;
                // }
                
                if ($request->hasFile('homepage_image_4')) {
                    $file = $request->file('homepage_image_4');
                    $extension = $file->getClientOriginalExtension();
                    $extension = 'webp';
                    // Rename the file with a new extension
                    $name = time() . '.' . $extension;
                
                    // Move the uploaded file to the desired directory
                    $file->move(public_path('/images/'), $name);
                
                    $setting['homepage_image_4'] = 'public/images/'.$name;
                }
                
                if ($request->hasFile('homepage_image_5')) {
                    $file = $request->file('homepage_image_5');
                    $extension = $file->getClientOriginalExtension();
                    $extension = 'webp';
                    // Rename the file with a new extension
                    $name = time() . '.' . $extension;
                
                    // Move the uploaded file to the desired directory
                    $file->move(public_path('/images/'), $name);
                
                    $setting['homepage_image_5'] = 'public/images/'.$name;
                }
                
                if ($request->hasFile('homepage_image_6')) {
                    $file = $request->file('homepage_image_6');
                    $extension = $file->getClientOriginalExtension();
                    $extension = 'webp';
                    // Rename the file with a new extension
                    $name = time() . '.' . $extension;
                
                    // Move the uploaded file to the desired directory
                    $file->move(public_path('/images/'), $name);
                
                    $setting['homepage_image_6'] = 'public/images/'.$name;
                }
                
                
               
                $setting->welcome_message=$request->welcome_message;
                $setting->email=$request->email;
                $setting->phone=$request->phone;
                $setting->whatsapp=$request->whatsapp;
                $setting->track_order_link=$request->track_order_link;
                $setting->about_us_link=$request->about_us_link;
                $setting->contact_us_link=$request->contact_us_link;
                $setting->site_title=$request->site_title;
                $setting->title=$request->title;
                $setting->description=$request->description;
                $setting->keywords=$request->keywords;
                 
                
               
                $setting->phonetwo=$request->phonetwo;
                $setting->instagram=$request->instagram;
                $setting->dir_link=$request->dir_link;
                $setting->facebook=$request->facebook;
                $setting->twitter=$request->twitter;
                $setting->tiktok=$request->tiktok;
                $setting->pinterest=$request->pinterest;
                $setting->youtube=$request->youtube;
                
                $setting->homepage_footer=$request->homepage_footer;
                $setting->homepage_img1d=$request->homepage_img1d;
                $setting->homepage_img2d=$request->homepage_img2d;
                $setting->homepage_img3d=$request->homepage_img3d;
                $setting->homepage_img4d=$request->homepage_img4d;
                $setting->homepage_img5d=$request->homepage_img5d;
                $setting->homepage_img6d=$request->homepage_img6d;
                $setting->shipping_charges=$request->shipping_charges;
                $setting->footer_text=$request->footer;
                $setting->news_text=$request->news_text;
                $setting->primary_color=$request->primary_color;
                $setting->navigation_color=$request->navigation_color;
                $setting->button_color=$request->button_color;
                $setting->theme_style=$request->theme_style;
                $setting->active_theme = $request->active_theme ?? 2;
                if (Schema::hasColumn('setting', 'home_layout')) {
                    $allowedHomes = \App\Support\ThemeRegistry::homeIds((int) $setting->active_theme);
                    $setting->home_layout = in_array((int) $request->home_layout, $allowedHomes, true)
                        ? (int) $request->home_layout
                        : ($allowedHomes[0] ?? 1);
                }
                $setting->head_scripts=$request->head_scripts;
                $setting->save();

                $currentStore = StoreContext::store() ?: \App\Support\CurrentStore::get();
                if ($currentStore && in_array((int) $setting->active_theme, storefront_theme_ids(), true)) {
                    $currentStore->active_theme = (int) $setting->active_theme;
                    $currentStore->save();
                }

                return redirect('/admin/setting')->with([
                    'msg' => 'Settings updated successfully.',
                    'msg_type' => 'success',
                ]);
            }
        }
        
        $storeId = StoreContext::id();
        if ($storeId && Schema::hasColumn('setting', 'store_id')) {
            $edit = Setting::where('store_id', $storeId)->orderBy('id')->first();
        }
        if (empty($edit)) {
            $edit = Setting::where('id', '=', '1')->first();
        }
        $themes = \App\Support\ThemeRegistry::all();
        return view('admins.setting', compact('edit', 'themes'));
    }

    public function themeSettings(Request $request)
    {
        $store = StoreContext::store();
        if (!$store) {
            return redirect('/admin/setting')->with([
                'msg' => 'No store is assigned to this account.',
                'msg_type' => 'danger',
            ]);
        }

        if ($request->isMethod('post')) {
            \App\Support\ThemeSettings::save((array) $request->input('ts', []), $store);
            return redirect('/admin/theme-settings')->with([
                'msg' => 'Theme settings saved for this store only.',
                'msg_type' => 'success',
            ]);
        }

        $themeId = \App\Support\ThemeSettings::assignedThemeId($store);
        $schema = \App\Support\ThemeSettings::schema($store);
        $values = \App\Support\ThemeSettings::values($store);
        $schemaPath = theme_path($themeId, 'settings.json');

        return view('admins.theme_settings', compact('store', 'themeId', 'schema', 'values', 'schemaPath'));
    }
    
     public function learn_setting(Request $request,$id=0,$delete=null){
        if ($request->isMethod('post')){
            // dd($request);
            if($request->hidden_id){
                
                $setting=Learn_setting::find($request->hidden_id);
                
                
                
                if(isset($request->learn_img_1)){
                    $imageone = $request->learn_img_1;
                    $pimage_name = time().$imageone->getClientOriginalName();
                    $imageone->move(public_path('/images/'),$pimage_name);
                    $setting->learn_img_1 = 'public/images/'.$pimage_name;
                }
                
                if(isset($request->learn_img_2)){
                    $imageone = $request->learn_img_2;
                    $pimage_name = time().$imageone->getClientOriginalName();
                    $imageone->move(public_path('/images/'),$pimage_name);
                    $setting->learn_img_2 = 'public/images/'.$pimage_name;
                }
                
                if(isset($request->learn_img_3)){
                    $imageone = $request->learn_img_3;
                    $pimage_name = time().$imageone->getClientOriginalName();
                    $imageone->move(public_path('/images/'),$pimage_name);
                    $setting->learn_img_3 = 'public/images/'.$pimage_name;
                }
                
              
             
                $setting->p_1=$request->p_1;
                $setting->p2=$request->p2;
                $setting->p3=$request->p3;
                $setting->p4=$request->p4;
                $setting->p5=$request->p5;
                $setting->p6=$request->p6;
               
                $setting->save();
            }
        }
        
        $edit = Learn_setting::where('id', '=',  '1')->first();
        return view('admins.learn_setting',compact('edit'));
    }
    
    public function orders(Request $request){
        $ordersQuery = DB::table('orders')->where('status',1)->orderBy('id','DESC');
        if (Schema::hasColumn('orders', 'store_id') && StoreContext::id()) {
            $ordersQuery->where('store_id', StoreContext::id());
        }
        $orders = $ordersQuery->get();
        $mark = DB::table('orders');
        if (Schema::hasColumn('orders', 'store_id') && StoreContext::id()) {
            $mark->where('store_id', StoreContext::id());
        }
        $mark->update(array('is_read'=>1));
        return view('admins.orders',compact('orders'));
        
    }
    
    
    
    public function delete_order(Request $request){
        $ids = $request->input('id', []);

        Order::whereIn('id', $ids)->delete();
        return redirect()->back()->with([
            'msg'=>'Selected records deleted successfully.',
            'msg_type'=>'success',
        ]);

        
    }
     public function contact(Request $request){
        $orders=Contact::orderBy('id','DESC')->get();
        return view('admins.contact',compact('orders'));
    }
    public function complete_orders(Request $request){
        $orders=Order::where('status','2')->orderBy('id','DESC')->get();
        return view('admins.corder',compact('orders'));
    }
    
    public function product_form(Request $request,$id=0)
    {
        //  dd($request);
        $edit=null;
        if ($request->isMethod('post')){
            
            if($request->colors !== null){
                if(count($request->colors) > 1){
                    $allcolors = implode(",",$request->colors);   
                }else{
                    // $allcolors = $request->colors;    
                    $allcolors = implode(",",$request->colors);   
                }
                
            }else{
                $allcolors = '';
            }
            $home_cats = '';
            if($request->home_cats !== null){
                if(count($request->home_cats) > 1){
                    $home_cats = implode(",",$request->home_cats);   
                }else{
                    // $allcolors = $request->colors;    
                    $home_cats = implode(",",$request->home_cats);   
                }
                
            }else{
                $allcolors = '';
            }
            
            if($request->category_id !== null){
                
                $cats = $request->category_id;
                
            }else{
                $cats = '';
            }
            
            
            if($request->hidden_id){
                // dd($_REQUEST);
                $product=$this->assertStoreOwns(Product::find($request->hidden_id));
                if (!$product) {
                    return redirect(route('admins.products'))->with(['msg'=>'Product not found','msg_type'=>'error']);
                }
                
                // if(isset($request->image_one)){
                //     $imageone = $request->image_one;
                //     $pimage_name = time().$imageone->getClientOriginalName();
                //     $imageone->move(public_path('/images/products/'),$pimage_name);
                //     $product->image_one = 'public/images/products/'.$pimage_name;
                // }
                
                if ($request->hasFile('image_one')) {
                    $file = $request->file('image_one');
                    $extension = $file->getClientOriginalExtension();
                    $extension = 'avif';
                    // Rename the file with a new extension
                    $name = time() . '.' . $extension;
                
                    // Move the uploaded file to the desired directory
                    $file->move(public_path('/images/products/'), $name);
                
                    $product['image_one'] = 'public/images/products/'.$name;
                }
                
                $product->category_id=$cats;
                $product->product_name=$request->product_name;
                $product->product_details=$request->product_details;
                $product->add_info=$request->add_info;
                
                $product->short_discriiption=$request->short_discriiption;
                $product->tags = trim((string) preg_replace('/\s*,\s*/', ',', str_replace('-', ' ', (string) $request->tags)));
                $product->shipping_price=$request->shipping_price;
                $product->slug=$request->slug;
                $product->brand=$request->brand;
                $product->ptype=$request->ptype;
                $product->subcategory_id=$request->subcategory_id;
                if(isset($home_cats))
                $product->home_cats=$home_cats;
                else
                $product->home_cats='';
                $product->selling_price=$request->selling_price;
                $product->discount_price=$request->discount_price;
                $product->product_quantity=$request->product_quantity;
                $product->size=$request->size;
                $product->made_in=$request->made_in;
                $product->main_slider=isset($request->main_slider) ? 1 : 0;
                $product->hot_deal=isset($request->hot_deal) ? 1 : 0;
                $product->New_Arrival=isset($request->New_Arrival) ? 1 : 0;
                $product->Featured=isset($request->Featured) ? 1 : 0;
                $product->best_rated=isset($request->best_rated) ? 1 : 0;
                $product->mid_slider=isset($request->mid_slider) ? 1 : 0;
                $product->hot_new=isset($request->hot_new) ? 1 : 0;
                $product->Sale=isset($request->Sale) ? 1 : 0;
                $product->status=1;
                
                // dd($request->short_discriiption);
                $product->save();
                
                // dd($request->gallary_images);
                
                 if ($request->hasFile('gallary_images')) {
                foreach ($request->file('gallary_images') as $image) {
                    $extension = $image->getClientOriginalExtension();
                    $extension = 'avif';
                    
                    $filename = time() . '_' . uniqid() . '.' . $extension;
                    
                    
                    $image->move(public_path('gallery/'), $filename);
                    
                Gallerie::create([
                    'product_id' => $request->hidden_id,
                    'photo' =>url('/').'/public/gallery/'. $filename,
                ]);
            }
                 }

                
                
                
    
                
                $webName = env('WEB_NAME', 'Store');
                $seoTitle = trim((string) $request->stitle) !== ''
                    ? $request->stitle
                    : ('Buy ' . $request->product_name . ' | ' . $webName);
                $seoDesc = trim((string) $request->sdescription) !== ''
                    ? $request->sdescription
                    : strip_tags((string) $request->short_discriiption);
                $seoKeys = trim((string) ($request->skeywords ?: $request->tags));
                $seoKeys = trim(preg_replace('/\s+/', ' ', str_replace('-', ' ', $seoKeys)));

                $seo = ProductsToMeta::where('pid', '=', $request->hidden_id)->orderByDesc('id')->first();
                if($seo !== null){
                    $seo->title = $seoTitle;
                    $seo->description = $seoDesc;
                    $seo->keywords = $seoKeys;
                    $seo->save();
                }else{
                    $seo = new ProductsToMeta;
                    $seo['title'] = $seoTitle;
                    $seo['description'] = $seoDesc;
                    $seo['keywords'] = $seoKeys;
                    $seo['pid'] = $request->hidden_id;
                    $seo->save();
                }
                
                
            }else{
                // dd($request);
                $product=new Product();
                StoreContext::assignToModel($product);
                
                // if(isset($request->image_one)){
                //     $imageone = $request->image_one;
                //     $pimage_name = time().$imageone->getClientOriginalName();
                //     $imageone->move(public_path('/images/products/'),$pimage_name);
                //     $product->image_one = 'public/images/products/'.$pimage_name;
                // }
                if ($request->hasFile('image_one')) {
                    $file = $request->file('image_one');
                    $extension = $file->getClientOriginalExtension();
                    $extension = 'webp';
                    // Rename the file with a new extension
                    $name = time() . '.' . $extension;
                
                    // Move the uploaded file to the desired directory
                    $file->move(public_path('/images/products/'), $name);
                
                    $product['image_one'] = 'public/images/products/'.$name;
                }
                if($request->category_id !== null){
                
                $cats = $request->category_id;
                
            }else{
                $cats = '';
            }
                
                $product->slug= strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->product_name)));
                
                $product->category_id=$cats;
                $product->product_name=$request->product_name;
                $product->product_details=$request->product_details;
                $product->short_discriiption=$request->short_discriiption;
                $product->add_info=$request->add_info;
                $product->tags = trim((string) preg_replace('/\s*,\s*/', ',', str_replace('-', ' ', (string) $request->tags)));
                $product->shipping_price=$request->shipping_price;
                $product->slug=$request->slug;
                $product->brand=$request->brand;
                $product->ptype=$request->ptype;
                $product->subcategory_id=$request->subcategory_id;
                if(isset($home_cats))
                $product->home_cats=$home_cats;
                else
                $product->home_cats='';
                $product->selling_price=$request->selling_price;
                $product->discount_price=$request->discount_price;
                $product->product_quantity=$request->product_quantity;
                $product->size=$request->size;
                $product->made_in=$request->made_in;
                $product->main_slider=isset($request->main_slider) ? 1 : 0;
                $product->hot_deal=isset($request->hot_deal) ? 1 : 0;
                $product->New_Arrival=isset($request->New_Arrival) ? 1 : 0;
                $product->Featured=isset($request->Featured) ? 1 : 0;
                $product->best_rated=isset($request->best_rated) ? 1 : 0;
                $product->mid_slider=isset($request->mid_slider) ? 1 : 0;
                $product->hot_new=isset($request->hot_new) ? 1 : 0;
                $product->Sale=isset($request->Sale) ? 1 : 0;
                $product->status=1;
                
                
                $product->save();
    
                $lastid = $product->id;
                if ($request->hasFile('gallary_images')) {
                foreach ($request->file('gallary_images') as $image) {
                    $extension = $image->getClientOriginalExtension();
                    $extension = 'avif';
                    
                    $filename = time() . '_' . uniqid() . '.' . $extension;
                    
                    
                    $image->move(public_path('gallery/'), $filename);
                    
                Gallerie::create([
                    'product_id' => $lastid,
                    'photo' =>url('/').'/public/gallery/'. $filename,
                ]);
            }
                }
                
               
                $webName = env('WEB_NAME', 'Store');
                $seo = new ProductsToMeta;
                $seo['title'] = trim((string) $request->stitle) !== ''
                    ? $request->stitle
                    : ('Buy ' . $request->product_name . ' | ' . $webName);
                $seo['description'] = trim((string) $request->sdescription) !== ''
                    ? $request->sdescription
                    : strip_tags((string) $request->short_discriiption);
                $seoKeys = trim((string) ($request->skeywords ?: $request->tags));
                $seo['keywords'] = trim(preg_replace('/\s+/', ' ', str_replace('-', ' ', $seoKeys)));
                $seo['pid'] = $lastid;
                $seo->save();
                
            }
            
            
            
            
            
            

            return redirect(route('admins.products'))->with([
                'msg'=>'Product Saved Successfully',
                'msg_type'=>'success',
            ]);
        }
        $categories=Category::all();
        $scategories=SubCategory::all();
        
        $colors = Colors::all();
        $brands = Brand::all();
        $size = Size::all();
        $calarity = DB::table('calarity')->get();
        $home_cats = DB::table('home_cats')->where('status','=',1)->get();
        // dd($home_cats);
        $shap = Shap::all();
        if($id>0)
        $edit=$this->assertStoreOwns(Product::findorFail($id));
        $seo = ProductsToMeta::where('pid', $id)->orderByDesc('id')->get();
        // dd($seo);
        
        
        
        return view('admins.product_form',compact('categories','scategories','brands','edit','seo','colors','size','calarity','shap','home_cats'));
    }
    private function convertToWebp($sourcePath)
    {
        $image = imagecreatefromstring(file_get_contents($sourcePath));
        imagewebp($image, $sourcePath . '.webp', 80);
        imagedestroy($image);
    }
    
    public function page_form(Request $request,$id=0)
    {
         
         
        $edit=null;
        if ($request->isMethod('post')){
            if(isset($_REQUEST['section']))
            {
                if(empty($request->name)){
                return back()->with([
                    'msg'=>'section name is Required!',
                    'msg_type'=>'danger',
                ]);
                if(empty($request->menu)){
                return back()->with([
                    'msg'=>'section Parent menu is Required!',
                    'msg_type'=>'danger',
                ]);
            }
                
            }
            }
            else
            {
            if(empty($_REQUEST['name'])){
                
                return back()->with([
                    'msg'=>'name field Required!',
                    'msg_type'=>'danger',
                ]);
            }
            }
            
            if($request->hidden_id){
                if(isset($_REQUEST['section']))
                {
                    $in = array(
                        'name' => $request->name,
                        'position' => $request->position,
                        'status' => $request->status,
                        'menu' => $request->menu,
                        'content'=>utf8_encode($request->content),
                        'menu_type' => $request->menu_type,
                        );
                        $id = DB::table('sections')->where('id', $request->hidden_id)->update($in);
                        if($id)
                        {
                            return redirect(route('admins.msections'))->with([
                'msg'=>'Section update Successfully',
                'msg_type'=>'success',
            ]);
                        }
                }
                else
                {
                    $page = Pages::find($request->hidden_id);
                    $page->name=$request->name;
                    $page->menu_type=$request->menu_type;
                    $page->slug=$request->slug;
                    $page->content=utf8_encode($request->content);
                    $page->status=$request->status;
                    $page->position=$request->position;
                    $page->seo_title=$request->seo_title;
                    $page->seo_description=$request->seo_description;
                    // $page->seo_meta=$request->seo_meta;
                    $page->seo_keywords=$request->seo_keywords;
                    $page->section=$request->sections;
                    $page->page_image_status=$request->image_status;
                    if($request->page_image_one)
                    {
                        $file = $request->page_image_one;
                        
                        $name = time().$file->getClientOriginalName();
                        $file->move(public_path('img/slider'),$name);
                        $page['page_image'] = $name;
                    }
                    
                    $page->parent=$request->parent;
                     $page->route=$request->route;
                    $page->save();
                }
                
            }else{
                
                if(isset($_REQUEST['section']))
                {
                    $in = array(
                        'name' => $request->name,
                        'position' => $request->position,
                        'status' => $request->status,
                        'menu' => $request->menu,
                        );
                        $id = DB::table('sections')->insert($in);
                        if($id)
                        {
                            return redirect(route('admins.msections'))->with([
                'msg'=>'Section add Successfully',
                'msg_type'=>'success',
            ]);
                        }
                    
                }
                else
                {
                if(isset($_REQUEST['section']))
                {
                    return back()->with([
                        'msg'=>'name field is Required!',
                        'msg_type'=>'danger',
                    ]);
                }
                
                $page = new Pages();
                $page->name=$request->name;
                $page->slug=$request->slug;
                $page->content=$request->content;
                $page->position=$request->position;
                $page->seo_title=$request->seo_title;
                $page->seo_description=$request->seo_description;
                // $page->seo_meta=$request->seo_meta;
                $page->seo_keywords=$request->seo_keywords;
                $page->status=$request->status;
                $page->parent=$request->parent;
                $page->section=$request->sections;
                 $page->page_image_status=1;
                    if($request->page_image_one)
                    {
                        $file = $request->page_image_one;
                        
                        $name = time().$file->getClientOriginalName();
                        $file->move(public_path('img/slider'),$name);
                        $page['page_image'] = $name;
                    }
                    
                $page->route=$request->route;
                $page->save();
            }   
            }
            
            return redirect(route('admins.pages'))->with([
                'msg'=>'pages Saved Successfully',
                'msg_type'=>'success',
            ]);
        }
        $page = Pages::all();
        $sections = DB::table('sections')->get();
        
        
        if(isset($_REQUEST['section']))
        {
            if($id > 0)
            {
                $edit = DB::table('sections')->where('sections.id', $id)->first();
            }
            return view('admins.section_form',compact('page','edit'));
        }
        else{
            if($id>0){
            $edit=Pages::findorFail($id);
        }
        $categories=Category::all();
        // dd($categories);
        $products=Product::all();
            return view('admins.page_form',compact('page','edit','sections','categories','products'));
        }
        $categories=Category::all();
        $products=Product::all();
        return view('admins.page_form',compact('page','edit','categories','products'));
    }
    public function get_subCategory_html(Request $request)
    {
        $options="";
        $sub_categories=SubCategory::where('category_id',$request->category_id)->get();
        $sub_cat_id=$request->sub_category_id;
        if(count($sub_categories)>0){
            foreach($sub_categories as $sub_category)
            {
                $selected=$sub_cat_id>0 && $sub_category->id==$sub_cat_id ? "selected" : null;
                $options.='<option '.$selected.' value="'.$sub_category->id.'">'.$sub_category->name.'</option>';
            }
        }
        echo $options;
    }
    public function product_delete($id)
    {
        $product=Product::find($id);
        $img_one=public_path().'/'.$product->image_one;
        if(\File::exists($img_one)){
            \File::delete($img_one);
        } 
        $img_two=public_path().'/'.$product->image_two;
        if(\File::exists($img_two)){
            \File::delete($img_two);
        } 
        $img_three=public_path().'/'.$product->image_three;
        if(\File::exists($img_three)){
            \File::delete($img_three);
        } 
        $product->delete();
        return redirect(route('admins.products'))->with([
            'msg'=>'Product Deleted Successfully',
            'msg_type'=>'success',
        ]);
    }
    public function gallery_delete($id)
    {
        $gallery=Gallerie::find($id);
        dd($gallery);
        
        $img_one=public_path().'/'.$gallery->photo;
        if(\File::exists($img_one)){
            \File::delete($img_one);
        } 
        $gallery->delete();
        return redirect()->back()->with([
            'msg'=>'Gallery Image Deleted Successfully',
            'msg_type'=>'success',
        ]);
    }
    public function review_delete($id)
    {
        $product=Rating::find($id);
        $product->delete();
        return redirect(route('admins.review'))->with([
            'msg'=>'Review Deleted Successfully',
            'msg_type'=>'success',
        ]);
    }
    public function order_delete($id)
    {
        $deleted = DB::table('orders')->where('id', $id)->delete();

        if($deleted)
        {
        return redirect(route('admins.orders'))->with([
            'msg'=>'Order Deleted Successfully',
            'msg_type'=>'success',
        ]);
        }
        else
        {
            return redirect(route('admins.orders'))->with([
            'msg'=>'Order not found',
            'msg_type'=>'error',
        ]);
        }
    }
    public function meg_delete($id)
    {
        $product=Contact::find($id);
        $product->delete();
        return redirect(route('admins.contact'))->with([
            'msg'=>'Message Deleted Successfully',
            'msg_type'=>'success',
        ]);
    }
    
    public function order_edit($id)
    {
        $edit=Order::findorFail($id);
        // $pro = Product::findorFail($edit->pid);
        
        
        return view('admins.edit_order',compact('edit'));
    }
    private function send_grid($to,$subj, $html,$from = 'info@live-x-martplace.com')
    {
        $arrayEmails = [$to];
$emailSubject = $subj;
$emailBody = $html;
$_SESSION['email_msg'] = $html ;

Mail::send('emails.normal',
	['html' => $emailBody],
	function($message) use ($arrayEmails, $emailSubject) {
		$message->to($arrayEmails)
        ->subject($emailSubject)->setBody($_SESSION['email_msg'], 'text/html');
        unset($_SESSION['email_msg']);
	}
);
        return true;


    }
    
    public function up_delivery_status(Request $request)
    {
        $order=Order::find($request->id);
        if($request->dstatus == 1){
           $order->status = 2;
           $order->dstatus = $request->dstatus;
           $order->shipping_company = $request->company;
           $order->track_url = $request->track_url;
           $order->track_no = $request->track_no;
           $order->note = $request->note;
           $order->save();
           
           $order_data = Order::where('id',$order->id)->get();
            if($order_data){
               $data = [
                    'order' =>$order_data ,
                    
                ];
                $to_email = $order->email;
                $html = view('emails.p_order', $data)->render();
                $this->send_grid($to_email,'Proceed Order', $html,'orders@live-x-martplace.com');
            }
           return redirect(route('admins.complete_orders'))->with([
            'msg'=>'Order Updated Successfully',
            'msg_type'=>'success',
        ]);
        }
        elseif($request->dstatus == 2){
           $order->status = 2;
           $order->dstatus = $request->dstatus;
            $order->shipping_company = $request->company;
           $order->track_url = $request->track_url;
           $order->track_no = $request->track_no;
           $order->note = $request->note;
           $order->save();
           $order_data = Order::where('id',$order->id)->get();
            if($order_data){
                $to_name = $order->name;
            $to_email = $order->email;
            $data = [
                    'order' =>$order_data ,
                    
                ];
                $to_email = $order->email;
                $html = view('emails.d_order', $data)->render();
                $this->send_grid($to_email,'Order Delivery Email', $html,'orders@live-x-martplace.com');
                   return redirect(route('admins.orders'))->with([
            'msg'=>'Order Updated Successfully',
            'msg_type'=>'success',
        ]); 
            }
        
        }elseif($request->dstatus == 3){
            $order->status = 1;
           $order->dstatus = $request->dstatus;
            $order->shipping_company = $request->company;
           $order->track_url = $request->track_url;
           $order->track_no = $request->track_no;
           $order->note = $request->note;
           $order->save();
           
           return redirect(route('admins.orders'))->with([
            'msg'=>'Order Updated Successfully',
            'msg_type'=>'success',
        ]); 
        }elseif($request->dstatus == 4){
            $order->status = 2;
           $order->dstatus = $request->dstatus;
            $order->shipping_company = $request->company;
           $order->track_url = $request->track_url;
           $order->track_no = $request->track_no;
           $order->note = $request->note;
           $order->save();
           $order_data = Order::where('id',$order->id)->get();
            if($order_data){
                $to_name = $order->name;
            $to_email = $order->email;
            $data = [
                    'order' =>$order_data ,
                    
                ];
                $html = view('emails.c_order', $data)->render();
                $this->send_grid($to_email,'Order Delivery Email', $html,'orders@live-x-martplace.com');
            
            }
           return redirect(route('admins.complete_orders'))->with([
            'msg'=>'Order Updated Successfully',
            'msg_type'=>'success',
        ]);
        }else{
            $order->status = 1;
           $order->dstatus = $request->dstatus;
            $order->shipping_company = $request->company;
           $order->track_url = $request->track_url;
           $order->track_no = $request->track_no;
           $order->note = $request->note;
           $order->save();
           return redirect(route('admins.orders'))->with([
            'msg'=>'Order Updated Successfully',
            'msg_type'=>'success',
        ]);
        }
        
        
       
    }
    
    public function page_delete($id)
    {
        $product=Pages::find($id);
        $product->delete();
        return redirect(route('admins.pages'))->with([
            'msg'=>'Page Deleted Successfully',
            'msg_type'=>'success',
        ]);
    }
    public function section_delete($id)
    {
        $product=DB::table('sections')->delete($id);
        // $product->delete();
        return redirect(route('admins.msections'))->with([
            'msg'=>'Section Deleted Successfully',
            'msg_type'=>'success',
        ]);
    }
    public function update_product_status(Request $request)
    {
        $product=Product::find($request->product_id);
        $product->status=$request->Status;
        $product->save();
        return response()->json([
            'msg'=>'Product Status Updated',
            'msg_type'=>'success',
        ]);
    }
    public function update_review_status(Request $request)
    {
        $product=Rating::find($request->review_id);
        $product->status=$request->Status;
        $product->save();
        return response()->json([
            'msg'=>'Review Status Updated',
            'msg_type'=>'success',
        ]);
    }
    public function media(Request $request,$id=0,$delete=null){
        $edit=null;
        if(isset($delete) && $id>0){
            Media::find($id)->delete();
            return redirect(route('admins.media'))->with([
                'msg'=>'Media Deleted Successfully',
                'msg_type'=>'success',
            ]);
        }
        if($id>0 && !isset($delete)){
            $edit=Media::find($id);
        }
        if ($request->isMethod('post')) {


            if($request->has('hidden_id')){
                $category=Media::find($request->hidden_id);
                $category['name'] = $request->name;                
                $category['icon'] = $request->icon;                
                $category['link'] = $request->link; 
                $category->updated_at=Date('Y-m-d h:i:s');
                $category->save();
                
                
            }else{
                
                $category= new Media();
                $category->name = $request->name;                
                $category->icon = $request->icon;                
                $category->link = $request->link;
                $category->created_at=Date('Y-m-d h:i:s');
                $category->save();
                
            }
            
            return redirect(route('admins.media'))->with([
                'msg'=>'Media Saved Successfully',
                'msg_type'=>'success',
            ]);
        }
        $categories=Media::all();
        return view('admins.media',compact('categories','edit'));
    }
    public function blog_category(Request $request,$id=0,$delete=null){
        $edit=null;
        if(isset($delete) && $id>0){
            Blog_Category::find($id)->delete();
            return redirect(route('admins.blog_category'))->with([
                'msg'=>'Blog Category Deleted Successfully',
                'msg_type'=>'success',
            ]);
        }
        if($id>0 && !isset($delete)){
            $edit=Blog_Category::find($id);
        }
        if ($request->isMethod('post')) {

            // $request->validate([
            //     'title_english'=>'required|unique:post_categories,title_english,'.$request->hidden_id,
            //     'title_urdu'=>'required|unique:post_categories,title_urdu,'.$request->hidden_id,
            // ]);

            if($request->has('hidden_id')){
                $category=Blog_Category::find($request->hidden_id);
                
                $category->title_english=$request->title_english;
                $category->slug=$request->slug;
                $category->title_urdu=$request->title_urdu;
                $category->title=$request->title;
                $category->description=$request->seo_des;
                $category->keywords=$request->seo_key;
                $category->updated_at=Date('Y-m-d h:i:s');
                $category->save();
                
                
            }else{
                $category=new Blog_Category();
                $category->created_at=Date('Y-m-d h:i:s');
                $category->title_english=$request->title_english;
                $category->slug=$request->slug;
                $category->title_urdu=$request->title_urdu;
                $category->title=$request->title;
                $category->description=$request->seo_des;
                $category->keywords=$request->seo_key;
                $category->updated_at=Date('Y-m-d h:i:s');
                $category->save();
                
            }
            
            return redirect(route('admins.blog_category'))->with([
                'msg'=>'Blog Category Saved Successfully',
                'msg_type'=>'success',
            ]);
        }
        $categories=Blog_Category::all();
        return view('admins.blog_category',compact('categories','edit'));
    }
    public function blog(Request $request,$id=0,$delete=null){
        
        $edit=null;
        if(isset($delete) && $id>0){
            Blog_Post::find($id)->delete();
            return redirect(route('admins.blog'))->with([
                'msg'=>'Blog  Deleted Successfully',
                'msg_type'=>'success',
            ]);
        }
        if($id>0 && !isset($delete)){
            $edit=Blog_Post::find($id);
        }
        if ($request->isMethod('post')) {

            if($request->has('hidden_id')){
                $category=Blog_Post::find($request->hidden_id);
                
                $category->title_english=$request->title_english;
                $category->slug= strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->title_english)));
                 $category->description_english=$request->description;
                 $category->title=$request->title;
                 $category->description=$request->seo_des;
                 $category->keywords=$request->seo_key;
                //  $category->seo_meta=$request->seo_meta;
                $category->category_id=$request->category;
                // if(isset($request->image)){
                //     $imageone = $request->image;
                //     $pimage_name = time().$imageone->getClientOriginalName();
                //     $imageone->move(public_path('/images/blogs/'),$pimage_name);
                //     $category->image= 'public/images/blogs/'.$pimage_name;
                // }
                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $extension = 'webp';
                    // Rename the file with a new extension
                    $name = time() . '.' . $extension;
                
                    // Move the uploaded file to the desired directory
                    $file->move(public_path('/images/blogs/'), $name);
                
                    $category['image'] = 'public/images/blogs/'.$name;
                }
                $category->updated_at=Date('Y-m-d h:i:s');
                $category->save();
                
                
            }else{
                // dd($request);
                $category=new Blog_Post();
                $category->created_at=Date('Y-m-d h:i:s');
                $category->title_english=$request->title_english;
                $category->slug= strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->title_english)));
                $category->description_english=$request->description;
                $category->category_id=$request->category;
                $category->title=$request->title;
                 $category->description=$request->seo_des;
                 $category->keywords=$request->seo_key;
                //  $category->seo_meta=$request->seo_meta;
                // if(isset($request->image)){
                //     $imageone = $request->image;
                //     $pimage_name = time().$imageone->getClientOriginalName();
                //     $imageone->move(public_path('/images/blogs/'),$pimage_name);
                //     $category->image= 'public/images/blogs/'.$pimage_name;
                // }
                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $extension = 'webp';
                    // Rename the file with a new extension
                    $name = time() . '.' . $extension;
                
                    // Move the uploaded file to the desired directory
                    $file->move(public_path('/images/blogs/'), $name);
                
                    $category['image'] = 'public/images/blogs/'.$name;
                }
                $category->updated_at=Date('Y-m-d h:i:s');
                $category->save();
                
            }
            
            return redirect(route('admins.blog'))->with([
                'msg'=>'Blog Saved Successfully',
                'msg_type'=>'success',
            ]);
        }
        $categories=Blog_Post::all();
        return view('admins.blog_posts',compact('categories','edit'));
    }
    
    
    
       public function orders_all(Request $request,$id=0,$delete=null){
           
          
        
       if(isset($delete) && $id > 0){
    DB::table('orders')->where('id', $id)->delete();
    return redirect(route('admins.all-orders'))->with([
        'msg' => 'Order Deleted Successfully',
        'msg_type' => 'success',
    ]);
}

       
       
       $orders = DB::table('orders')->get();
        return view('admins.all-orders', ['orders' => $orders]);
         
       
    }
    
    
    
     public function orders_all_api()
{
    $status = $_GET['status'];
    $start = $_GET['start'];
    $length = $_GET['length'];
    $srch = $_GET['search']['value'];

$query = DB::table('orders')->orderBy('id', 'asc');



if (!empty($srch)) {
    $query->where(function($q) use ($srch) {
        $q->where('city', 'like', '%' . $srch . '%')
          ->orWhere('customer_name', 'like', '%' . $srch . '%')
          ->orWhere('phone', 'like', '%' . $srch . '%')
          ->orWhere('address', 'like', '%' . $srch . '%')
          ->orWhere('email', 'like', '%' . $srch . '%');
    });
}


$tot = $query->get();

$ret = $query->offset($start)->limit($length)->get();

    $data = array();
    $i = 0;
    foreach($ret as $k => $v)
    {
        $i++;
        
        if($v->status == 1){
           $st = '<a class="btn btn-success btn-block">Not Delivered</a>
               <a href="' . url('admin/mark-as-delivered/' . $v->id) . '" class="btn btn-info  btn-block">Mark as Delivered</a>
        ';  
        }else if($v->status == 2){
              $st = '<a  class="btn btn-success btn-block">Delivered</a> ';
        }
        
       
         $actions = '<a href="' . url('admin/detail/' . $v->id) . '" target="_blank" class="btn btn-info btn-block">View More</a>
                            <a href="' . url('admin/delete-orders/' . $v->id) . '" class="btn btn-danger btn-block">Delete</a>';

         

        $data[] = array($start + $i, $v->customer_name, $v->phone, $v->email, $v->city, $v->address, $st,$actions);
    }
    
    $r = array('draw' => $_GET['draw'], 'recordsTotal' => count($tot), 'recordsFiltered' => count($tot), 'data' => $data);
    echo json_encode($r);
    exit();
}
    
    
    
    public function new_orders_delete($id)
    {
        $del = DB::table('orders')->delete($id);
        if($del){
           return redirect(route('admins.all-orders'))->with([
                'msg'=>'Order Deleted Successfully',
                'msg_type'=>'success',
            ]);
        }else{
            return back()->with('message', "something went wrong");
        }
    }
    
    public function status_deliverd($id)
{
    
    $updated = DB::table('orders')
                 ->where('id', $id)
                 ->update(['status' => 2]);

    if($updated){
        return redirect(route('admins.all-orders'))->with([
            'msg' => 'Order status updated to delivered successfully',
            'msg_type' => 'success',
        ]);
    } else {
        return back()->with('message', "Something went wrong");
    }
}


public function detail($id)
{
    // Fetch the order from the database
    $order = DB::table('orders')->where('id', $id)->first();
    
    // Decode the product_detail column
    $productDetails = json_decode($order->product_detail, true);
    
    $products = [];
    foreach ($productDetails as $detail) {
        // Fetch product details from the products table
        $product = DB::table('products')->where('id', $detail['id'])->first();
        if ($product) {
            $products[] = [
                'name' => $product->product_name,
                'quantity' => $detail['qty']
            ];
        }
    }

    return view('admins.all-orders', ['Detail' => $order, 'products' => $products]);
}

    
    
    
    
    
    public function post_form(Request $request,$id=0)
    {

    }
    public function post_delete(){
        
    }

    public function slider(Request $request,$id=0,$delete=null)
    {
        $edit=null;
        if(isset($delete) && $id>0){
            $slider=Slider::find($id);
            $file_path=public_path().'/'.$slider->slider_image;
            if(\File::exists($file_path)){
                \File::delete($file_path);
            }           
            $slider->delete();
            return redirect(route('admins.slider'))->with([
                'msg'=>'Slider Deleted Successfully',
                'msg_type'=>'success',
            ]);
        }
        if($id>0 && !isset($delete)){
            $edit=Slider::find($id);
        }
        if ($request->isMethod('post')) {
            if($request->has('hidden_id')){
                $slider=Slider::find($request->hidden_id);
            }else{
                $slider=new Slider();
                $slider->created_at=Date('Y-m-d h:i:s');
            }
            $slider->p = $request->p;
            $slider->button = $request->button;
            $slider->heading = $request->heading;
            $slider->image_url = $request->image_url;
            $slider->ga_id = $request->ga_id;
            $slider->ga_name = $request->ga_name;
            $slider->title_size = $request->title_size ?: '18px';
            $slider->sort = (int) ($request->sort ?? 0);
            $slider->status = $request->has('status') ? 1 : 0;
            $slider->updated_at=Date('Y-m-d h:i:s');
            
           if ($request->hasFile('slider_image')) {
            $file = $request->file('slider_image');
            $extension = $file->getClientOriginalExtension();
            $extension = 'webp';
            // Rename the file with a new extension
            $name = time() . '.' . $extension;
        
            // Move the uploaded file to the desired directory
            $file->move(public_path('img/slider'), $name);
        
            $slider['slider_image'] = $name;
        }



            // if($request->slider_image)
            // {
            //     $file = $request->slider_image;
                
            //     $name = time().$file->getClientOriginalName();
            //     $file->move(public_path('img/slider'),$name);
            //     $slider['slider_image'] = $name;
                
                
                
            //     // if($request->hidden_id){
            //     //     $image=public_path().'/'.$slider->slider_image;
            //     //     if(\File::exists($image)){
            //     //         \File::delete($image);
            //     //     } 
            //     // }
            //     // $img_name=hexdec(uniqid()).'.'.$request->slider_image->getClientOriginalExtension();
            //     // $img_path='/images/sliders/'.$img_name;
            //     // \Image::make($request->slider_image)->save(public_path().'/images/sliders/'.$img_name);
            //     // $slider->slider_image=$img_path;
            // }
            
            $slider->save();
            return redirect(route('admins.slider'))->with([
                'msg'=>'Slider Saved Successfully',
                'msg_type'=>'success',
            ]);
        }
        $sliders=Slider::orderBy('sort')->orderBy('id')->get();
        $categories=Category::all();
        return view('admins.slider',compact('sliders','categories','edit'));
    }
    public function clients(Request $request, $id = 0, $delete = null)
    {
        $edit = null;
        if (isset($delete) && $id > 0) {
            $client = Client::find($id);
            if ($client) {
                if (!empty($client->image)) {
                    $file_path = public_path('img/clients/' . basename($client->image));
                    if (\File::exists($file_path)) {
                        \File::delete($file_path);
                    }
                }
                $client->delete();
            }
            return redirect(route('admins.clients'))->with([
                'msg' => 'Client Deleted Successfully',
                'msg_type' => 'success',
            ]);
        }
        if ($id > 0 && !isset($delete)) {
            $edit = Client::find($id);
        }
        if ($request->isMethod('post')) {
            $postTooLarge = $this->clientPostTooLargeError($request);
            if ($postTooLarge) {
                return $this->clientFormErrorRedirect($request, $postTooLarge);
            }

            $uploadError = $this->clientImageUploadError($request);
            if ($uploadError) {
                return $this->clientFormErrorRedirect($request, $uploadError);
            }

            $rules = [
                'label' => 'required|string|max:255',
                'sort' => 'nullable|integer|min:0',
            ];
            if (!$request->has('hidden_id')) {
                $rules['image'] = 'required|image|mimes:jpeg,jpg,png,gif,webp|max:10240';
            } elseif ($request->hasFile('image')) {
                $rules['image'] = 'image|mimes:jpeg,jpg,png,gif,webp|max:10240';
            }

            try {
                $request->validate($rules);
            } catch (ValidationException $e) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors($e->errors())
                    ->with([
                        'msg' => 'Validation failed: ' . collect($e->errors())->flatten()->first(),
                        'msg_type' => 'error',
                    ]);
            }

            if ($request->has('hidden_id')) {
                $client = Client::find($request->hidden_id);
                if (!$client) {
                    return redirect()->route('admins.clients')->with([
                        'msg' => 'Client not found.',
                        'msg_type' => 'error',
                    ]);
                }
            } else {
                $client = new Client();
                $client->created_at = date('Y-m-d H:i:s');
            }

            $client->label = $request->label;
            $client->sort = $request->sort ?? 0;

            if ($request->hasFile('image')) {
                $storeResult = $this->storeClientImage($request->file('image'), $client->image);
                if (empty($storeResult['name'])) {
                    return $this->clientFormErrorRedirect(
                        $request,
                        $storeResult['error'] ?? 'Could not save image.'
                    );
                }
                $client->image = $storeResult['name'];
            } elseif ($request->has('hidden_id') && $this->clientImageWasSubmittedButMissing($request)) {
                return $this->clientFormErrorRedirect(
                    $request,
                    'Image upload failed. File may be too large — increase PHP upload_max_filesize and post_max_size (max 10MB allowed).'
                );
            }

            $client->save();

            $redirectId = $client->id;
            $successMsg = 'Client Saved Successfully';
            if ($request->hasFile('image')) {
                $successMsg .= ' (image updated)';
            }

            return redirect()
                ->route('admins.clients', $redirectId > 0 ? $redirectId : null)
                ->with([
                    'msg' => $successMsg,
                    'msg_type' => 'success',
                ]);
        }
        $clients = Client::orderBy('sort', 'ASC')->orderBy('id', 'DESC')->get();
        return view('admins.clients', compact('clients', 'edit'));
    }

    private function clientFormErrorRedirect(Request $request, string $message)
    {
        $redirect = redirect()->back()->withInput()->with([
            'msg' => $message,
            'msg_type' => 'error',
        ]);

        if ($request->filled('hidden_id')) {
            $redirect = redirect()
                ->route('admins.clients', $request->hidden_id)
                ->withInput()
                ->with([
                    'msg' => $message,
                    'msg_type' => 'error',
                ]);
        }

        return $redirect;
    }

    private function clientPostTooLargeError(Request $request): ?string
    {
        $contentLength = (int) $request->server('CONTENT_LENGTH', 0);
        if ($contentLength > 0 && !$request->has('label') && !$request->hasFile('image')) {
            return 'Request too large. Increase PHP post_max_size and upload_max_filesize (try 12M or higher).';
        }

        return null;
    }

    private function clientImageWasSubmittedButMissing(Request $request): bool
    {
        $fileName = $_FILES['image']['name'] ?? '';
        return $fileName !== '' && !$request->hasFile('image');
    }

    private function clientImageUploadError(Request $request): ?string
    {
        if (!isset($_FILES['image']) || !is_array($_FILES['image'])) {
            return null;
        }

        $error = (int) ($_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE);
        $fileName = $_FILES['image']['name'] ?? '';

        if ($error === UPLOAD_ERR_NO_FILE || $fileName === '') {
            return null;
        }

        if ($error === UPLOAD_ERR_OK) {
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                return null;
            }
            return 'Image upload failed: ' . ($request->file('image') ? $request->file('image')->getErrorMessage() : 'invalid file.');
        }

        $messages = [
            UPLOAD_ERR_INI_SIZE => 'Image is too large (upload_max_filesize). Max allowed: 10MB.',
            UPLOAD_ERR_FORM_SIZE => 'Image is too large for this form.',
            UPLOAD_ERR_PARTIAL => 'Image upload was interrupted. Please try again.',
            UPLOAD_ERR_NO_TMP_DIR => 'Server temp folder missing. Contact hosting support.',
            UPLOAD_ERR_CANT_WRITE => 'Server could not write the uploaded file.',
            UPLOAD_ERR_EXTENSION => 'Server blocked this image type.',
        ];

        return $messages[$error] ?? 'Image upload failed (PHP error code ' . $error . ').';
    }

    private function storeClientImage($file, ?string $oldImage = null): array
    {
        if (!$file) {
            return ['name' => null, 'error' => 'No image file received.'];
        }

        if (!$file->isValid()) {
            return ['name' => null, 'error' => 'Invalid image: ' . $file->getErrorMessage()];
        }

        $uploadDir = public_path('img/clients');
        if (!is_dir($uploadDir) && !@mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
            return ['name' => null, 'error' => 'Cannot create folder: public/img/clients — check permissions.'];
        }

        if (!is_writable($uploadDir)) {
            return ['name' => null, 'error' => 'Folder not writable: public/img/clients — chmod 755 or 775.'];
        }

        if (!empty($oldImage)) {
            $oldPath = $uploadDir . DIRECTORY_SEPARATOR . basename($oldImage);
            if (is_file($oldPath)) {
                @unlink($oldPath);
            }
        }

        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        if (!in_array($extension, ['jpeg', 'jpg', 'png', 'gif', 'webp'], true)) {
            $extension = 'jpg';
        }
        if ($extension === 'jpeg') {
            $extension = 'jpg';
        }

        $name = time() . '_' . uniqid('', true) . '.' . $extension;

        try {
            $file->move($uploadDir, $name);
        } catch (\Exception $e) {
            return ['name' => null, 'error' => 'Could not move uploaded file: ' . $e->getMessage()];
        }

        if (!is_file($uploadDir . DIRECTORY_SEPARATOR . $name)) {
            return ['name' => null, 'error' => 'File was uploaded but not found after save.'];
        }

        return ['name' => $name, 'error' => null];
    }

    public function payment_methods(Request $request, $id = 0, $delete = null)
    {
        $edit = null;
        if (isset($delete) && $id > 0) {
            PaymentMethod::find($id)->delete();
            return redirect(route('admins.payment_methods'))->with([
                'msg' => 'Payment Method Deleted Successfully',
                'msg_type' => 'success',
            ]);
        }
        if ($id > 0 && !isset($delete)) {
            $edit = PaymentMethod::find($id);
        }
        if ($request->isMethod('post')) {
            if ($request->has('hidden_id')) {
                $item = PaymentMethod::find($request->hidden_id);
            } else {
                $item = new PaymentMethod();
                $item->created_at = date('Y-m-d h:i:s');
            }
            $item->title = $request->title;
            $item->detail = $request->detail;
            $item->sort = $request->sort ?? 0;
            $item->updated_at = date('Y-m-d h:i:s');
            $item->save();
            return redirect(route('admins.payment_methods'))->with([
                'msg' => 'Payment Method Saved Successfully',
                'msg_type' => 'success',
            ]);
        }
        $payment_methods = PaymentMethod::orderBy('sort', 'ASC')->orderBy('id', 'DESC')->get();
        return view('admins.payment_methods', compact('payment_methods', 'edit'));
    }

    public function faq(Request $request,$id=0,$delete=null)
    {
        
        
        $edit=null;
        if(isset($delete) && $id>0){
            // $slider=Faq::find($id);
           
            // $slider->delete(); 
            DB::table('pfaqs')->where('id', $id)->delete();
            return redirect(route('admins.faq'))->with([
                'msg'=>'Faq Deleted Successfully',
                'msg_type'=>'success',
            ]);
        }
        if($id>0 && !isset($delete)){
            $edit=DB::table('pfaqs')->where('id',$id)->first();
            return view('admins.faq',compact('edit'));
        }
        if ($request->isMethod('post')) {
            
            
            if($request->has('hidden_id')){
                DB::table('pfaqs')->where('id',$request->hidden_id)->update([
                       'product_id' =>  $request->product_id,         
                      'ans' =>  $request->answer,
                      'question' =>  $request->question,
                  ]);
            }else{
                $in = DB::table('pfaqs')->insert([
            'product_id' =>  $request->product_id,        
            'ans' =>  $request->answer,
            'question' =>  $request->question,
        ]);
            }
            
            return redirect(route('admins.faq'))->with([
                'msg'=>'Faq Saved Successfully',
                'msg_type'=>'success',
            ]);
        }
        // $sliders=DB::table('pfaqs')->get();
        $sliders = DB::table('pfaqs')
    ->join('products', 'pfaqs.product_id', '=', 'products.id')
    ->select('pfaqs.*', 'products.product_name')
    ->get();
        return view('admins.faq',compact('sliders','edit'));
    }

    public function show_on_home(Request $request)
    {
        $category = Category::find($request->product_id);
        if($category) {
            $category->show_on_home = $request->Status;
            $category->save();
            
            return response()->json([
                'msg' => 'Category status updated successfully',
                'msg_type' => 'success'
            ]);
        }
        
        return response()->json([
            'msg' => 'Category not found',
            'msg_type' => 'error'
        ]);
    }
}
