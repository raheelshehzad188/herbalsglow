<?php

namespace App\Models\Admins;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $table = 'setting';
    
    protected $fillable = [
        'whatsapp',
        'track_order_link',
        'about_us_link',
        'contact_us_link',
        'email',
        'phone',
        'phonetwo',
        'youtube',
        'welcome_message',
        'site_title',
        'title',
        'description',
        'keywords',
        'instagram',
        'facebook',
        'twitter',
        'tiktok',
        'pinterest',
        'logo',
        'wlogo',
        'logo1',
        'homepage_footer',
        'homepage_img1d',
        'homepage_img2d',
        'homepage_img3d',
        'homepage_img4d',
        'homepage_img5d',
        'homepage_img6d',
        'homepage_image_one',
        'homepage_image_two',
        'homepage_image_3',
        'homepage_image_4',
        'homepage_image_5',
        'homepage_image_6',
        'shipping_charges',
        'footer_text',
        'news_text',
        'dir_link',
        'primary_color',
        'navigation_color',
        'button_color',
        'theme_style',
        'active_theme',
        'head_scripts'
    ];
}
