<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admins;
use App\Http\Controllers\Front;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear-cache', function() {
     \Artisan::call('config:cache'); 
     return 'Application cache cleared';
 });
Route::get('/test',[Admins\AdminController::class,'test'])->name('test');
 
Route::get('/', function () {
    return view('welcome');
});


Route::get('/admin', function () {
    return redirect('admin/login');
});

Route::name('admins.')->prefix('/admin')->group(function () {
    
    
    Route::get('/login',[Admins\AdminController::class,'adminloginpage'])->name('login')->middleware('adminnotlogedin');
    Route::post('/login',[Admins\AdminController::class,'admin_login_submit'])->name('admin_login_submit');
    Route::get('/logout', [Admins\AdminController::class,'logout']);

    Route::middleware(['adminlogedin'])->group(function () { 

        Route::get('/dashboard',[Admins\AdminController::class,'dashboard'])->name('dashboard')->middleware('adminlogedin');
    
        Route::any('/category/{id?}/{delete?}',[Admins\AdminController::class,'category'])->name('category');
        Route::any('/colors/{id?}/{delete?}',[Admins\AdminController::class,'colors'])->name('colors');
        Route::any('/size/{id?}/{delete?}',[Admins\AdminController::class,'size'])->name('size');
        Route::any('/clarity/{id?}/{delete?}',[Admins\AdminController::class,'clarity'])->name('clarity');
        Route::any('/home_cats/{id?}/{delete?}',[Admins\AdminController::class,'home_cats'])->name('home_cats');
        Route::any('/shap/{id?}/{delete?}',[Admins\AdminController::class,'shap'])->name('shap');
        Route::any('/blog_category/{id?}/{delete?}',[Admins\AdminController::class,'blog_category'])->name('blog_category');
        Route::any('/media/{id?}/{delete?}',[Admins\AdminController::class,'media'])->name('media');
        Route::get('/blog_category',[Admins\AdminController::class,'blog_category'])->name('blog_category');
        Route::get('/blog',[Admins\AdminController::class,'blog'])->name('blog');
        Route::any('/blog/{id?}/{delete?}',[Admins\AdminController::class,'blog'])->name('blog');
    
        Route::any('/coupon/{id?}/{delete?}',[Admins\AdminController::class,'coupon'])->name('coupon');
        Route::any('/orders',[Admins\AdminController::class,'orders'])->name('orders');
        Route::any('/contact',[Admins\AdminController::class,'contact'])->name('contact');
        Route::any('/complete_orders',[Admins\AdminController::class,'complete_orders'])->name('complete_orders');
        Route::any('/brand/{id?}/{delete?}',[Admins\AdminController::class,'brand'])->name('brand');
        Route::any('/subcategory/{id?}/{delete?}',[Admins\AdminController::class,'subcategory'])->name('subcategory');
        Route::any('/news_letters/{id?}/{delete?}',[Admins\AdminController::class,'news_letters'])->name('news_letters');
        Route::get('/products',[Admins\AdminController::class,'products'])->name('products');
        Route::any('/product_form/{id?}',[Admins\AdminController::class,'product_form'])->name('product_form');
        Route::get('/pages',[Admins\AdminController::class,'pages'])->name('pages');
        Route::get('/msections',[Admins\AdminController::class,'msections'])->name('msections'); 
        Route::get('/dsection/{id?}',[Admins\AdminController::class,'dsection'])->name('dsection');
        Route::get('/setting',[Admins\AdminController::class,'setting'])->name('setting');
        Route::any('/setting/{id?}',[Admins\AdminController::class,'setting'])->name('setting');
        Route::get('/learn_setting',[Admins\AdminController::class,'learn_setting'])->name('learn_setting');
        Route::any('/learn_setting/{id?}',[Admins\AdminController::class,'learn_setting'])->name('learn_setting');
        Route::any('/page_form/{id?}',[Admins\AdminController::class,'page_form'])->name('page_form');
        // Route::any('/section_form/{id?}',[Admins\AdminController::class,' page_form'])->name('page_form'); 
        Route::get('/product/delete/{id}',[Admins\AdminController::class,'product_delete'])->name('product_delete');
        Route::get('/gallery/delete/{id}',[Admins\AdminController::class,'gallery_delete'])->name('gallery_delete');
        Route::get('/order/delete/{id}',[Admins\AdminController::class,'order_delete'])->name('order_delete');
        Route::get('/meg/delete/{id}',[Admins\AdminController::class,'meg_delete'])->name('meg_delete');
        Route::get('/order/edit/{id}',[Admins\AdminController::class,'order_edit'])->name('edit_order');
        Route::post('/order/up_delivery_status',[Admins\AdminController::class,'up_delivery_status'])->name('up_delivery_status');
        Route::get('/pages/delete/{id}',[Admins\AdminController::class,'page_delete'])->name('page_delete');
        Route::get('/sections/delete/{id}',[Admins\AdminController::class,'section_delete'])->name('section_delete');
        Route::post('/get_subCategory_html',[Admins\AdminController::class,'get_subCategory_html'])->name('get_subCategory_html');
        Route::post('/update_product_status',[Admins\AdminController::class,'update_product_status'])->name('update_product_status');
        Route::post('/update_review_status',[Admins\AdminController::class,'update_review_status'])->name('update_review_status');
        Route::any('/review',[Admins\AdminController::class,'review'])->name('review');
        Route::get('/review/delete/{id}',[Admins\AdminController::class,'review_delete'])->name('review_delete');
    
        Route::get('/posts',[Admins\AdminController::class,'posts'])->name('posts');
        Route::get('/admin',[Admins\AdminController::class,'admin'])->name('admin');
        Route::post('/update_admin',[Admins\AdminController::class,'update_admin'])->name('update_admin');
        Route::any('/delete_order',[Admins\AdminController::class,'delete_order'])->name('delete_order');
        Route::any('/post_form/{id?}',[Admins\AdminController::class,'post_form'])->name('post_form');
        Route::get('/post/delete/{id}',[Admins\AdminController::class,'post_delete'])->name('post_delete');
        
        Route::any('/slider/{id?}/{delete?}',[Admins\AdminController::class,'slider'])->name('slider');
        Route::any('/faq/{id?}/{delete?}',[Admins\AdminController::class,'faq'])->name('faq');

    });
    

    




});


