@extends('layouts.main')
@section('main.content')


<!--Banner Section-->
<section class="hero-section" style="background-image:url({{url('/banner/'."$banner->banner_img")}})">
    <div class="container">
        <div class="hero-content text-center">
            <h1 class="text-bold text-uppercase">{{ $banner->line_one }}</h1>
            <h1 class="text-uppercase mt-0">{{ $banner->line_two }}</h1>
            <!--                 <h2 class="text-uppercase mt-30">We will treat you right</h2> -->
            <div class="buttons mt-40">
                <a href="#" class="btn btn-primary">{{ $banner->button_one }}</a>
                <a href="#" class="btn btn-primary">{{ $banner->button_two }}</a>
            </div>
        </div>
    </div>
</section>

<!-- Services -->
<div class="services">
    <div class="container">
        <div class="row px-2">
            <div class="col-lg-3 col-md-6 col-6 p-0">
                <img src="{{asset('front_assets/img/c.png')}}" class="img-fluid" alt="cs.png">
            </div>
            <div class="col-lg-3 col-md-6 col-6 p-0">
                <img src="{{asset('front_assets/img/q.png')}}" class="img-fluid" alt="qs.png">
            </div>
            <div class="col-lg-3 col-md-6 col-6 p-0">
                <img src="{{asset('front_assets/img/r.png')}}" class="img-fluid" alt="rs.png">
            </div>
            <div class="col-lg-3 col-md-6 col-6 p-0">
                <img src="{{asset('front_assets/img/w.png')}}" class="img-fluid" alt="ws.png">
            </div>
        </div>
    </div>
</div>
    <!-- /Services -->

{{--  Image Gellery Section  --}}
<section class="section-one">
    <div class="container">
        <div class="content text-center">
            <h2 class="text-bold text-uppercase">Celebrating the Pillars of the Community</h2>
            <p class="text-bold text-uppercase">
                THE ZCON TEAM WILL RESPECT AND TREAT YOU RIGHT!
            </p>
            <div class="img-grid mt-30">
                <div class="item">
                    <img src="{{asset('front_assets/img/Woman in headscarf.png')}}" alt="" class="img-responsive">
                </div>
                <div class="item">
                    <img src="{{asset('front_assets/img/african-lady.jpg')}}" alt="" class="img-responsive">
                </div>
                <div class="item">
                    <img src="{{asset('front_assets/img/Asian Lady.png')}}" alt="" class="img-responsive">
                </div>
                <div class="item">
                    <img src="{{asset('front_assets/img/white_lady-2.jpg')}}" alt="" class="img-responsive">
                </div>
            </div>
        </div>
    </div>
</section>


<section>
    <div class="container">
        <div class="support-section">
            <h2 class="text-bold text-uppercase mb-4">Supported By</h2>
            <div class="row">
                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <img src="{{asset('front_assets/img/TWILIO.png')}}" class="img-fluid" alt="cs.png">
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <img src="{{asset('front_assets/img/AWS.png')}}" class="img-fluid" alt="qs.png">
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <img src="{{asset('front_assets/img/APPLE.png')}}" class="img-fluid" alt="rs.png">
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <img src="{{asset('front_assets/img/GOOGLE.png')}}" class="img-fluid" alt="ws.png">
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <img src="{{asset('front_assets/img/AZURE.png')}}" class="img-fluid" alt="cs.png">
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <img src="{{asset('front_assets/img/SINCH.png')}}" class="img-fluid" alt="qs.png">
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <img src="{{asset('front_assets/img/FACEBOOK.png')}}" class="img-fluid" alt="rs.png">
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <img src="{{asset('front_assets/img/ameteklogo.png')}}" class="img-fluid" alt="ws.png">
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <img src="{{asset('front_assets/img/EDUTECH%20GROUP.png')}}" class="img-fluid" alt="ws.png">
                </div>
            </div>
        </div>
    </div>
</section>
@endsection