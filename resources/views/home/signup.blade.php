@extends('layouts.main')
@section('main.content')
{{-- no need this page  --}}
<section class="singup-section">
    <div class="container singup-section-background">
        <div class="singup-title">
            <h1>Sign Up</h1>
        </div>
        <div class="singup-content row">

            <div class="col-md-6 sinup-driver">
                <h2>Driver</h2>
                <p>Find everything you need to track your success on the road.</p>
                <buttonb class="btn sing-in-button" >Driver sign Up <i class="fa fa-long-arrow-right icon icon_right-arrow-thin"></i></buttonb>
           </div>

            <div class="col-md-6 sinup-rider">
                <h2>Rider</h2>
                <p>Find everything you need to track your success on the road.</p>
                <buttonb class="btn sing-in-button" >Rider sign Up <i class="fa fa-long-arrow-right icon icon_right-arrow-thin"></i></buttonb>
            </div>
        </div>
    </div>
</section>
    
@endsection