Route::get('/',[Front\FrontController::class,'home'])->name('home');
Route::get('/sitemap',[Front\FrontController::class,'index'])->name('sitemap');
Route::get('/categories_sitemap',[Front\FrontController::class,'categories'])->name('categories_sitemap');
Route::get('/products_tag',[Front\FrontController::class,'products_tag'])->name('products_tag');
Route::get('/shop/{id}',[Front\FrontController::class,'shop'])->name('shop');
Route::any('/shop',[Front\FrontController::class,'shop'])->name('shop');
Route::get('/product/{id}',[Front\FrontController::class,'product_detail']);
Route::get('/blog',[Front\FrontController::class,'blogs']);
Route::get('/blog/{id}',[Front\FrontController::class,'blog_detail']);
Route::get('/category/{id}',[Front\FrontController::class,'category_detail']);
Route::get('/blog_category/{id}',[Front\FrontController::class,'blog_category']);
Route::get('/shape/{id}',[Front\FrontController::class,'shape_detail']);
Route::get('/brand/{id}',[Front\FrontController::class,'brand_detail']);
Route::get('/product-tag/{id}',[Front\FrontController::class,'tags_detail']);
Route::get('/search',[Front\FrontController::class,'search_detail']);
Route::get('/order',[Front\FrontController::class,'order']);
Route::get('/cart',[Front\FrontController::class,'cart']);
Route::get('/contact',[Front\FrontController::class,'contact']);
Route::post('cart/increment',[Front\CartController::class,'increment'] );
Route::post('cart/decrement', [Front\CartController::class,'decrement'] );
Route::post('cart/remove', [Front\CartController::class,'remove']);
Route::post('cart/clear', [Front\CartController::class,'clear']);
Route::get('login', [Front\FrontController::class,'login']);
Route::get('logout', [Front\FrontController::class,'logout']);
Route::post('register', [Front\FrontController::class,'register']);
Route::get('user_register', [Front\FrontController::class,'user_register']);
Route::post('user_login', [Front\FrontController::class,'user_login']);
Route::get('my_account', [Front\FrontController::class,'my_account']);
Route::post('user_update', [Front\FrontController::class,'user_update']);
Route::get('checkout', [Front\FrontController::class,'checkout']);
Route::get('guest_checkout', [Front\FrontController::class,'guest_checkout']);
Route::get('track_order', [Front\FrontController::class,'track_order']);

Route::get('/page/{id}',[Front\FrontController::class,'page_detail']);
Route::get('/about',[Front\FrontController::class,'about'])->name('about');
Route::get('/learn',[Front\FrontController::class,'learn'])->name('learn');
Route::get('/faq',[Front\FrontController::class,'faq'])->name('faq');
Route::post('/order',[Front\FrontController::class,'order'])->name('order');
Route::post('/cart_data',[Front\FrontController::class,'cart_data'])->name('cart_data');
Route::post('/hearder_cart',[Front\FrontController::class,'hearder_cart'])->name('hearder_cart');
Route::post('/order_submit',[Front\FrontController::class,'order_submit'])->name('order_submit');
Route::post('/instant_order',[Front\FrontController::class,'instant_order'])->name('instant_order');
Route::post('/blod_comment',[Front\FrontController::class,'blod_comment'])->name('blod_comment');
Route::post('/rating_submit',[Front\FrontController::class,'rating_submit'])->name('rating_submit');
Route::post('/faq_submit',[Front\FrontController::class,'faq_submit'])->name('faq_submit');
Route::post('/contact_us',[Front\FrontController::class,'contact_us'])->name('contact_us');
Route::post('/trackorder',[Front\FrontController::class,'trackorder'])->name('trackorder');
Route::any('/import',[Front\FrontController::class,'import'])->name('import');
Route::any('/import_tag',[Front\FrontController::class,'import_tag'])->name('import_all');
Route::any('/import_all/{id}',[Front\FrontController::class,'import_all'])->name('import_all');

Route::post('/get_selected_shap',[Front\FrontController::class,'get_selected_shap'])->name('get_selected_shap');
Route::post('/get_selected_color',[Front\FrontController::class,'get_selected_color'])->name('get_selected_color');
Route::post('/get_selected_size',[Front\FrontController::class,'get_selected_size'])->name('get_selected_size');
Route::post('/get_selected_detail',[Front\FrontController::class,'get_selected_detail'])->name('get_selected_detail');
Route::any('/get_selected_price',[Front\FrontController::class,'get_selected_price'])->name('get_selected_price');
Route::post('/cart/add', [Front\CartController::class,'add'])->name('cart');
Route::POST('/subcribe_newsletter',[Front\FrontController::class,'subcribe_newsletter'])->name('subcribe_newsletter');
Route::any('/forget_pass',[Front\FrontController::class,'forget_pass'])->name('forget_pass');




