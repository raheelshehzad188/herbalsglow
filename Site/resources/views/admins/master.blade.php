<!DOCTYPE html>
<html>

<head>


  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>@yield('title')</title>

  <link href="{{ asset('backend_assets/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{ asset('backend_assets/font-awesome/css/font-awesome.css')}}" rel="stylesheet">

  <link href="{{ asset('backend_assets/css/plugins/dataTables/datatables.min.css')}}" rel="stylesheet">

  <link href="{{ asset('backend_assets/css/animate.css')}}" rel="stylesheet">
  <link href="{{ asset('backend_assets/css/style.css')}}" rel="stylesheet">
  <link href="{{ asset('backend_assets/css/plugins/select2/select2.min.css')}}" rel="stylesheet">

  <link href="{{ asset('backend_assets/css/plugins/summernote/summernote.css')}}" rel="stylesheet">
  <link href="{{ asset('backend_assets/css/plugins/summernote/summernote-bs3.css')}}" rel="stylesheet">

  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/bootstrap.tagsinput/0.8.0/bootstrap-tagsinput.css" rel="stylesheet"/>
  
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    


</head>

<body>
    <div id="wrapper">
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav metismenu" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element"> <span>
                                <img alt="image" class="img-circle"
                                    src="{{ asset('backend_assets/img/profile_small.jpg') }}" />
                            </span>
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear"> <span class="block m-t-xs"> <strong
                                            class="font-bold">David Williams</strong>
                                    </span> <span class="text-muted text-xs block">Admin <b
                                            class="caret"></b></span> </span> </a>
                            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li><a href="/admin/logout">Logout</a></li>
                            </ul>
                        </div>
                        <div class="logo-element">
                            IN+
                        </div>
                    </li>
                    <li class="@yield('dashboard_active')">
                        <a href="{{route('admins.dashboard')}}"><i class="fa fa-th-large"></i> <span
                                class="nav-label">Dashboard</span></a>
                    </li>
                    <li class="@yield('category_active')">
                        <a href="#"><i class="fa fa-bar-chart-o"></i> <span class="nav-label">Category</span><span
                                class="fa arrow"></span></a>
                        <ul class="nav nav-second-level collapse @yield('category_active_c1') @yield('category_active_c2') @yield('category_active_c3')">
                            <li class="@yield('category_child_1_active')"><a href="{{route('admins.category')}}">Category</a></li>
                            <li style="display:none;" class="@yield('category_child_3_active')"><a href="{{route('admins.brand')}}">Brand</a></li>
                        </ul>
                    </li>
                    <li class="@yield('product_active')">
                        <a href="#"><i class="fa fa-edit"></i> <span class="nav-label">Products</span><span
                                class="fa arrow"></span></a>
                        <ul class="nav nav-second-level collapse @yield('product_active_c1')">
                            <li class="@yield('product_child_1_active')"><a href="{{route('admins.product_form')}}">Add Product</a></li>
                            <li class="@yield('product_child_2_active')"><a href="{{route('admins.products')}}">All Product</a></li>

                        </ul>
                    </li>
                    <!--<li class="@yield('product_attribute_active')">-->
                    <!--    <a href="#"><i class="fa fa-edit"></i> <span class="nav-label">Products Attributes</span><span-->
                    <!--            class="fa arrow"></span></a>-->
                    <!--    <ul class="nav nav-second-level collapse @yield('product_active_c1')">-->
                    <!--        <li class="@yield('product_attribute_0_active')"><a href="{{route('admins.clarity')}}">Clarity</a></li>-->
                    <!--        <li class="@yield('product_attribute_child_1_active')"><a href="{{route('admins.size')}}">Size</a></li>-->
                    <!--        <li class="@yield('product_attribute_child_2_active')"><a href="{{route('admins.colors')}}">Color</a></li>-->
                    <!--        <li class="@yield('product_attribute_child_3_active')"><a href="{{route('admins.shap')}}">Shape</a></li>-->

                    <!--    </ul>-->
                    <!--</li>-->
                    <li class="@yield('page_active')">
                        <a href="#"><i class="fa fa-edit"></i> <span class="nav-label">Pages</span><span
                                class="fa arrow"></span></a>
                        <ul class="nav nav-second-level collapse @yield('page_active_c1')">
                            <li class="@yield('page_1_active')"><a href="{{route('admins.page_form')}}">Add Page</a></li>
                             
                            <li class="@yield('page_2_active')"><a href="{{route('admins.pages')}}">All Page</a></li>
                            <!--<li class="@yield('page_1_active')"><a href="{{route('admins.page_form')}}?section=1">Add Section</a></li>-->
                            <!--<li class="@yield('page_2_active')"><a href="{{route('admins.msections')}}">Menu section</a></li>-->

                        </ul>
                    </li>
                    <li class="@yield('coupon_active')" style="display:none;" >
                        <a href="{{route('admins.coupon')}}"><i class="fa fa-flask"></i> <span
                                class="nav-label">Coupons</span></a>
                    </li>
                    <li class="@yield('order')" style="display:;" >
                        <a href="#"><i class="fa fa-flask"></i> <span
                                class="nav-label">Orders</span><span
                                class="fa arrow"></span></a>
                        <ul class="nav nav-second-level collapse @yield('orderc1')">
                            <li class="@yield('order1')"><a href="{{route('admins.orders')}}">Orders</a></li>
                            <li class="@yield('corder')"><a href="{{route('admins.complete_orders')}}">Complete Orders</a></li>
                        </ul>
                    </li>
                    <li class="@yield('slider')"> 
                        <a href="{{route('admins.slider')}}"><i class="fa fa-pie-chart"></i> <span
                                class="nav-label">Home Slider</span> </a>
                    </li>
                    <li class="@yield('faq')"> 
                        <a href="{{route('admins.faq')}}"><i class="fa fa-question"></i> <span
                                class="nav-label">Faq's</span> </a>
                    </li>
                    <!--<li class="@yield('home_cats_active')"> -->
                    <!--    <a href="{{route('admins.home_cats')}}"><i class="fa fa-list-alt"></i> <span-->
                    <!--            class="nav-label">Home Categories</span> </a>-->
                    <!--</li>-->
                    <li class="@yield('setting')"> 
                        <a href="{{route('admins.setting')}}"><i class="fa fa-cog"></i> <span
                                class="nav-label">Settings</span> </a>
                    </li>
                    <li class="@yield('media')"> 
                        <a href="{{route('admins.media')}}"><i class="fa fa-image"></i> <span
                                class="nav-label">Media</span> </a>
                    </li>
                    <!-- <li class="@yield('learn_setting')"> -->
                    <!--    <a href="{{route('admins.learn_setting')}}"><i class="fa fa-cog" ></i> <span-->
                    <!--            class="nav-label">Learn Settings</span> </a>-->
                    <!--</li>-->
                    <li class="@yield('news_letter_active')" style="display:;" > 
                        <a href="{{route('admins.news_letters')}}"><i class="fa fa-users"></i> <span
                                class="nav-label">News Letters</span> </a>
                    </li>
                    <li class="@yield('review')" style="display:;" > 
                        <a href="{{route('admins.review')}}"><i class="fa fa-book"></i> <span
                                class="nav-label">Reviews</span> </a>
                    </li>
                    <li class="@yield('blog_category_active')" style="display:;" > 
                        <a href="{{route('admins.blog_category')}}"><i class="fa fa-book"></i> <span
                                class="nav-label">Blog_category</span> </a>
                    </li>
                    <li class="@yield('blog_active')" style="display:;" > 
                        <a href="{{route('admins.blog')}}"><i class="fa fa-book"></i> <span
                                class="nav-label">Blog</span> </a>
                    </li>
                    <li class="@yield('message')" style="display:;" > 
                        <a href="{{route('admins.contact')}}"><i class="fa fa-comment"></i> <span
                                class="nav-label">Messages</span> </a>
                    </li>
                    <li class="@yield('admin')"> 
                        <a href="{{route('admins.admin')}}"><i class="fa fa-user"></i> <span
                                class="nav-label">Admin</span> </a>
                    </li>
                </ul>

            </div>
        </nav>

        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i
                                class="fa fa-bars"></i> </a>
                        <form style="display:none;" role="search" class="navbar-form-custom"
                            action="http://webapplayers.com/inspinia_admin-v2.7/search_results.html">
                            <div class="form-group">
                                <input type="text" placeholder="Search for something..." class="form-control"
                                    name="top-search" id="top-search">
                            </div>
                        </form>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">


                        <li>
                            <a href="/admin/logout">
                                <i class="fa fa-sign-out"></i> Log out
                            </a>
                        </li>
                        
                    </ul>

                </nav>
            </div>
            @yield('content')

            <div class="footer">
                <div class="pull-right">
                    10GB of <strong>250GB</strong> Free.
                </div>
                <div>
                    <strong>Copyright</strong> Example Company &copy; 2014-2017
                </div>
            </div>
        </div>
        <div id="right-sidebar">
            <div class="sidebar-container">

                <ul class="nav nav-tabs navs-3">

                    <li class="active"><a data-toggle="tab" href="#tab-1">
                            Notes
                        </a></li>
                    <li><a data-toggle="tab" href="#tab-2">
                            Projects
                        </a></li>
                    <li class=""><a data-toggle="tab" href="#tab-3">
                            <i class="fa fa-gear"></i>
                        </a></li>
                </ul>

                <div class="tab-content">


                    <div id="tab-1" class="tab-pane active">

                        <div class="sidebar-title">
                            <h3> <i class="fa fa-comments-o"></i> Latest Notes</h3>
                            <small><i class="fa fa-tim"></i> You have 10 new message.</small>
                        </div>

                        <div>

                            <div class="sidebar-message">
                                <a href="#">
                                    <div class="pull-left text-center">
                                        <img alt="image" class="img-circle message-avatar"
                                            src="{{ asset('backend_assets/img/a1.jpg') }}">

                                        <div class="m-t-xs">
                                            <i class="fa fa-star text-warning"></i>
                                            <i class="fa fa-star text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="media-body">

                                        There are many variations of passages of Lorem Ipsum available.
                                        <br>
                                        <small class="text-muted">Today 4:21 pm</small>
                                    </div>
                                </a>
                            </div>
                            <div class="sidebar-message">
                                <a href="#">
                                    <div class="pull-left text-center">
                                        <img alt="image" class="img-circle message-avatar"
                                            src="{{ asset('backend_assets/img/a2.jpg') }}">
                                    </div>
                                    <div class="media-body">
                                        The point of using Lorem Ipsum is that it has a more-or-less normal.
                                        <br>
                                        <small class="text-muted">Yesterday 2:45 pm</small>
                                    </div>
                                </a>
                            </div>
                            <div class="sidebar-message">
                                <a href="#">
                                    <div class="pull-left text-center">
                                        <img alt="image" class="img-circle message-avatar"
                                            src="{{ asset('backend_assets/img/a3.jpg') }}">

                                        <div class="m-t-xs">
                                            <i class="fa fa-star text-warning"></i>
                                            <i class="fa fa-star text-warning"></i>
                                            <i class="fa fa-star text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        Mevolved over the years, sometimes by accident, sometimes on purpose (injected
                                        humour and the like).
                                        <br>
                                        <small class="text-muted">Yesterday 1:10 pm</small>
                                    </div>
                                </a>
                            </div>
                            <div class="sidebar-message">
                                <a href="#">
                                    <div class="pull-left text-center">
                                        <img alt="image" class="img-circle message-avatar"
                                            src="{{ asset('backend_assets/img/a4.jpg') }}">
                                    </div>

                                    <div class="media-body">
                                        Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the
                                        <br>
                                        <small class="text-muted">Monday 8:37 pm</small>
                                    </div>
                                </a>
                            </div>
                            <div class="sidebar-message">
                                <a href="#">
                                    <div class="pull-left text-center">
                                        <img alt="image" class="img-circle message-avatar"
                                            src="{{ asset('backend_assets/img/a8.jpg') }}">
                                    </div>
                                    <div class="media-body">

                                        All the Lorem Ipsum generators on the Internet tend to repeat.
                                        <br>
                                        <small class="text-muted">Today 4:21 pm</small>
                                    </div>
                                </a>
                            </div>
                            <div class="sidebar-message">
                                <a href="#">
                                    <div class="pull-left text-center">
                                        <img alt="image" class="img-circle message-avatar"
                                            src="{{ asset('backend_assets/img/a7.jpg') }}">
                                    </div>
                                    <div class="media-body">
                                        Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..",
                                        comes from a line in section 1.10.32.
                                        <br>
                                        <small class="text-muted">Yesterday 2:45 pm</small>
                                    </div>
                                </a>
                            </div>
                            <div class="sidebar-message">
                                <a href="#">
                                    <div class="pull-left text-center">
                                        <img alt="image" class="img-circle message-avatar"
                                            src="{{ asset('backend_assets/img/a3.jpg') }}">

                                        <div class="m-t-xs">
                                            <i class="fa fa-star text-warning"></i>
                                            <i class="fa fa-star text-warning"></i>
                                            <i class="fa fa-star text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        The standard chunk of Lorem Ipsum used since the 1500s is reproduced below.
                                        <br>
                                        <small class="text-muted">Yesterday 1:10 pm</small>
                                    </div>
                                </a>
                            </div>
                            <div class="sidebar-message">
                                <a href="#">
                                    <div class="pull-left text-center">
                                        <img alt="image" class="img-circle message-avatar"
                                            src="{{ asset('backend_assets/img/a4.jpg') }}">
                                    </div>
                                    <div class="media-body">
                                        Uncover many web sites still in their infancy. Various versions have.
                                        <br>
                                        <small class="text-muted">Monday 8:37 pm</small>
                                    </div>
                                </a>
                            </div>
                        </div>

                    </div>

                    <div id="tab-2" class="tab-pane">

                        <div class="sidebar-title">
                            <h3> <i class="fa fa-cube"></i> Latest projects</h3>
                            <small><i class="fa fa-tim"></i> You have 14 projects. 10 not completed.</small>
                        </div>

                        <ul class="sidebar-list">
                            <li>
                                <a href="#">
                                    <div class="small pull-right m-t-xs">9 hours ago</div>
                                    <h4>Business valuation</h4>
                                    It is a long established fact that a reader will be distracted.

                                    <div class="small">Completion with: 22%</div>
                                    <div class="progress progress-mini">
                                        <div style="width: 22%;" class="progress-bar progress-bar-warning"></div>
                                    </div>
                                    <div class="small text-muted m-t-xs">Project end: 4:00 pm - 12.06.2014</div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div class="small pull-right m-t-xs">9 hours ago</div>
                                    <h4>Contract with Company </h4>
                                    Many desktop publishing packages and web page editors.

                                    <div class="small">Completion with: 48%</div>
                                    <div class="progress progress-mini">
                                        <div style="width: 48%;" class="progress-bar"></div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div class="small pull-right m-t-xs">9 hours ago</div>
                                    <h4>Meeting</h4>
                                    By the readable content of a page when looking at its layout.

                                    <div class="small">Completion with: 14%</div>
                                    <div class="progress progress-mini">
                                        <div style="width: 14%;" class="progress-bar progress-bar-info"></div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="label label-primary pull-right">NEW</span>
                                    <h4>The generated</h4>
                                    <!--<div class="small pull-right m-t-xs">9 hours ago</div>-->
                                    There are many variations of passages of Lorem Ipsum available.
                                    <div class="small">Completion with: 22%</div>
                                    <div class="small text-muted m-t-xs">Project end: 4:00 pm - 12.06.2014</div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div class="small pull-right m-t-xs">9 hours ago</div>
                                    <h4>Business valuation</h4>
                                    It is a long established fact that a reader will be distracted.

                                    <div class="small">Completion with: 22%</div>
                                    <div class="progress progress-mini">
                                        <div style="width: 22%;" class="progress-bar progress-bar-warning"></div>
                                    </div>
                                    <div class="small text-muted m-t-xs">Project end: 4:00 pm - 12.06.2014</div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div class="small pull-right m-t-xs">9 hours ago</div>
                                    <h4>Contract with Company </h4>
                                    Many desktop publishing packages and web page editors.

                                    <div class="small">Completion with: 48%</div>
                                    <div class="progress progress-mini">
                                        <div style="width: 48%;" class="progress-bar"></div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div class="small pull-right m-t-xs">9 hours ago</div>
                                    <h4>Meeting</h4>
                                    By the readable content of a page when looking at its layout.

                                    <div class="small">Completion with: 14%</div>
                                    <div class="progress progress-mini">
                                        <div style="width: 14%;" class="progress-bar progress-bar-info"></div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="label label-primary pull-right">NEW</span>
                                    <h4>The generated</h4>
                                    <!--<div class="small pull-right m-t-xs">9 hours ago</div>-->
                                    There are many variations of passages of Lorem Ipsum available.
                                    <div class="small">Completion with: 22%</div>
                                    <div class="small text-muted m-t-xs">Project end: 4:00 pm - 12.06.2014</div>
                                </a>
                            </li>

                        </ul>

                    </div>

                    <div id="tab-3" class="tab-pane">

                        <div class="sidebar-title">
                            <h3><i class="fa fa-gears"></i> Settings</h3>
                            <small><i class="fa fa-tim"></i> You have 14 projects. 10 not completed.</small>
                        </div>

                        <div class="setings-item">
                            <span>
                                Show notifications
                            </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox"
                                        id="example">
                                    <label class="onoffswitch-label" for="example">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                            <span>
                                Disable Chat
                            </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" checked class="onoffswitch-checkbox"
                                        id="example2">
                                    <label class="onoffswitch-label" for="example2">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                            <span>
                                Enable history
                            </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox"
                                        id="example3">
                                    <label class="onoffswitch-label" for="example3">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                            <span>
                                Show charts
                            </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox"
                                        id="example4">
                                    <label class="onoffswitch-label" for="example4">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                            <span>
                                Offline users
                            </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" checked name="collapsemenu" class="onoffswitch-checkbox"
                                        id="example5">
                                    <label class="onoffswitch-label" for="example5">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                            <span>
                                Global search
                            </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" checked name="collapsemenu" class="onoffswitch-checkbox"
                                        id="example6">
                                    <label class="onoffswitch-label" for="example6">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                            <span>
                                Update everyday
                            </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox"
                                        id="example7">
                                    <label class="onoffswitch-label" for="example7">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="sidebar-content">
                            <h4>Settings</h4>
                            <div class="small">
                                I belive that. Lorem Ipsum is simply dummy text of the printing and typesetting
                                industry.
                                And typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever
                                since the 1500s.
                                Over the years, sometimes by accident, sometimes on purpose (injected humour and the
                                like).
                            </div>
                        </div>

                    </div>
                </div>

            </div>



        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <!-- Mainly scripts -->
    <script src="{{ asset('backend_assets/js/jquery-3.1.1.min.js')}}"></script>
    <script src="{{ asset('backend_assets/js/bootstrap.min.js')}}"></script>
    <script src="{{ asset('backend_assets/js/plugins/metisMenu/jquery.metisMenu.js')}}"></script>
    <script src="{{ asset('backend_assets/js/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>

    <script src="{{ asset('backend_assets/js/plugins/dataTables/datatables.min.js')}}"></script>

    <!-- Custom and plugin javascript -->
    <script src="{{ asset('backend_assets/js/inspinia.js')}}"></script>
    <script src="{{ asset('backend_assets/js/plugins/pace/pace.min.js')}}"></script>
    <!-- Select2 -->
    <script src="{{ asset('backend_assets/js/plugins/select2/select2.full.min.js')}}"></script>
    <!-- SUMMERNOTE -->
