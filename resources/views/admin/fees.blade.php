@extends('layouts.master')
@section('css')
@endsection

@section('content')
<div class="fees-wrap content-wrapper">
	<section class="content-header">
		<h1>
		Fees
		</h1>
		<ol class="breadcrumb">
			<li>
				<a href="{{ url(LOGIN_USER_TYPE.'/dashboard') }}">
					<i class="fa fa-dashboard"></i>
					Home
				</a>
			</li>
			<li>
				<a href="#">
					Fees
				</a>
			</li>
			<li class="active">
				Edit
			</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-8 col-sm-offset-2">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title"> Fees Form </h3>
					</div>
					{!! Form::open(['url' => 'admin/fees', 'class' => 'form-horizontal']) !!}
					<div class="box-body">
						<div class="form-group">
							<label for="input_service_fee" class="col-sm-3 control-label">
								Driver Peak Fare
							</label>
							<div class="col-sm-7 col-md-5">
								<div class="input-group">
									{!! Form::text('driver_peak_fare', fees('driver_peak_fare'), ['class' => 'form-control', 'id' => 'input_driver_peak_fare', 'placeholder' => 'Driver Peak Fare']) !!}
									<div class="input-group-addon" >%</div>
									<span class="text-danger">{{ $errors->first('driver_peak_fare') }}</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="input_service_fee" class="col-sm-3 control-label">
								Black Car Fund Fee
							</label>
							<div class="col-sm-7 col-md-5">
								<div class="input-group">
									{!! Form::text('black_car_fund', fees('black_car_fund'), ['class' => 'form-control', 'id' => 'input_black_car_fund', 'placeholder' => 'Black Car Fund Fee']) !!}
									<div class="input-group-addon" >%</div>
									<span class="text-danger">{{ $errors->first('black_car_fund') }}</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="input_service_fee" class="col-sm-3 control-label">
								Sales Tax
							</label>
							<div class="col-sm-7 col-md-5">
								<div class="input-group">
									{!! Form::text('sales_tax', fees('sales_tax'), ['class' => 'form-control', 'id' => 'input_sales_tax', 'placeholder' => 'Sales Tax']) !!}
									<div class="input-group-addon" >%</div>
									<span class="text-danger">{{ $errors->first('sales_tax') }}</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="input_service_fee" class="col-sm-3 control-label">
								Driver Service Fee
							</label>
							<div class="col-sm-7 col-md-5">
								<div class="input-group">
									{!! Form::text('driver_service_fee', fees('driver_access_fee'), ['class' => 'form-control', 'id' => 'input_driver_service_fee', 'placeholder' => 'Driver Service Fee']) !!}
									<div class="input-group-addon" >%</div>
									<span class="text-danger">{{ $errors->first('driver_service_fee') }}</span>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label for="input_service_fee" class="col-sm-3 control-label">
								Cancellation Fee
							</label>
							<div class="col-sm-7 col-md-5">
								<div class="input-group">
									{!! Form::text('cancellation_fee', fees('cancellation_fee'), ['class' => 'form-control', 'id' => 'input_cancellation_dee', 'placeholder' => 'Cancellation Fee']) !!}
									<span class="text-danger">{{ $errors->first('cancellation_fee') }}</span>
								</div>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<button type="reset" class="btn btn-default" name="cancel">Cancel</button>
						<button type="submit" class="btn btn-info pull-right" name="submit" value="submit">Submit</button>
					</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</section>
</div>
@endsection
@push('scripts')
<style type="text/css">
	.input-group-addon {
		background-color: #eee !important;
	}
</style>
@endpush
