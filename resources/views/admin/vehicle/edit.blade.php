@extends('layouts.master')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" />
@endsection

@section('content')
<div class="content-wrapper" ng-controller="vehicle_management">
	<section class="content-header">

		<h1 style="display: inline-block; "> Edit Vehicles </h1>
		<ol style=" float: right;" class="breadcrumb">
			<li>
				<a href="{{ url(LOGIN_USER_TYPE.'/dashboard') }}"> <i class="fa fa-dashboard"> </i> Home </a>
			</li>
			<li>
				<a href="{{ url(LOGIN_USER_TYPE.'/vehicle') }}">/ Vehicles </a>
			</li>
			<li class="active">/ Edit </li>
		</ol>
	</section>
	<section class="content" ng-init='vehicle_id="{{$result->id}}"'>
		<div class="row">
			<div class="col-md-8 offset-2">
				<div class="card card-danger">
					<div class="card-header shadow p-3 mb-5 bg-body rounded with-border">
						<h3 class="card-title">Edit Vehicles Form</h3>
					</div>
					{!! Form::open(['url' => LOGIN_USER_TYPE.'/edit_vehicle/'.$result->id, 'class' => 'form-horizontal vehicle_form','files' => true,'id'=>'vehicle_form']) !!}
					{!! Form::hidden('user_country_code', @$result->user->country->phone_code, ['id' => 'user_country_code']) !!}
					<div class="card-body">
						<span class="text-danger">(*)Fields are Mandatory</span>
						@if (LOGIN_USER_TYPE!='company')
							<div class="form-group" ng-init='company_name = "{{$result->company_id}}"'>
								<label for="input_company" class="col-sm-3 control-label">Company Name <em class="text-danger">*</em></label>
								<div class="col-sm-8" ng-init='get_driver()'>
									{!! Form::select('company_name', $company, $result->status==null?0:$result->status, ['class' => 'form-control', 'id' => 'input_company_name', 'placeholder' => 'Select','ng-model' => 'company_name','ng-change' => 'get_driver()']) !!}
									<span class="text-danger">{{ $errors->first('company_name') }}</span>
								</div>
							</div>
						@else
							<span ng-init='company_name="{{Auth::guard("company")->user()->id}}";get_driver()'></span>
						@endif
						<div class="form-group">
							<label for="input_company" class="col-sm-3 control-label">Driver Name <em class="text-danger">*</em></label>
							<div class="col-sm-8" ng-init='driver_name = "{{$result->user_id}}";selectedDriver={{ $result->user_id }}'>
								<span class="loading" id="driver_loading" style="display: none;padding-left: 50%"></span>
								<select class='form-control' name="driver_name" id="input_driver_name" ng-model="selectedDriver" ng-change="updateVehicleType()" ng-cloak>
									<option value="">Select</option>
									<option ng-repeat="driver in drivers" value="@{{driver.id}}" ng-selected="@{{driver_name}} == @{{driver.id}}">@{{driver.first_name}} @{{driver.last_name}} - @{{driver.id}}</option>
								</select>
								<span class="text-danger" id="driver-error">{{ $errors->first('driver_name') }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="input_status" class="col-sm-3 control-label">Status <em class="text-danger">*</em></label>
							<div class="col-sm-8">
								{!! Form::select('status', array('Active' => 'Active', 'Inactive' => 'Inactive'), $result->status, ['class' => 'form-control', 'id' => 'input_status', 'placeholder' => 'Select']) !!}
								<span class="text-danger">{{ $errors->first('status') }}</span>
							</div>
						</div> 
						<div class="form-group">
			              <label for="input_status" class="col-sm-3 control-label">Make <em class="text-danger">*</em></label>
			              <div class="col-sm-8">
			                {!! Form::select('vehicle_make_id',$make, $result->vehicle_make_id, ['class' => 'form-control', 'id' => 'vehicle_make', 'placeholder' => 'Select']) !!}
			                <span class="text-danger">{{ $errors->first('vehicle_make_id') }}</span>
			              </div>
			            </div>
			            <div class="form-group">
			              <label for="input_status" class="col-sm-3 control-label">Model <em class="text-danger">*</em></label>
			              <div class="col-sm-8" ng-init='vehicle_model_id="{{ $result->vehicle_model_id }}";'>
			              	<span class="loading" id="model_loading" style="display: none;padding-left: 50%"></span>
			                {!! Form::select('vehicle_model_id', array(), '', ['class'=>'form-control', 'id'=>'vehicle_model', 'placeholder'=>'Select']) !!}
			                <span class="text-danger">{{ $errors->first('vehicle_make_id') }}</span>
			              </div>
			            </div>
						<div class="form-group cls_vehicle">
							<label for="vehicle_type" class="col-sm-3 control-label">Vehicle Type <em class="text-danger">*</em></label>
							<div class="col-sm-8 form-check">
								@php $vehicle_types = explode(',', $result->vehicle_id); @endphp
								@foreach($car_type as $type)
								<li class="col-lg-12">
									<input type="checkbox" name="vehicle_type[]" id="vehicle_type" class="form-check-input vehicle_type" value="{{ $type->id }}" data-error-placement="container" data-error-container="#vehicle_type_error" {{ in_array($type->id,$vehicle_types) ? 'checked' : '' }}/> <span> {{ $type->car_name }}</span>
									</li>
								@endforeach
								</br></br>
								<div class="text-danger" id="vehicle_type_error"></div>
								<span class="text-danger">{{ $errors->first('vehicle_type') }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="default" class="col-sm-3 control-label">Default <em class="text-danger">*</em></label>
							<div class="col-sm-8 form-check" style="padding-top: 6px;">
								{{ Form::radio('default', '1', $result->default_type=='1' ? true:false, ['class' => 'form-check-input default', 'id' => 'default_yes', 'data-error-placement'=>'container', 'data-error-container'=>'#default_error']) }} Yes
								{{ Form::radio('default', '0', $result->default_type=='0' ? true:false, ['class' => 'form-check-input default', 'id' => 'default_no', 'data-error-placement'=>'container', 'data-error-container'=>'#default_error']) }} No
								</br>
								<div class="text-danger" id="default_error"></div>
								<span style="color:gray;font-style: italic;"> * If the driver has only one vehicle with active status, it will be automatically selected as default.</span>
							</div>
						</div>
						<div class="form-group">
							<label for="vehicle_number" class="col-sm-3 control-label">Vehicle Number <em class="text-danger">*</em></label>
							<div class="col-sm-8">
								{!! Form::text('vehicle_number',@$result->vehicle_number, ['class' => 'form-control', 'id' => 'vehicle_number', 'placeholder' => 'Vehicle Number']) !!}
								<span class="text-danger">{{ $errors->first('vehicle_number') }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="color" class="col-sm-3 control-label">Color <em class="text-danger">*</em></label>
							<div class="col-sm-8">
								{!! Form::text('color',@$result->color, ['class' => 'form-control', 'id' => 'color', 'placeholder' => 'Color']) !!}
								<span class="text-danger">{{ $errors->first('color') }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="year" class="col-sm-3 control-label">Year <em class="text-danger">*</em></label>
							<div class="col-sm-8">
								{!! Form::text('year',@$result->year, ['class' => 'form-control', 'id' => 'year', 'placeholder' => 'Year']) !!}
								<span class="text-danger">{{ $errors->first('year') }}</span>
							</div>
						</div>
						<input type="hidden" id="vehicle_id" value="{{$result->id}}">
						<p ng-init='vehicle_doc="";errors={{json_encode($errors->getMessages())}};'></p>
						<span class="loading" id="document_loading" style="padding-left: 80%" ng-if="vehicle_doc==''"></span>
						<div class="form-group" ng-repeat="doc in vehicle_doc" ng-cloak ng-if="vehicle_doc">
							<div class="form-group">
							<label class="col-sm-3 control-label"> @{{doc.document_name}} <em class="text-danger">*</em></label>
							<div class="col-sm-8">
								<input type="file" name="file_@{{doc.id}}" class="form-control">
								<span class="text-danger">@{{ errors['file_'+doc.id][0] }}</span>								
								<div class="license-img" ng-if="doc.document">
									<a href="@{{doc.document}}" target="_blank">
										<img style="width: 200px;height: 100px;object-fit: contain;" ng-src="@{{doc.document}}">
									</a>
								</div>
								<div class="license-img" ng-if="!doc.document">
									<img style="width: 100px;height: 100px;object-fit: contain;" src="{{ url('images/driver_doc.png')}}">
								</div>
							</div>
							</div>
							<div class="form-group">
							<label class="col-sm-3 control-label" ng-if="doc.expiry_required=='1'">Expire Date <em class="text-danger">*</em></label>
							<div class="col-sm-8" ng-if="doc.expiry_required=='1'">
								<input type="text" name="expired_date_@{{doc.id}}" min="{{ date('Y-m-d') }}" value="@{{doc.expired_date}}" class="form-control document_expired" placeholder="Expire date" autocomplete="off">
								<span class="text-danger">@{{ errors['expired_date_'+doc.id][0] }}</span>
							</div>
							</div>
							<div class="form-group">
							<label class="col-sm-3 control-label">@{{doc.document_name}} Status <em class="text-danger">*</em></label>
							<div class="col-sm-8">
								<select class ='form-control' name='@{{doc.doc_name}}_status'>
									<option value="0" ng-selected="doc.status==0">Pending</option>
									<option value="1" ng-selected="doc.status==1">Approved</option>
									<option value="2" ng-selected="doc.status==2">Rejected</option>
								</select>
							</div>
						</div>
						</div>
					</div>
					<div class="card-footer shadow-sm bg-body rounded">
						<button type="submit" class="btn btn-info pull-right" name="submit" value="submit"> Submit </button>
						<a href="{{url(LOGIN_USER_TYPE.'/vehicle')}}"><span class="btn btn-default pull-left">Cancel</span></a>
					</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</section>
</div>


@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous"></script>
<script>
	$("#year").datepicker({
		format: "yyyy",
		viewMode: "years", 
		minViewMode: "years",
		autoclose : true,
		startDate: '1950',
		endDate: '<?php echo date('Y'); ?>'
	});
	</script>
@endsection
