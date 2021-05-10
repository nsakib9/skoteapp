
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Zcon</title>
    <link rel="shortcut icon" type="image/jpg" href="front_assets/img/favicon.png"/>
<!-- google fonts link -->
  <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	  <!-- Bootstrap Css -->

   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
  
  <style>
        @foreach($customCSS as $css)
            {{$css->csscode}}
       @endforeach
    </style>
  </head>
  <div id="app-wrapper" class="sigin-riders" ng-controller="user">
  <body ng-app="App">

<section class="ftco-section">
<div class="container">

<div class="row justify-content-center">

<div class="wrap col-6 p-0">
      <div class="login-wrap p-4 p-lg-5">
          <div class="d-flex">
								<div class="w-100">
									<p class="social-media d-flex justify-content-end">
										<a href="#" class="social-icon d-flex align-items-center justify-content-center"><span class="fa fa-facebook"></span></a>
										<a href="#" class="social-icon d-flex align-items-center justify-content-center"><span class="fa fa-twitter"></span></a>
									</p>
								</div>
							</div>
   
        
            <form class="push--top-small forward" method="POST" data-reactid="13" >
              
               <input type="hidden" name="user_type" value="Rider" id="user_type">
               <div data-reactid="15" class="email_phone-sec">
                  <h4 data-reactid="14">Sign In Rider</h4>

                  <div class="form-group mb-3">
                

                       
                           <input class="form-control" id="email_phone" placeholder="Enter Your Email or Mobile Number" autocorrect="off" autocapitalize="off" name="textInputValue" data-reactid="21" type="text" value="{{ canDisplayCredentials() ? '98765432106':'' }}" ng-init="invalid_email= '{{trans('messages.user.email_mobile')}}';" >
                       
                        <span class="text-danger email-error"></span>
                     </div>
                  
               </div>
             
               <div data-reactid="15" class="password-sec hide">

                  <div class="form-group mb-3">
                   
                       
                           <input class="form-control" id="password" placeholder="User Password" autocorrect="off" autocapitalize="off" name="password" data-reactid="21" type="password" value="{{ canDisplayCredentials() ? '123456':'' }}">
                        
                        <span class="text-danger email-error"></span>
                     
                  </div>
               </div>
               
               <button class="btn btn--arrow btn--full blue-signin-btn singin_rider email_phone-sec-1" data-reactid="22" data-type='email'><span class="push-small--right" data-reactid="23">Next</span><i class="fa fa-long-arrow-right icon icon_right-arrow-thin"></i></button>
             
            </form>
       </div>
       </div>


        


         <div class="wrap col-6 p-0">
						<div class="text-wrap p-4 p-lg-5 text-center d-flex align-items-center order-md-last">
							<div class="text  w-100">
								<h2>Welcome to Zcon</h2>
								<div class="welcome-logo">
                           <a href="{{url('/')}}">
                <img src="{{asset('setting/'. $setting->logo_img)}}" alt="logo">
                        </a>  
                        
								</div>
								<!-- <a href="#" class="btn btn-white btn-outline-white mt-3">Sign Up</a>-->
							</div>
						</div>
					</div>
          <div class="w-50 text-md-right">
         
            <p class=" display--inline email_phone-sec" data-reactid="27">Don't have an account?<a href="{{ url('signup_rider')}}">Sign Up</a></p>
            <p class="pull-right forgot password-sec hide">
               <a href="{{ url('forgot_password_rider')}}" class="forgot-password">Forgot Password ?</a>
            </p>
    
         </div>
    


</div>
</div>
</section>


</body>
</div>

</html>

<style>
body {
  font-family: "Lato", Arial, sans-serif;
  font-size: 16px;
  line-height: 1.8;
  font-weight: normal;
  background: #f8f9fd;
  color: gray;
}
.ftco-section {
  padding: 7em 0;
}

