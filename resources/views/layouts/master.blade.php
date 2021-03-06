<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title> @yield('title') | Zcon </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon.png') }}">
    @include('layouts.head-css')


    <!-- CSRF Token file manager -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- font awesome  --}}
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">
    {{-- file manager styles --}}
    <link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
    
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
li {
  float: left;
}

  li a {
  display: block;
  color: white;
  
  padding: 14px 16px;
  text-decoration: none;
}


li a:hover {
  background-color: #cfa233;
  color: white;
  text-decoration: none;
}


.overflow-h{overflow: hidden;}
.card.mini-stats-wid {
	margin: 10px 0;
	box-shadow: 0 0 3px #ccc;
	padding: 10px;
}
.card.mini-stats-wid a {
	width: 50%;
	background: #6F456E;
	padding: 3px 5px;
	color: #fff;
	border-top-right-radius: 50px;
	border-bottom-right-radius: 50px;
	transition: 0.3s ease-in-out;
	display: flex;
	justify-content: space-between;
	align-items: baseline;
}
.card.mini-stats-wid a:hover {
	width: 100%;
}
.avatar-title {
	background-color: #C69727 !important;
}

.conf_menu {}
.conf_menu .navbar-nav > li > a{
    padding-top: 5px;
    padding-bottom: 5px;
}
    .conf_menu ul.navbar-nav {
        display: inline;
    }
    .conf_menu li {
        width: max-content;
        display: inline-flex;
        align-items: center;
        background: #fff;
        margin: 5px;
        padding: 0 5px;
        border: 1px solid #DBDBDB;
    }
    .conf_menu li:hover,
    .conf_menu li.dropdown:hover {
        background: #420653;
    }
    .conf_menu li:hover a.nav-link{
        color: #fff;    
    }
    .conf_menu li:hover a.dropdown-toggle {
    color: #fff;
    }
    .conf_menu li.dropdown {
        padding: 0px 10px !important;
    }
    .conf_menu li.dropdown a:hover {
    background: none;
    color: #fff;
    }
    .conf_menu a {
        padding: 3px 0;
        color: #000;
    }
    .navbar {
        z-index: 9;
    }
        
    </style>

</head>

@section('body')


    <body data-sidebar="dark">
@show
    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('layouts.topbar')
        @include('layouts.sidebar')
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            @include('layouts.footer')
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    <!-- Right Sidebar -->
    @include('layouts.right-sidebar')
    <!-- /Right-bar -->

    <!-- JAVASCRIPT -->
    @include('layouts.vendor-scripts')

    
{{-- file manager js --}}
<script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>
</body>

</html>
