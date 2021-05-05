<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Style -->
    <link rel="stylesheet" href="{{ URL::asset('assets/css/style.css')}}">

    <title>Login</title>
    <link rel="shortcut icon" type="image/jpg" href="front_assets/img/favicon.png"/>
    {{--  style.css file added in public-assets-css  --}}

  </head>
  <body>
    {{--  images file added in public-assets-image  --}}
  

  <div class="d-lg-flex half">
    <div class="bg order-1 order-md-2" style="background-image: url({{ URL::asset('assets/images/bg_1.jpg')}})"></div>
    <div class="contents order-2 order-md-1">

      <div class="container">
        <div class="row align-items-center justify-content-center">
          <div class="col-md-7">
            <h3><strong>Login to Zcon</strong></h3>
            <div class="welcome-logo">
              <img src="{{ URL::asset('assets/images/logo.png')}}">
            </div>
            <form action="{{ url('admin/authenticate') }}" method="post">
                @csrf
              <div class="form-group first">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" value="" name="username" placeholder="Enter the username">
              </div>
              <div class="form-group last mb-3">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" value="{{ canDisplayCredentials() ? '123456':'' }}" 
                placeholder="Enter the password" aria-label="Password" aria-describedby="password-addon">
              </div>
              
              <div class="d-flex mb-5 align-items-center">
                <label class="control control--checkbox mb-0"><span class="caption"><input type="checkbox" /> Remember me</span>

                </label>
                <span class="ml-auto"><a href="#" class="forgot-pass">Forgot Password</a></span> 
              </div>

              <input type="submit" value="Log In" class="btn btn-block btn-primary">

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
    
    

    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js" ></script>
    <script src="{{ URL::asset('assets/js/images/main.js')}}"></script>

    <script>
        $(document).ready(function() {
            var canDisplayCredentials = '{!! canDisplayCredentials() !!}';
            $('.login_btn').click(function() {
                $('.login_btn').removeClass('active')
                $(this).addClass('active')
                login_change();
            });
            login_change();
            function login_change() {
                user = $('.login_btn.active').attr('user')
                $('.user_type').val(user);
                if (user == 'Admin') {
                    if(canDisplayCredentials == '1') {
                        $('#username').val('admin');
                    }
                    $('#username').attr('placeholder','Username');
                    $('.username_label').text('Username');
                    $(".username_label").attr('label','Username');
                }
                else if(user == 'Dispatcher') {
                    if(canDisplayCredentials == '1') {
                        $('#username').val('dispatcher');
                    }
                    $('#username').attr('placeholder','Username');
                    $('.username_label').text('Username');
                    $(".username_label").attr('label','Username');
                }
                else if(user == 'Company') {
                    if(canDisplayCredentials == '1') {
                        $('#username').val('9876543211');
                    }
                    $('#username').attr('placeholder','Email / Mobile Number');
                    $('.username_label').text('Email / Mobile Number');
                    $(".username_label").attr('label','Email / Mobile Number');
                }
            }
        });
        </script>

    {{--  js file added in public-assets-js  --}}

</body>
</html>