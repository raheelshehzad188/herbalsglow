<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <style>
        body {
            margin: 1.5em;
            padding: 0;
            font-family: sans-serif;
            background-color: #ffc107;
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            }

            h1 {
            font-weight: bold;
            font-size: 3em;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            }

            span {
            color: rgb(141, 14, 130);
            }

            .login-box {
            width: 45em;
            height: 32em;
            background: rgba(239, 218, 241, 0.438);
            top: 50%;
            left: 50%;
            position: absolute;
            transform: translate(-50%, -50%);
            box-sizing: border-box;
            padding: 3em 3em;
            border-radius: 10%;
            }

            .login-image {
            width: 7em;
            height: 7em;
            border-radius: 50%;
            position: absolute;
            top: -3.7em;
            left: calc(50% - 3.7em);
            }

            h3 {
            margin: 0 auto;
            padding: 0.5em 0;
            text-align: center;
            font-size: 2.5rem;
            }

            .login-box p {
            margin: 0;
            padding: 1em 0;
            font-weight: bold;
            }

            .login-box input {
            width: 100%;
            margin-bottom: 2em;
            }

            .login-box input[type="text"],
            input[type="password"] {
            border: none;
           border-bottom: 1.2px solid rgb(0 0 0);
            background: transparent;
            outline: none;
            height: 2em;
               color: #4CAF50;
            font-size: 16px;
            }

            .login-box input[type="submit"] {
            border: none;
            outline: none;
            height: 2.5em;
           background: #000;
            color: white;
            font-size: 1em;
            border-radius: 1.5rem;
            }

            .login-box input[type="submit"]:hover {
            cursor: pointer;
            background: #FFC107;
            color: white;
            }

            .login-box a {
            text-decoration: none;
            font-size: 0.75em;
            line-height: 1.7em;
            color: rgb(53, 51, 58);
            display: flex;
            flex-direction: vertical;
            }

            .login-box a:hover {
            color: rgb(141, 14, 130);
            }

    </style>
</head>
<body>

  <div class="login-box">

    <h3>Login</h3>
    <form  action="{{url('/')}}/admin/login" method="post">
      @csrf
      @if(session('invalid'))
      <div class="alert alert-danger mt-1 alert-validation-msg" role="alert">
          <i class="feather icon-info mr-1 align-middle"></i>
          <span>{{session('invalid')}}</span>
      </div>
      @endif
      <p>Username</p>
      <input type="text" name="email" required placeholder="Enter your Email...">
      <p>Password</p>
      <input type="password" name="password" required placeholder="Enter your password...">
      <input type="submit" name="" value="Login">
    </form>
  </div>
</body>
</html>