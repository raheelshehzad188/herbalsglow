@extends('admins.master')
<style>
    label{
        text-align: left !important;
    }
    .bootstrap-tagsinput{
        width:100% !important;
    }
    /* Tab Content Styling */
    .tab-content {
        margin-top: 20px;
        min-height: 400px;
    }
    .tab-pane {
        display: none !important;
    }
    .tab-pane.active,
    .tab-pane.show {
        display: block !important;
    }
    .tab-pane.fade {
        opacity: 0;
        transition: opacity 0.15s linear;
    }
    .tab-pane.fade.active,
    .tab-pane.fade.show {
        opacity: 1;
    }
    .nav-tabs .nav-link {
        cursor: pointer;
    }
    .nav-tabs .nav-link.active {
        color: #495057;
        background-color: #fff;
        border-color: #dee2e6 #dee2e6 #fff;
    }
</style>
@section('title','Setting')

@section('setting','active')


@section('content')
<?php
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Setting Form</h5>
                </div>
                <div class="ibox-content">
                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">General</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="homepage-tab" data-toggle="tab" href="#homepage" role="tab" aria-controls="homepage" aria-selected="false">Home Page</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="social-tab" data-toggle="tab" href="#social" role="tab" aria-controls="social" aria-selected="false">Social Media</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="seo-tab" data-toggle="tab" href="#seo" role="tab" aria-controls="seo" aria-selected="false">SEO</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="theme-tab" data-toggle="tab" href="#theme" role="tab" aria-controls="theme" aria-selected="false">Theme</a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="settingsTabsContent">
                    <form action="/admin/setting" class="form-horizontal" method="post" enctype="multipart/form-data">
                        @csrf
                            
                            <!-- General Tab -->
                            <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                                <div class="row" style="margin-top: 20px;">
                                    <div class="col-md-6">
                        <div class="form-group"><label class="col-sm-12 control-label">Shipping charges:</label>
                            <div class="col-sm-12"><input type="text" value="<?php echo isset($edit->shipping_charges	) ? htmlspecialchars($edit->shipping_charges	) : null; ?>" required class="form-control" name="shipping_charges"></div>
                        </div>
                        <div class="form-group"><label class="col-sm-12 control-label">Site Title:</label>
                            <div class="col-sm-12"><input type="text" value="<?php echo isset($edit->site_title	) ? htmlspecialchars($edit->site_title	) : null; ?>" required class="form-control" name="site_title"></div>
                        </div>
                        </div>
                                    <div class="col-md-6">
                        <div class="form-group"><label class="col-sm-12 control-label">Email:</label>
                            <div class="col-sm-12"><input type="text" value="<?php echo isset($edit->email) ? htmlspecialchars($edit->email) : null; ?>" required class="form-control" name="email"></div>
                        </div>
                        <div class="form-group"><label class="col-sm-12 control-label">Phone Number:</label>
                            <div class="col-sm-12"><input type="text" value="<?php echo isset($edit->phone) ? htmlspecialchars($edit->phone) : null; ?>" required class="form-control" name="phone"></div>
                                        </div>
                                    </div>
                        </div>
                        
                                <div class="row">
                                    <div class="col-md-6">
                        <div class="form-group"><label class="col-sm-12 control-label">WhatsApp Number:</label>
                            <div class="col-sm-12"><input type="text" value="<?php echo isset($edit->whatsapp) ? htmlspecialchars($edit->whatsapp) : null; ?>" class="form-control" name="whatsapp" placeholder="03225386000"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group"><label class="col-sm-12 control-label">Direction Link:</label>
                                            <div class="col-sm-12"><input type="text" value="<?php echo isset($edit->dir_link) ? htmlspecialchars($edit->dir_link) : null; ?>" required class="form-control" name="dir_link"></div>
                                        </div>
                                    </div>
                        </div>
                        
                                <div class="row">
                                    <div class="col-md-6">
                        <div class="form-group"><label class="col-sm-12 control-label">Track Order Link:</label>
                            <div class="col-sm-12"><input type="text" value="<?php echo isset($edit->track_order_link) ? htmlspecialchars($edit->track_order_link) : null; ?>" class="form-control" name="track_order_link" placeholder="https://example.com/track"></div>
                        </div>
                                    </div>
                                    <div class="col-md-6">
                        <div class="form-group"><label class="col-sm-12 control-label">About Us Link:</label>
                            <div class="col-sm-12"><input type="text" value="<?php echo isset($edit->about_us_link) ? htmlspecialchars($edit->about_us_link) : null; ?>" class="form-control" name="about_us_link" placeholder="https://example.com/about"></div>
                                        </div>
                                    </div>
                        </div>
                        
                                <div class="row">
                                    <div class="col-md-6">
                        <div class="form-group"><label class="col-sm-12 control-label">Contact Us Link:</label>
                            <div class="col-sm-12"><input type="text" value="<?php echo isset($edit->contact_us_link) ? htmlspecialchars($edit->contact_us_link) : null; ?>" class="form-control" name="contact_us_link" placeholder="https://example.com/contact"></div>
                        </div>
                                    </div>
                                    <div class="col-md-6">
                        <div class="form-group"><label class="col-sm-12 control-label">Welcome Message:</label>
                            <div class="col-sm-12"><input type="text" value="<?php echo isset($edit->welcome_message) ? htmlspecialchars($edit->welcome_message) : 'Welcome To QuickOn.Pk Online Web Store | we offer Free Delivery over purchase of Rs. 5000 all over Pakistan.'; ?>" class="form-control" name="welcome_message" placeholder="Welcome message for header"></div>
                        </div>
                                    </div>
                        </div>
                        
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label">Favicon:</label>
                                            <div class="col-sm-12"><input type="file" onchange="readURL(this);" <?php echo isset($edit->id) ? null : "required"; ?> accept="image/png, image/gif, image/jpeg, image/webp" class="form-control" name="logo1">
                                            <img src="<?php echo isset($edit->logo1) ? asset($edit->logo1) : null; ?>"  alt="" <?php echo ($edit->logo1 != null) ? 'style="width:100px;"' : 'style="display:none;width:100px;"'; ?>></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label">White Logo:</label>
                                            <div class="col-sm-12"><input type="file" onchange="readURL(this);" <?php echo isset($edit->id) ? null : "required"; ?> accept="image/png, image/gif, image/jpeg, image/webp" class="form-control" name="wlogo">
                                            <img src="<?php echo isset($edit->logo) ? asset($edit->wlogo) : null; ?>"  alt="" <?php echo ($edit->wlogo != null) ? 'style="width:100px;"' : 'style="display:none;width:100px;"'; ?>></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label">Header Logo:</label>
                                            <div class="col-sm-12"><input type="file" onchange="readURL(this);" <?php echo isset($edit->id) ? null : "required"; ?> accept="image/png, image/gif, image/jpeg, image/webp" class="form-control" name="logo">
                                            <img src="<?php echo isset($edit->logo) ? asset($edit->logo) : null; ?>"  alt="" <?php echo ($edit->logo != null) ? 'style="width:100px;"' : 'style="display:none;width:100px;"'; ?>></div>
                        </div>
                        </div>
                        </div>
                        </div>
                        
