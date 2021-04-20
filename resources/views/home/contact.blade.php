@extends('layouts.main')
@section('main.content')	
	
	<div class="contact-p">
        <div class="container">
            <div class="row">

                <!-- 1st Section -->
                <div class="col-md-8 offset-md-2 col-sm-12 cp-1st">
                    <div class="text-center">
                        <h1 class="display-1 fw-bold m-5">Hello</h1>
                        <h3 class="mb-5 text-secondary">What can we help you with?</h3>
                        <div class="row">
                            <div class="col-md-5 sm-12 px-0">
                                <a href="#" class="btn btn-danger d-block rounded-0 py-4">Get Started</a>
                            </div>
                            <div class="col-md-2 sm-12 py-4 px-0">OR</div>
                            <div class="col-md-5 sm-12 px-0">
                                <a href="#" class="btn btn-danger d-block rounded-0 py-4">Get Started</a>
                            </div>
                        </div>                        
                        <h3 class="mt-5 mb-2 text-secondary">For everything else</h3>
                        <a href="mailto:hello@example.com" class="text-danger">hello@example.com</a>
                    </div>
                </div>
                <!-- /1st Section -->

                <!-- 2nd Section -->
                <div class="col-md-8 offset-md-2 col-sm-12 cp-2nd my-5">
                    <div class="text-center">
                        <div class="row">
                            <div class="col-md-4 col-sm-12">
                                <h3>New York</h3>
                                <p>67E 11th St <br> New York, NY 10003, USA</p>
                                <a href="mailto:hello@example.com" class="text-danger">hello@example.com</a>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <h3>Croatia</h3>
                                <p>Strojarska 22 <br> 10000 Zagreb</p>
                                <a href="mailto:hello@example.com" class="text-danger">hello@example.com</a>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <h3>Slovenia</h3>
                                <p>Tehnološki park 22a <br> 1000 Ljubljana</p>
                                <a href="mailto:hello@example.com" class="text-danger">hello@example.com</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /2nd Section -->

                <!-- 3rd Section -->
                <div class="col-md-12 cp-3rd">
                    <div class="text-center">
                        <p class="text-secondary">We are currently in beta testing with our partner Cooperative Home Care Associates in the Bronx. We will be launching for all New Yorkers in early 2021.</p>
                    </div>
                </div>
                <!-- /3rd Section -->

            </div>
        </div>
    </div>
    
@endsection