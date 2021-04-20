<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Zcon</title>
<!-- google fonts link -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('front_assets/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('front_assets/css/responsive.css')}}">
	<link rel="stylesheet" href="{{asset('front_assets/css/font-awesome.min.css')}}">
  

	@stack('header.css')

</head>

<body>
    <header class="header">
        <div class="container">
            <div class="header-content">
                <a href="#" class="logo">
                    <img src="{{asset('front_assets/img/logo.png')}}" alt="">
                </a>
                <a href="#" class="nav-toggler">
                    <span></span>
                    <span></span>
                    <span></span>
                </a>
                <div class="navigation-content">
                    <ul class="navigation">
                    	
                    	<li><a href="#">Login</a></li>
                    	<li><a href="#">Register</a></li>
<!--                         <li><a href="#">RIDE</a></li>
                        <li><a href="#">DRIVE</a></li>
                        
                        <li><a href="#">HELP</a></li> -->
                    </ul>
                    <a href="#" class="btn btn-primary">Become a Driver</a>
                </div>
            </div>
        </div>
    </header>