<!-- Home Page Tab -->
        <div class="tab-pane fade" id="homepage" role="tabpanel" aria-labelledby="homepage-tab">
            <div class="row" style="margin-top: 20px;">
                <div class="col-md-6">
					<div class="form-group">
						<label class="col-sm-12 control-label">Home Image 1:</label>
						<div class="col-sm-12"><input type="file" onchange="readURL(this);" <?php echo isset($edit->id) ? null : "required"; ?> accept="image/png, image/gif, image/jpeg, image/webp" class="form-control" name="homepage_image_one">
							<img src="<?php echo isset($edit->homepage_image_one) ? asset($edit->homepage_image_one) : null; ?>"  alt="" <?php echo ($edit->homepage_image_one != null) ? 'style="width:100px;"' : 'style="display:none;width:100px;"'; ?>>
						</div>
					</div>
					<div class="form-group"><label class="col-sm-12 control-label">Home Image 1 detail:</label>
						<div class="col-sm-12">
							<textarea class="summernote" name="homepage_img1d" id="homepage_img1d" rows="5">
								<?php echo isset($edit->homepage_img1d) ? htmlspecialchars($edit->homepage_img1d) : null; ?>
							</textarea>
						</div>
					</div>
                </div>
                <div class="col-md-6">
					<div class="form-group">
						<label class="col-sm-12 control-label">Home Image 2:</label>
						<div class="col-sm-12"><input type="file" onchange="readURL(this);" <?php echo isset($edit->id) ? null : "required"; ?> accept="image/png, image/gif, image/jpeg, image/webp" class="form-control" name="homepage_image_two">
						<img src="<?php echo isset($edit->homepage_image_two) ? asset($edit->homepage_image_two) : null; ?>"  alt="" <?php echo ($edit->homepage_image_two != null) ? 'style="width:100px;"' : 'style="display:none;width:100px;"'; ?>></div>
					</div>
                    <div class="form-group"><label class="col-sm-12 control-label">Home Image 2 detail:</label>
						<div class="col-sm-12">
							<textarea class="summernote" name="homepage_img2d" id="homepage_img2d" rows="5">
								<?php echo isset($edit->homepage_img2d) ? htmlspecialchars($edit->homepage_img2d) : null; ?>
							</textarea>
                        </div>
                    </div>
                </div>
            </div>
                        
            <div class="row">
                <div class="col-md-6">
					<div class="form-group">
						<label class="col-sm-12 control-label">Home Image 3:</label>
						<div class="col-sm-12"><input type="file" onchange="readURL(this);" <?php echo isset($edit->id) ? null : "required"; ?> accept="image/png, image/gif, image/jpeg, image/webp" class="form-control" name="homepage_image_3">
						<img src="<?php echo isset($edit->homepage_image_3) ? asset($edit->homepage_image_3) : null; ?>"  alt="" <?php echo ($edit->homepage_image_3 != null) ? 'style="width:100px;"' : 'style="display:none;width:100px;"'; ?>></div>
					</div>
					<div class="form-group"><label class="col-sm-12 control-label">Home Image 3 detail:</label>
						<div class="col-sm-12">
							<textarea class="summernote" name="homepage_img3d" id="homepage_img3d" rows="5">
								<?php echo isset($edit->homepage_img3d) ? htmlspecialchars($edit->homepage_img3d) : null; ?>
							</textarea>
						</div>
					</div>
                </div>
                <div class="col-md-6">
					<div class="form-group">
						<label class="col-sm-12 control-label">Home Image 4:</label>
						<div class="col-sm-12"><input type="file" onchange="readURL(this);" <?php echo isset($edit->id) ? null : "required"; ?> accept="image/png, image/gif, image/jpeg, image/webp" class="form-control" name="homepage_image_4">
						<img src="<?php echo isset($edit->homepage_image_4) ? asset($edit->homepage_image_4) : null; ?>"  alt="" <?php echo ($edit->homepage_image_4 != null) ? 'style="width:100px;"' : 'style="display:none;width:100px;"'; ?>></div>
					</div>
                    <div class="form-group"><label class="col-sm-12 control-label">Home Image 4 detail:</label>
						<div class="col-sm-12">
							<textarea class="summernote" name="homepage_img4d" id="homepage_img4d" rows="5">
								<?php echo isset($edit->homepage_img4d) ? htmlspecialchars($edit->homepage_img4d) : null; ?>
							</textarea>
						</div>
                    </div>
                </div>
            </div>
                    
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-sm-12 control-label">Home Image 5:</label>
						<div class="col-sm-12"><input type="file" onchange="readURL(this);" <?php echo isset($edit->id) ? null : "required"; ?> accept="image/png, image/gif, image/jpeg, image/webp" class="form-control" name="homepage_image_5">
						<img src="<?php echo isset($edit->homepage_image_5) ? asset($edit->homepage_image_5) : null; ?>"  alt="" <?php echo ($edit->homepage_image_5 != null) ? 'style="width:100px;"' : 'style="display:none;width:100px;"'; ?>></div>
					</div>
					<div class="form-group"><label class="col-sm-12 control-label">Home Image 5 detail:</label>
						<div class="col-sm-12">
							<textarea class="summernote" name="homepage_img5d" id="homepage_img5d" rows="5">
								<?php echo isset($edit->homepage_img5d) ? htmlspecialchars($edit->homepage_img5d) : null; ?>
							</textarea>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="col-sm-12 control-label">Home Image 6:</label>
						<div class="col-sm-12"><input type="file" onchange="readURL(this);" <?php echo isset($edit->id) ? null : "required"; ?> accept="image/png, image/gif, image/jpeg, image/webp" class="form-control" name="homepage_image_6">
						<img src="<?php echo isset($edit->homepage_image_6) ? asset($edit->homepage_image_6) : null; ?>"  alt="" <?php echo ($edit->homepage_image_6 != null) ? 'style="width:100px;"' : 'style="display:none;width:100px;"'; ?>></div>
					</div>
					<div class="form-group"><label class="col-sm-12 control-label">Feature Products detail:</label>
						<div class="col-sm-12">
							<textarea class="summernote" name="homepage_img6d" id="homepage_img6d" rows="5">
								<?php echo isset($edit->homepage_img6d) ? htmlspecialchars($edit->homepage_img6d) : null; ?>
							</textarea>
						</div>
					</div>
				</div>
			</div>
                   
                                <div class="row">
                                    <div class="col-md-12">
                        <div class="form-group"><label class="col-sm-12 control-label">Address:</label>
                            <div class="col-sm-12">
                                <textarea class="summernote" name="homepage_footer" id="homepage_footer" rows="5">
                                    <?php echo isset($edit->homepage_footer) ? htmlspecialchars($edit->homepage_footer) : null; ?>
                                </textarea>
                            </div>
                        </div>
                        <div class="form-group"><label class="col-sm-12 control-label">Footer Text:</label>
                            <div class="col-sm-12">
                                <textarea class="summernote" name="footer" id="footer" rows="5">
                                    <?php echo isset($edit->footer_text) ? htmlspecialchars($edit->footer_text) : null; ?>
                                </textarea>
                            </div>
                        </div>
                        <div class="form-group"><label class="col-sm-12 control-label">Newsletter Text:</label>
                            <div class="col-sm-12">
                                <textarea class="summernote" name="news_text" id="news_text" rows="5">
                                    <?php echo isset($edit->news_text) ? htmlspecialchars($edit->news_text) : null; ?>
                                </textarea>
                            </div>
                        </div>
                                    </div>
                                </div>
        </div>
                            
                            <!-- Social Media Tab -->
                            <div class="tab-pane fade" id="social" role="tabpanel" aria-labelledby="social-tab">
                                <div class="row" style="margin-top: 20px;">
                                    <div class="col-md-6">
                                        <div class="form-group"><label class="col-sm-12 control-label">Instagram Link:</label>
                                            <div class="col-sm-12"><input type="text" value="<?php echo isset($edit->instagram) ? htmlspecialchars($edit->instagram) : null; ?>" required class="form-control" name="instagram"></div>
                                        </div>
                                        <div class="form-group"><label class="col-sm-12 control-label">Facebook Link:</label>
                                            <div class="col-sm-12"><input type="text" value="<?php echo isset($edit->facebook) ? htmlspecialchars($edit->facebook) : null; ?>" required class="form-control" name="facebook"></div>
                                        </div>
                                        <div class="form-group"><label class="col-sm-12 control-label">Twitter Link:</label>
                                            <div class="col-sm-12"><input type="text" value="<?php echo isset($edit->twitter) ? htmlspecialchars($edit->twitter) : null; ?>" required class="form-control" name="twitter"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group"><label class="col-sm-12 control-label">Tiktok Link:</label>
                                            <div class="col-sm-12"><input type="text" value="<?php echo isset($edit->tiktok) ? htmlspecialchars($edit->tiktok) : null; ?>" required class="form-control" name="tiktok"></div>
                                        </div>
                                        <div class="form-group"><label class="col-sm-12 control-label">Pinterest Link:</label>
                                            <div class="col-sm-12"><input type="text" value="<?php echo isset($edit->pinterest) ? htmlspecialchars($edit->pinterest) : null; ?>" required class="form-control" name="pinterest"></div>
                                        </div>
                                        <div class="form-group"><label class="col-sm-12 control-label">YouTube Link:</label>
                                            <div class="col-sm-12"><input type="text" value="<?php echo isset($edit->youtube) ? htmlspecialchars($edit->youtube) : null; ?>" required class="form-control" name="youtube"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- SEO Tab -->
                            <div class="tab-pane fade" id="seo" role="tabpanel" aria-labelledby="seo-tab">
                                <div class="row" style="margin-top: 20px;">
                                    <div class="col-md-12">
                                        <div class="form-group"><label class="col-sm-12 control-label">SEO Title:</label>
                            <div class="col-sm-12"><input type="text" value="<?php echo isset($edit->title	) ? htmlspecialchars($edit->title	) : null; ?>" required class="form-control" name="title"></div>
                        </div>
                                        <div class="form-group"><label class="col-sm-12 control-label">SEO Description:</label>
                            <div class="col-sm-12"><input type="text" value="<?php echo isset($edit->description) ? htmlspecialchars($edit->description) : null; ?>" required class="form-control" name="description"></div>
                        </div>
                                        <div class="form-group"><label class="col-sm-12 control-label">SEO Keywords:</label>
                            <div class="col-sm-12"><input type="text" value="<?php echo isset($edit->keywords) ? htmlspecialchars($edit->keywords) : null; ?>" required class="form-control" name="keywords"></div>
                                        </div>
                                        <div class="form-group"><label class="col-sm-12 control-label">Head Scripts (Google Analytics, Facebook Pixel, etc):</label>
                            <div class="col-sm-12">
                                <textarea class="form-control" name="head_scripts" rows="10" placeholder="Add your tracking scripts here (Google Analytics, Facebook Pixel, etc). These will be added to the <head> section of all pages."><?php echo isset($edit->head_scripts) ? htmlspecialchars($edit->head_scripts) : ''; ?></textarea>
                                <small class="text-muted">Example: &lt;script&gt;...Google Analytics code...&lt;/script&gt;</small>
                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Theme Tab -->
                            <div class="tab-pane fade" id="theme" role="tabpanel" aria-labelledby="theme-tab">
                                <div class="row" style="margin-top: 20px;">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label">Primary Color:</label>
                                            <div class="col-sm-12">
                                                <input type="color" value="<?php echo isset($edit->primary_color) ? htmlspecialchars($edit->primary_color) : '#db1215'; ?>" class="form-control" name="primary_color" id="primary_color">
                                                <small class="text-muted">Select the main theme color for your website</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label">Navigation Color:</label>
                                            <div class="col-sm-12">
                                                <input type="color" value="<?php echo isset($edit->navigation_color) ? htmlspecialchars($edit->navigation_color) : '#000000'; ?>" class="form-control" name="navigation_color" id="navigation_color">
                                                <small class="text-muted">Select the navigation bar color</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label">Button Color:</label>
                                            <div class="col-sm-12">
                                                <input type="color" value="<?php echo isset($edit->button_color) ? htmlspecialchars($edit->button_color) : '#154880'; ?>" class="form-control" name="button_color" id="button_color">
                                                <small class="text-muted">Select the button color for your website</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label">Theme Style:</label>
                                            <div class="col-sm-12">
                                                <select class="form-control" name="theme_style" id="theme_style">
                                                    <option value="default" <?php echo (isset($edit->theme_style) && $edit->theme_style == 'default') ? 'selected' : ''; ?>>Default Theme</option>
                                                    <option value="modern" <?php echo (isset($edit->theme_style) && $edit->theme_style == 'modern') ? 'selected' : ''; ?>>Modern Theme</option>
                                                    <option value="classic" <?php echo (isset($edit->theme_style) && $edit->theme_style == 'classic') ? 'selected' : ''; ?>>Classic Theme</option>
                                                    <option value="minimal" <?php echo (isset($edit->theme_style) && $edit->theme_style == 'minimal') ? 'selected' : ''; ?>>Minimal Theme</option>
                                                </select>
                                                <small class="text-muted">Choose the overall theme style for your website</small>
                                            </div>
                                        </div>
                                    </div>
                        </div>
                        
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-info">
                                            <h5><i class="fa fa-info-circle"></i> Color Preview</h5>
                                            <p>Here's how your selected colors will look:</p>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="color-preview" style="background-color: <?php echo isset($edit->primary_color) ? htmlspecialchars($edit->primary_color) : '#db1215'; ?>; height: 40px; border-radius: 5px; margin: 10px 0; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                                        Primary Color
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="color-preview" style="background-color: <?php echo isset($edit->navigation_color) ? htmlspecialchars($edit->navigation_color) : '#000000'; ?>; height: 40px; border-radius: 5px; margin: 10px 0; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                                        Navigation Color
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                       
                        @if(isset($edit->id))
                        <input type="hidden" name="hidden_id" value="{{$edit->id}}">
                        @endif
                        <div class="form-group">
                            <div class="col-sm-10"><button class="btn btn-md btn-primary" type="submit"><strong>Save</strong></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
      </div>
  </div>
