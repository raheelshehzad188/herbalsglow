@extends('layout.app2')
@section('content')
<?php 
use App\Models\Admins\Category;
$cate = Category::limit('6')->get();
?>
<div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="{{ url('/') }}">Home</a>
                    <a class="breadcrumb-item text-dark" href="{{ url($sub_cat->slug) }}">{{$sub_cat->name}}</a>
                    <span class="breadcrumb-item active">{{ $category_id->name }}</span>
                </nav>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row px-xl-5">
            
            <div class="">
                <h2>{{ $category_id->name }}</h2>
            <div class="pb-3 pt-2">
                <p>
                               @if(isset($category_id->short_description))
    {!! str_replace('&nbsp;', '', $category_id->short_description) !!}
@endif

                </p>
            </div>  
            </div>


            <!-- Shop Product Start -->
            <div class="col-lg-12 col-md-12">
                <div class="row pb-3">
                    <div class="col-12 pb-1">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <button class="btn btn-sm btn-light"><i class="fa fa-th-large"></i></button>
                                <button class="btn btn-sm btn-light ml-2"><i class="fa fa-bars"></i></button>
                            </div>
                            <div class="ml-2">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-toggle="dropdown">Sorting</button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#">Latest</a>
                                        <a class="dropdown-item" href="#">Popularity</a>
                                        <a class="dropdown-item" href="#">Best Rating</a>
                                    </div>
                                </div>
                                <div class="btn-group ml-2">
                                    <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-toggle="dropdown">Showing</button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="#">10</a>
                                        <a class="dropdown-item" href="#">20</a>
                                        <a class="dropdown-item" href="#">30</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                     @foreach ($products as  $k=>$v)
                    @include('includes/parts/product_box')
                    @endforeach
                </div>
            </div>
            <!-- Shop Product End -->
            
             <style>
                .pagination-wrapper svg{
                    width: 30px;
                }
                .pagination-wrapper{
                       text-align: left;
                       width: 100%;
                    
                }
                .pagination-wrapper .leading-5{
                    margin:10px 0px;
                    
                }
                .pagination-wrapper nav div:nth-child(1){
                    display: flex;
                    justify-content: space-between;
                }
                .pagination-wrapper nav div:nth-child(2){
                    display:none!important;
                }
            </style>
            @if ($products->hasPages())
    <div class="pagination-wrapper">
         {{ $products->links() }}
    </div>
@endif
        </div>
    </div>
    
 
@endsection