<script src="{{ asset('backend_assets/js/plugins/summernote/summernote.min.js')}}"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script src="https://cdn.jsdelivr.net/bootstrap.tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    

    <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function(){
            $('.dataTables-example').DataTable({
                pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    { extend: 'copy'},
                    {extend: 'csv'},
                    {extend: 'excel', title: 'ExampleFile'},
                    {extend: 'pdf', title: 'ExampleFile'},

                    {extend: 'print',
                     customize: function (win){
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');

                            $(win.document.body).find('table')
                                    .addClass('compact')
                                    .css('font-size', 'inherit');
                    }
                    }
                ]

            });

        });

    </script>

    <script>
        function showToastr(msg,msg_type)
        {
            switch(msg_type)
                {
                    case "success":
                    toastr.success(msg);
                    break;

                    case "danger":
                    toastr.error(msg)
                    break;

                    case "info":
                    toastr.info(msg)
                    break;
                    
                    case "warning":
                    toastr.warning(msg)
                    break;
                }
        }
        $(document).ready(function(){
            $('.delete_record').click(function(){
                $(".select2").select2();
                swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                })
                .then((willDelete) => {
                if (willDelete) {
                    let href=$(this).data('href');
                    window.location.href=href;
                }
                });

            });



            let msg_type="";
            let msg="";
            @if(Session::has('msg'))
            msg_type="{{Session::get('msg_type')}}";
            msg="{{Session::get('msg')}}";
            @endif

            if(msg!="")
            {
                switch(msg_type)
                {
                    case "success":
                    toastr.success(msg);
                    break;

                    case "danger":
                    toastr.error(msg)
                    break;

                    case "info":
                    toastr.info(msg)
                    break;
                    
                    case "warning":
                    toastr.warning(msg)
                    break;
                }
            }





        });





    </script>
    <script>
        $(document).ready(function(){
    
            $('.summernote').summernote(
                {
                height: 150
                }
                );
    
            // $('.input-group.date').datepicker({
            //     todayBtn: "linked",
            //     keyboardNavigation: false,
            //     forceParse: false,
            //     calendarWeeks: true,
            //     autoclose: true
            // });
    
        });
    </script>
    @stack('scripts')
</body>

</html>