@endsection

@push('scripts')

<script>

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
    
            reader.onload = function (e) {
                $(input).parent().find('img').attr('src', e.target.result).show();
            };
    
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    // Tab functionality
    $(document).ready(function() {
        // Hide all tab panes first
        $('.tab-pane').removeClass('active show');
        
        // Show only the first tab (General) on page load
        $('#general').addClass('active show');
        $('#general-tab').addClass('active');
        
        // Handle tab clicks
        $('.nav-tabs a[data-toggle="tab"]').on('click', function(e) {
            e.preventDefault();
            var target = $(this).attr('href');
            
            // Remove active from all tabs and panes
            $('.nav-tabs .nav-link').removeClass('active');
            $('.tab-pane').removeClass('active show');
            
            // Add active to clicked tab and its pane
            $(this).addClass('active');
            $(target).addClass('active show');
        });
        
        // Bootstrap tab event handler (backup)
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr('href');
            $('.tab-pane').removeClass('active show');
            $(target).addClass('active show');
        });
        
        // Update primary color preview
        $('#primary_color').on('change', function() {
            var color = $(this).val();
            $('.color-preview').first().css('background-color', color);
        });
        
        // Update navigation color preview
        $('#navigation_color').on('change', function() {
            var color = $(this).val();
            $('.color-preview').last().css('background-color', color);
        });
    });
    
</script>

@endpush