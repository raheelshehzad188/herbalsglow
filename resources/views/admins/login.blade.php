<!DOCTYPE html>
<!-- Topbar Start -->
<?php $setting = DB::table('setting')
    ->where('id', '=', '1')
    ->first();
    $header_menu = DB::table('pages')
    ->where('menu_type', '=', 'header')->orderBy('position', 'asc')
    ->get();
    $top_bar = DB::table('pages')
    ->where('menu_type', '=', 'top_bar')->orderBy('position', 'asc')
    ->get();
$cate = DB::table('categories')->get();
?>
  <head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <link rel="stylesheet" href="style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style type="text/css">
        /* Google Font Link */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');
*{
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Poppins" , sans-serif;
}

    </style>
   </head>
<body>
    <div class="body" style="  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #201d1d;
  padding: 30px;">
  <div class="container" style=" border-radius: 15px;   position: relative;
    max-width: 850px;
    width: 100%;
    background: #fff;
    padding: 40px 30px;
    box-shadow: 0 5px 10px rgba(0,0,0,0.2);
    perspective: 2700px;
">
    <div class="cover" style="
    position: absolute;
    top: 0;
    left: 50%;
    height: 100%;
    width: 50%;
    z-index: 98;
    transition: all 1s ease;
    transform-origin: left;
    transform-style: preserve-3d;">
      <div class="front" style="    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;">
        <img style="    position: absolute;
    height: 100%;
    width: 100%;
    object-fit: cover;
    z-index: 10;" src="{{asset('')}}{{$setting->logo}}" alt="">
</div>

    </div>
    <div class="forms" style="    height: 100%;
    width: 100%;
    background: #fff;">
        <div class="form-content" style="display: flex;
    align-items: center;
    justify-content: space-between;">
          <div class="login-form"style=" width: calc(100% / 2 - 25px);">
            <div class="title" style="border-bottom: 3px solid #ffd333;
    position: relative;
    font-size: 24px;
    font-weight: 500;
    width: 63px;">Login</div>
          <form  action="{{url('/')}}/admin/login" method="post">
      @csrf

      @if(session('invalid'))
      <div class="alert alert-danger mt-1 alert-validation-msg" role="alert">
          <i class="feather icon-info mr-1 align-middle"></i>
          <span>{{session('invalid')}}</span>
      </div>
      @endif
                  <div class="input_box" style="    display: flex;
    align-items: center;
    height: 50px;
    width: 100%;
    margin: 10px 0;
    position: relative;">
                <i class="fas fa-envelope" style="    position: absolute;
    color: #ffd333;
    font-size: 17px;"></i>
                <input style="    height: 100%;
    width: 100%;
    outline: none;
    border: none;
    padding: 0 30px;
    font-size: 16px;
    font-weight: 500;
    border-bottom: 2px solid rgba(0,0,0,0.2);
    transition: all 0.3s ease;" type="text" name="email"  placeholder="Enter your email" required>
              </div>
      <!--<p>Username</p>-->
      <!--<input type="text" name="email" required placeholder="Enter your Email...">-->
                    <div class="input_box" style="display: flex;
    align-items: center;
    height: 50px;
    width: 100%;
    margin: 10px 0;
    position: relative;">
                <i class="fas fa-lock" style="    position: absolute;
    color: #ffd333;
    font-size: 17px;"></i>
                <input style="height: 100%;
    width: 100%;
    outline: none;
    border: none;
    padding: 0 30px;
    font-size: 16px;
    font-weight: 500;
    border-bottom: 2px solid rgba(0,0,0,0.2);
    transition: all 0.3s ease;"type="password" name="password" placeholder="Enter your password" required>
              </div>
              
      <!--<p>Password</p>-->
      <!--<input type="password" name="password" required placeholder="Enter your password...">-->
                    <div style="font-size: 14px;
    font-weight: 500;
    color: #333;" class="text"><a href="#" style="   color: #000; text-decoration: none;">Forgot password?</a></div>
      <!--<input type="submit" name="" value="Login">-->
        <div class="button input-box" style="    color: #fff;
    margin-top: 40px;">
                <input type="submit" name="" value="login"  onmouseover="this.style.transform= 'scale(1.02)'" onmouseout="this.style.transform= 'scale(1)'"  style="    color: #000;
    background: #ffd333;
    border-radius: 6px;
    padding: 0;
    border: none;
    height: 50px;
    width: 370px;
    cursor: pointer;
    transition: all 0.4s ease;">
              </div>
                            <div style="    text-align: center;
    margin-top: 25px;" class="text sign-up-text">Don't have an account?</div>
    </form>
      </div>
       
    </div>
    </div>
  </div>
</div>
</body>
</html>