@extends('layout.app2')

<?php
use App\Models\Admins\Category;
use App\Models\Admins\SubCategory;
use App\Models\Childcatagorie;
use App\Models\product;
use App\Models\Admins\Gallerie;
  ?>


@section('content')
<style>
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
html {
    font-family: sans-serif;
    font-size: 10px;
}
body {
    width: 100%;
    min-height: 100vh;
    font-size: 1.6rem;
    line-height: 140%;
    background-color: #f3f6f6;
    opacity: 0.3;
    opacity: 1;
    display: grid;
    place-items: center;
}
.card {
    background: #fff;
    color: #333;
    margin: 15rem auto;
    width: 90%;
    max-width: 1200px;
    min-height: 30rem;
    border-radius: 2rem;
}

.hero {
    display: flex;
    justify-content: center;
    transform: translateY(-55%);
}
.hero .img {
    filter: drop-shadow(0rem 1.5rem rgba(0,0,0,0.1));
    transition: 0.3s ease-out;
}
.card:hover .img {
    filter: drop-shadow(0rem 2.5rem rgba(0,0,0,0.1));
}

.title {
    text-align: center;
    font-size: 5rem;
    padding: 1rem;
}

.acc-container {
    padding: 4rem 2rem;
}
.acc-btn {
    width: 100%;
    padding: 1.6rem 2rem;
    font-size: 1.6rem;
    cursor: pointer;
    background: inherit;
    border: none;
    outline: none;
    text-align: left;
    transition: all 0.5s linear;
}
.acc-btn:after {
    content: "\27A4";
    color: #fa8d0c;
    float: right;
    transition: all 0.3s linear;
}
.acc-btn.is-open:after {
    transform: rotate(90deg);
}
.acc-btn:hover, .acc-btn.is-open {
    color: #000;
    font-weight: bold;
}

.acc-content {
    max-height: 0;
    color: rgba(0,0,0,0.75);
    font-size: 1.5rem;
    margin: 0 2rem;
    padding-left: 1rem;
    overflow: hidden;
    transition: max-height 0.3s ease-in-out;
    border-bottom: 1px solid #ccc;
}

.credit {
    text-align: center;
    padding: 1rem;
}
.credit a {
    text-decoration: wavy underline;
    color: dodgerblue;
}
</style>
<div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="/">Home</a>
                    <span class="breadcrumb-item active">FAQ</span>
                </nav>
            </div>
        </div>
    </div>
    
    <main class="card">

  <h2 class="title">FAQ</h2>

  <div class="acc-container">
    @if(isset($faq))
    @foreach($faq as $k=> $v)
    <button class="acc-btn">{{$v->question}}</button>
    <div class="acc-content">
      <p></p>{!!$v->answer!!}</p>
    </div>
    @endforeach
    @endif

  </div>
</main>


@endsection
@section('script')
<script>
    const btns = document.querySelectorAll(".acc-btn");

// fn
function accordion() {
  // this = the btn | icon & bg changed
  this.classList.toggle("is-open");

  // the acc-content
  const content = this.nextElementSibling;

  // IF open, close | else open
  if (content.style.maxHeight) content.style.maxHeight = null;
  else content.style.maxHeight = content.scrollHeight + "px";
}

// event
btns.forEach((el) => el.addEventListener("click", accordion));

/*
   
       Jokes are from > 
        https://chartcons.com/100-funny-trick-questions-answers/
        Background image from > 
        https://www.magicpattern.design/tools/css-backgrounds
   
*/

</script>
@endsection
