@extends('layouts.master')
@section('css')
@endsection

@section('content')
<div class="content-wrapper">
	<section class="content-header">

		@include('layouts.configurationMenu')

		<h1> Payment Gateway </h1>
		<ol class="breadcrumb">
			<li>
				<a href="{{ url(LOGIN_USER_TYPE.'/dashboard') }}"> <i class="fa fa-dashboard"></i> Home </a>
			</li>
			<li>
				<a href="{{ url(LOGIN_USER_TYPE.'/payment_gateway') }}"> Payment Gateway </a>
			</li>
			<li class="active"> Edit </li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-8 col-sm-offset-2">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title"> Payment Gateway Form </h3>
					</div>
					{!! Form::open(['url' => 'admin/payment_gateway', 'class' => 'form-horizontal']) !!}
					<div class="box-body">
						<span class="text-danger">(*)Fields are Mandatory</span>
						<!-- Stripe Section Start -->
						<div class="box-body" ng-init="stripe_enabled={{ old('stripe_enabled',payment_gateway('is_enabled','Stripe')) }}">
							<div class="form-group">
								<label for="input_stripe_publish_key" class="col-sm-3 control-label"> Stripe Key <em ng-show="stripe_enabled == '1'" class="text-danger">*</em></label>
								<div class="col-sm-6">
									{!! Form::text('stripe_publish_key', old('stripe_publish_key',payment_gateway('publish','Stripe')), ['class' => 'form-control', 'id' => 'input_stripe_publish_key', 'placeholder' => 'Stripe Key']) !!}
									<span class="text-danger">{{ $errors->first('stripe_publish_key') }}</span>
								</div>
							</div>
							<div class="form-group">
								<label for="input_stripe_secret_key" class="col-sm-3 control-label"> Stripe Secret <em ng-show="stripe_enabled == '1'" class="text-danger">*</em></label>
								<div class="col-sm-6">
									{!! Form::text('stripe_secret_key', old('stripe_secret_key',payment_gateway('secret','Stripe')), ['class' => 'form-control', 'id' => 'input_stripe_secret_key', 'placeholder' => 'Stripe Secret']) !!}
									<span class="text-danger">{{ $errors->first('stripe_secret_key') }}</span>
								</div>
							</div>
							<div class="form-group">
								<label for="input_stripe_api_version" class="col-sm-3 control-label"> Stripe API Version <em ng-show="stripe_enabled == '1'" class="text-danger">*</em></label>
								<div class="col-sm-6">
									{!! Form::text('stripe_api_version', old('stripe_api_version',payment_gateway('api_version','Stripe')), ['class' => 'form-control', 'id' => 'input_stripe_api_version', 'placeholder' => 'Stripe API Version']) !!}
									<span class="text-danger">{{ $errors->first('stripe_api_version') }}</span>
								</div>
							</div>
						</div>
					</div>
					<!-- Stripe Section End -->

					<!-- Payout Methods Section Start -->
						<div class="box-body">
							<div class="form-group">
								<label for="input_payout_methods" class="col-sm-3 control-label"> Payout Methods <em class="text-danger">*</em> </label>
								<div class="col-sm-6">
									@foreach(PAYOUT_METHODS as $payout_method)
									<div ng-init="payout_method_{{ $payout_method['key'] }}={{ isPayoutEnabled($payout_method['key']) }}">
										<input disabled="" type="checkbox" name="payout_methods[]" id="payout_method-{{ $payout_method['key'] }}" value="{{ $payout_method['key'] }}" ng-checked="{{ isPayoutEnabled($payout_method['key']) }}"> <label for="payout_method-{{ $payout_method['key'] }}" ng-model="payout_method_{{ $payout_method['key'] }}"> {{ $payout_method["value"] }} </label>
									</div>
									@endforeach
								</div>
							</div>
					</div>
					<!-- Payout Methods Section End -->

					<div class="box-footer">
						<button type="submit" class="btn btn-info pull-right" name="submit" value="submit">Submit</button>
						<button type="reset" class="btn btn-default pull-left"> Cancel </button>
					</div>
				</div>
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</section>
</div>
@endsection
