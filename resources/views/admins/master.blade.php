@php
    $admin = Session::get('admin');
    $store = \App\Support\CurrentStore::get();
    $storeName = $store->name ?? 'Store';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — {{ $storeName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('backend_assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend_assets/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('backend_assets/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend_assets/css/plugins/select2/select2.min.css') }}" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/bootstrap.tagsinput/0.8.0/bootstrap-tagsinput.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.css" rel="stylesheet">
    <link href="{{ asset('backend_assets/css/shopify-admin.css') }}" rel="stylesheet">
    <style>
        .ck-editor__editable_inline { min-height: 400px; }
    </style>
    @stack('styles')
</head>
<body class="sa-body admin-shopify">
<div class="sa-shell">
    <aside class="sa-sidebar">
        <div class="sa-brand">
            <span class="sa-brand-mark">{{ strtoupper(substr($storeName, 0, 1)) }}</span>
            <div>
                <strong>{{ $storeName }}</strong>
                <small>{{ ($admin->role ?? '') === 'super_admin' ? 'Super admin' : 'Store admin' }}</small>
            </div>
        </div>
        <nav class="sa-nav">
            <a class="sa-nav-item @yield('dashboard_active')" href="{{ route('admins.dashboard') }}">Home</a>
            <div class="sa-nav-label">Orders</div>
            <a class="sa-nav-item @yield('order1')" href="{{ route('admins.orders') }}">Orders</a>
            <a class="sa-nav-item @yield('corder')" href="{{ route('admins.complete_orders') }}">Completed</a>
            <div class="sa-nav-label">Catalog</div>
            <a class="sa-nav-item @yield('product_child_2_active')" href="{{ route('admins.products') }}">Products</a>
            <a class="sa-nav-item @yield('product_child_1_active')" href="{{ route('admins.product_form') }}">Add product</a>
            <a class="sa-nav-item @yield('category_child_1_active')" href="{{ route('admins.category') }}">Categories</a>
            <a class="sa-nav-item @yield('category_child_2_active')" href="{{ route('admins.subcategory') }}">Subcategories</a>
            <a class="sa-nav-item @yield('brand')" href="{{ route('admins.brand') }}">Brands</a>
            <div class="sa-nav-label">Online store</div>
            <a class="sa-nav-item @yield('page_2_active')" href="{{ route('admins.pages') }}">Pages</a>
            <a class="sa-nav-item @yield('page_1_active')" href="{{ route('admins.page_form') }}">Add page</a>
            <a class="sa-nav-item @yield('slider')" href="{{ route('admins.slider') }}">Home slider</a>
            <a class="sa-nav-item @yield('faq')" href="{{ route('admins.faq') }}">FAQs</a>
            <a class="sa-nav-item @yield('boxs')" href="{{ route('admins.boxs') }}">Boxes</a>
            <a class="sa-nav-item @yield('clients')" href="{{ route('admins.clients') }}">Clients</a>
            <a class="sa-nav-item @yield('blog_active')" href="{{ route('admins.blog') }}">Blog</a>
            <a class="sa-nav-item @yield('blog_category_active')" href="{{ route('admins.blog_category') }}">Blog categories</a>
            <div class="sa-nav-label">Sales channels</div>
            <a class="sa-nav-item @yield('integrations_active')" href="{{ url('/admin/integrations') }}">Apps · Meta / TikTok</a>
            <a class="sa-nav-item @yield('payment_methods')" href="{{ route('admins.payment_methods') }}">Payments</a>
            <a class="sa-nav-item @yield('review')" href="{{ route('admins.review') }}">Reviews</a>
            <a class="sa-nav-item @yield('message')" href="{{ route('admins.contact') }}">Inbox</a>
            <a class="sa-nav-item @yield('news_letters')" href="{{ route('admins.news_letters') }}">Newsletters</a>
            <div class="sa-nav-label">Settings</div>
            <a class="sa-nav-item @yield('setting')" href="{{ route('admins.setting') }}">Settings</a>
            <a class="sa-nav-item @yield('flush_data')" href="{{ url('/admin/flush-data') }}">Flush data</a>
            <a class="sa-nav-item @yield('import_data')" href="{{ url('/admin/import-data') }}">Import data</a>
            <a class="sa-nav-item @yield('theme_settings')" href="{{ route('admins.theme_settings') }}">Theme customizer</a>
            <a class="sa-nav-item @yield('media')" href="{{ route('admins.media') }}">Social</a>
            <a class="sa-nav-item @yield('admin')" href="{{ route('admins.admin') }}">Staff</a>
            @if(($admin->role ?? '') === 'super_admin')
                <a class="sa-nav-item" href="{{ url('/superadmin/dashboard') }}">Super Admin</a>
            @endif
            <a class="sa-nav-item" href="{{ url('/') }}" target="_blank">View store</a>
        </nav>
        <div class="sa-sidebar-foot">
            <div class="sa-user">{{ $admin->email ?? 'Admin' }}</div>
            <a href="{{ url('/admin/logout') }}">Log out</a>
                            </div>
    </aside>
    <main class="sa-main">
        <header class="sa-topbar">
            <div>
                <h1>@yield('page_title', View::getSection('title') ?: 'Home')</h1>
                <p class="sa-subtitle">@yield('page_subtitle', $storeName)</p>
                    </div>
            <div class="sa-topbar-actions">
                @yield('page_actions')
                <a class="sa-btn sa-btn-secondary" href="{{ url('/') }}" target="_blank">View store</a>
            </div>
        </header>
        @if(Session::has('msg'))
            <div class="sa-alert sa-alert-{{ Session::get('msg_type', 'success') }}">{{ Session::get('msg') }}</div>
        @endif
        <div class="sa-content">
            @yield('content')
        </div>
    </main>
                        </div>

<script src="{{ asset('backend_assets/js/jquery-3.1.1.min.js') }}"></script>
<script src="{{ asset('backend_assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('backend_assets/js/plugins/dataTables/datatables.min.js') }}"></script>
<script src="{{ asset('backend_assets/js/plugins/select2/select2.full.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/bootstrap.tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
    $(document).ready(function () {
        if ($.fn.DataTable) {
            $('.dataTables-example').DataTable({
                pageLength: 25,
                responsive: true,
                aaSorting: []
            });
        }
        if ($.fn.select2) {
            $('.select2').select2();
        }
        if ($.fn.summernote) {
            $('.summernote').summernote({ height: 300 });
        }
        $('.delete_record').click(function () {
            var href = $(this).attr('href');
            swal({ title: 'Are you sure?', icon: 'warning', buttons: true, dangerMode: true })
                .then(function (ok) { if (ok) { window.location.href = href; } });
            return false;
        });
        @if(Session::has('msg'))
        showToastr("{{ Session::get('msg') }}", "{{ Session::get('msg_type') }}");
        @endif
    });
    function showToastr(msg, msg_type) {
        if (!msg) return;
        if (msg_type === 'success') toastr.success(msg);
        else if (msg_type === 'danger' || msg_type === 'error') toastr.error(msg);
        else if (msg_type === 'info') toastr.info(msg);
        else if (msg_type === 'warning') toastr.warning(msg);
    }
                </script>
    @stack('scripts')
</body>
</html>
