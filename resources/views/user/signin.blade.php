@extends('layouts.main')
@section('main.content')
<section class="singup-section">
  <div class="container singup-section-background">
      <div class="singup-title">
          <h1>Sign In</h1>
      </div>
      @if(Auth::user()==null)
      <div class="singup-content row">
          <div class="col-md-6 sinup-driver">
              <h2>Driver</h2>
              <p>Find everything you need to track your success on the road.</p>
              <a href="{{ url('signin_driver') }}" class="btn btn-for-driver ">
              <button class="btn sing-in-button" >Driver sign In <i class="fa fa-long-arrow-right icon icon_right-arrow-thin"></i></button>
              </a>
         </div>
          <div class="col-md-6 sinup-rider">
              <h2>Rider</h2>
              <p>Find everything you need to track your success on the road.</p>
              <a href="{{ url('signin_rider') }}" class="btn btn-for-rider">
              <button class="btn sing-in-button" >Rider sign In <i class="fa fa-long-arrow-right icon icon_right-arrow-thin"></i></button>
              </a>
          </div> 
      </div>
      @endif
  </div>
</section>


@endsection