.wrap {
  width: 100%;
  border-radius: 5px;
  -webkit-box-shadow: 0px 10px 34px -15px rgb(0 0 0 / 24%);
  -moz-box-shadow: 0px 10px 34px -15px rgba(0, 0, 0, 0.24);
  box-shadow: 0px 10px 34px -15px rgb(0 0 0 / 24%);
}
.text-wrap {
  background: #7F3884;
  color: #fff;
  height: 100%;
}
.text-wrap .text h2 {
  font-weight: 900;
  color: #fff;
}
.btn.btn-white.btn-outline-white {
  border: 1px solid #fff;
  background: transparent;
  color: #fff;
}
.btn {
  cursor: pointer;
  -webkit-box-shadow: none !important;
  box-shadow: none !important;
  font-size: 15px;
  padding: 10px 20px;
  border-radius: 50px;
}
.login-wrap {
  position: relative;
  background: #fff;
}
.form-group .label {
  font-size: 12px;
  text-transform: uppercase;
  letter-spacing: 1px;
  color: #000;
  font-weight: 700;
}
.form-control {
  height: 48px;
  background: rgba(0, 0, 0, 0.05);
  color: #000;
  font-size: 16px;
  border-radius: 50px;
  -webkit-box-shadow: none;
  box-shadow: none;
  border: 1px solid transparent;
  padding-left: 20px;
  padding-right: 20px;
  -webkit-transition: all 0.2s ease-in-out;
  -o-transition: all 0.2s ease-in-out;
  transition: all 0.2s ease-in-out;
  display: block;
  width: 100%;
  font-weight: 400;
  line-height: 1.5;
  background-clip: padding-box;
}
.social-media {
  position: relative;
  width: 100%;
}
.social-media .social-icon {
  display: block;
  width: 40px;
  height: 40px;
  background: transparent;
  border: 1px solid rgba(0, 0, 0, 0.05);
  font-size: 16px;
  margin-right: 5px;
  border-radius: 50%;
}
.social-media .social-icon span {
  color: #999999;
}
.social-media .social-icon:hover, .social-media .social-icon:focus {
  background: #440653;
}
.social-media .social-icon:hover span, .social-media .social-icon:focus span {
  color: #fff;
}
.form-group .label {
  font-size: 12px;
  text-transform: uppercase;
  letter-spacing: 1px;
  color: #000;
  font-weight: 700;
}
.btn.btn-primary {
  background: #440653;
  border: 1px solid #440653;
  color: #fff;
}
.checkmark:after {
  content: "\f0c8";
  position: absolute;
  color: rgba(0, 0, 0, 0.1);
  font-size: 20px;
  margin-top: -4px;
  left: 46px;
  -webkit-transition: 0.3s;
  -o-transition: 0.3s;
  transition: 0.3s;
}
.checkbox-primary {
  color: #440653;
}

.checkbox-primary input:checked ~ .checkmark:after {
  color: #440653;
}
.form-control:focus, .form-control:active {
  outline: none !important;
  -webkit-box-shadow: none;
  box-shadow: none;
  background: rgba(0, 0, 0, 0.07);
  border-color: transparent;
}
.form-group a {
  color: gray;
  text-decoration: none;
}
.btn.btn-white.btn-outline-white:hover {
  border: 1px solid transparent;
  background: #fff;
  color: #000;
}
.welcome-logo img {
  height: 95px;
}
</style>



<script src="js/function.js" type="text/javascript"></script>
<script src="https://kit.fontawesome.com/c860a299a7.js" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js" integrity="sha384-SR1sx49pcuLnqZUnnPwx6FCym0wLsk5JZuNx2bPPENzswTNFaQU1RDvt3wT4gWFG" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js" integrity="sha384-j0CNLUeiqtyaRmlzUHCPZ+Gy5fQu0dQ6eZ/xAww941Ai1SxSY+0EQqNXNE6DZiVc" crossorigin="anonymous"></script>
<script src="{{asset('front_assets/js/scripts.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js" ></script>
{!! Html::script('js/jquery-1.11.3.js') !!}
  {!! Html::script('js/jquery-ui.js') !!}

  {!! Html::script('js/angular.js') !!}
  {!! Html::script('js/angular-sanitize.js') !!}
  <script>
    var app = angular.module('App', ['ngSanitize']);
    var APP_URL = {!! json_encode(url('/')) !!};
    var LOGIN_USER_TYPE = '{!! LOGIN_USER_TYPE !!}';
    var STRIPE_PUBLISH_KEY = "{{ payment_gateway('publish','Stripe') }}";
  </script>
<script>
  {!! Html::script('js/common.js?v='.$version) !!}
  {!! Html::script('js/user.js?v='.$version) !!}
  {!! Html::script('js/main.js?v='.$version) !!}
  {!! Html::script('js/bootstrap.min.js') !!}
  {!! Html::script('js/jquery.bxslider.min.js') !!}
  {!! Html::script('js/jquery.sliderTabs.min.js') !!}
  {!! Html::script('js/responsiveslides.js?v='.$version) !!}