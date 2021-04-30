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
    <style>
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
