<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Zcon</title>
    <link rel="shortcut icon" type="image/jpg" href="front_assets/img/favicon.png"/>
<!-- google fonts link -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('front_assets/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('front_assets/css/responsive.css')}}">
	<link rel="stylesheet" href="{{asset('front_assets/css/font-awesome.min.css')}}">

    <style>
        @foreach($customCSS as $css)
            {{$css->csscode}}
       @endforeach
    </style>

    @stack('header.css')

</head>

<body ng-app="App"> 

<header class="header sticky-wrapper" id="sticker">
    <div class="container">
        <nav class="header-content">
            <a href="/" class="logo">
                <img src="{{asset('setting/'. $setting->logo_img)}}" alt="logo">
            </a>
            <a href="#" class="nav-toggler">
                <span></span>
                <span></span>
                <span></span>
            </a>
            <div class="navigation-content">
                <ul class="navigation">
                    @foreach($menus->where('type', 'header') as $menu)
                        <li><a href="{{url("$menu->url")}}">{{$menu->name}}</a></li>
                    @endforeach
                </ul>
                <a href="#" class="btn btn-primary">{{$setting->button_text}}</a>
                <div class="side-menu">
                    <i class="fas fa-bars"></i>
                </div>


<!-- resource/views/laouts/pertials/header.blade.php -->
{{-- <div id="sidenav" class="sidenav d-none">
    <div class="sideber-menu-item">
        <div class="siderber-logo mb-50">
        <img src="{{asset('front_assets/img/logo.png')}}">
        </div>
        <div  class="closebtn" >&times;</div>
    
        <a href="/about">About</a>
    	<a href="/pricing">Pricing</a>
   		<a href="/admin/login">Login</a>
    	<a href="/admin/login">Register</a>
    	<a href="/contact">Contact Us</a>
    </div>
    <div class="flex mt-30 sideber-socalicon">
        <ul class="list-unstyled social">
            <li>
                <a href="#">
                    <i class="fa fa-facebook"></i>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-instagram"></i>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-twitter"></i>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-youtube-play"></i>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-pinterest"></i>
                </a>
            </li>
        </ul>
    </div>
</div>   --}}

<div id="sidenav" class="sidenav d-none">
    <div class="sideber-menu-item">
        <a href="/" class="siderber-logo mb-50">
            <img src="{{asset('front_assets/img/logo.png')}}">
        </a>
        <a  class="closebtn" >&times;</a>
        <div class="sideber-menu-item-hover">
         @foreach($menus->where('type', 'sidebar') as $menu)
            <a href="{{url("$menu->url")}}">{{$menu->name}}</a>
         @endforeach
        </div>
    </div>


    <div class="flex mt-30 sideber-socalicon">
        <ul class="list-unstyled social">
            <li>
                <a href="#">
                    <i class="fa fa-facebook"></i>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-instagram"></i>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-twitter"></i>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-youtube-play"></i>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-pinterest"></i>
                </a>
            </li>
        </ul>
    </div>
</div>
            
            
            

            </div>
        </nav>
    </div>
</header>