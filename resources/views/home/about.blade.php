@extends('layouts.main')
@section('main.content')

	<!-- Heading -->
    <div class="about-p-heading">
        <h3 class="fw-bold">Zcon is based on several key principles:</h3>
    </div>
    <!-- /Heading -->
    
    <!-- 1st section -->
    <section class="about-img-c">
        <div class="container-fluid px-0">
            <div class="row g-0">
                <div class="col-md-6 col-sm-12 p-0">
                    <div class="image">
                        <img src="{{asset('front_assets/img/women_rider.jpg')}}" class="img-fluid" alt="image">
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 p-5 bg-1">
                    <div class="d-flex align-items-center h-100">
                        <div class="content">
                            <h3>Here will be placed the title</h3>
                            <p>We are a driver-owned ridehailing cooperative in New York City. Drivers make 8-10% more on each trip, all profits go back to drivers, and drivers have democratic control over the decisions that affect their lives.</p>

                            <p>Our mission is to end exploitative conditions in the for-hire vehicle industry through system change– putting drivers in the driver’s seat of the platform economy.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /1st section -->
    
    <!-- 2nd section -->
    <section class="about-img-c">
        <div class="container-fluid px-0">
            <div class="row g-0">
                <div class="col-md-6 col-sm-12 p-5 bg-2">
                    <div class="d-flex align-items-center h-100">
                        <div class="content">
                            <h3>Here will be placed the title</h3>
                            <p>We are a driver-owned ridehailing cooperative in New York City. Drivers make 8-10% more on each trip, all profits go back to drivers, and drivers have democratic control over the decisions that affect their lives.</p>

                            <p>Our mission is to end exploitative conditions in the for-hire vehicle industry through system change– putting drivers in the driver’s seat of the platform economy.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 p-0">
                    <div class="image">
                        <img src="{{asset('front_assets/img/man_rider.jpg')}}" class="img-fluid" alt="image">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /2nd section -->
    
    <!-- 3rd section -->
    <section class="about-img-c">
        <div class="container-fluid px-0">
            <div class="row g-0">
                <div class="col-md-6 col-sm-12 p-0">
                    <div class="image">
                        <img src="{{asset('front_assets/img/women_out_taxi.jpg')}}" class="img-fluid" alt="image">
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 p-5 bg-3">
                    <div class="d-flex align-items-center h-100">
                        <div class="content">
                            <h3>Here will be placed the title</h3>
                            <p>We are a driver-owned ridehailing cooperative in New York City. Drivers make 8-10% more on each trip, all profits go back to drivers, and drivers have democratic control over the decisions that affect their lives.</p>

                            <p>Our mission is to end exploitative conditions in the for-hire vehicle industry through system change– putting drivers in the driver’s seat of the platform economy.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /3rd section -->

    
    <!-- Get Started -->
    <div class="about-started py-5 text-center">
        <h3 class="lh-base pb-2">We will be launching for all New Yorkers <br> in early 2021.</h3>
        <a href="#" class="btn btn-primary">Get Started</a>
    </div>
    <!-- /Get Started -->
    
@endsection