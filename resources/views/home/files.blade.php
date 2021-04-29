{{-- @extends('layouts.main')
@section('main.content') --}}

@extends('layouts.master')
@section('content')
@include('layouts.configurationMenu')

	<!-- Heading -->
    <div class="row m-5 text-center bg-dark text-white">
        <h2 class="display-2">Manager your files</h2>
    </div>
    <div style="height: 600px;">
        <div id="fm"></div>
    </div>
    <!-- /Heading -->
    
    
@endsection
