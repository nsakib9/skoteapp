{{--  @extends('layouts.master')
@section('css')
@endsection
@section('content')  --}}
{{--  <div class="content-wrapper">  --}}
  @php
    if(LOGIN_USER_TYPE=='company'){
        $user = Auth::guard('company')->user();
        $company_user = true;
        $first_segment = 'company';
    }
    else{
        $user = Auth::guard('admin')->user();
        $company_user = false;
        $first_segment = 'admin';
    }
@endphp
  <nav class="navbar sticky-top navbar-expand-lg bg-light">
    <div class="container">
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto w-100 justify-content-center">
          @if(@$user->can('manage_locations'))
          <li class="nav-item active">
            <a class="nav-link" href="{{ url('admin/locations') }}">Locations</a>
          </li>
          @endif
          @if(@$user->can('manage_peak_based_fare'))
          <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/manage_fare') }}">Fares</a>
          </li>
          @endif
          @if(@$user->can('manage_fees'))
          <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/fees') }}">Fees</a>
          </li>
          @endif
          @if($company_user || @$user->can('manage_driver_payments'))
          <li class="nav-item">
            <a class="nav-link" href="{{ url($first_segment.'/payout/overall') }}">Payout</a>
          </li>
          @endif
          @if($company_user || @$user->can('manage_wallet'))
          <li class="nav-item">
            <a class="nav-link" href="{{ route('wallet',['user_type' => 'Rider']) }}">Wallet</a>
          </li>
          @endif
          @if(@$user->can('view_manage_reason'))
          <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/cancel-reason') }}">Cancellations</a>
          </li>
          @endif
          @if(@$user->can('manage_api_credentials'))
          <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/api_credentials') }}">API</a>
          </li>
          @endif
          @if(@$user->can('manage_payment_gateway'))
          <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/payment_gateway') }}">Payment Gateway</a>
          </li>
          @endif
          @if(@$user->can('manage_referral_settings'))
          <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/referral_settings') }}">Referrals</a>
          </li>
          @endif
          @if(@$user->can('manage_language'))
          <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/language') }}">Language</a>
          </li>
          @endif
          @if(@$user->can('manage_metas'))
          <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/metas') }}">Meta</a>
          </li>
          @endif
          @if(@$user->can('manage_country'))
          <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/country') }}">Country</a>
          </li>
          @endif
          @if(@$user->can('manage_currency'))
          <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/currency') }}">Currency</a>
          </li>
          @endif
          <li class="nav-item">
            <a class="nav-link" href="#">Website</a>
          </li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Documents(new) <b class="caret"></b></a>
            <ul class="dropdown-menu ">
              <li><a href="{{ url($first_segment.'/files') }}">File Manager</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
{{--  </div>  --}}
{{--  @stop  --}}