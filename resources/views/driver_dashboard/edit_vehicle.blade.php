<title>{{ trans('messages.driver_dashboard.edit') }} {{ trans('messages.driver_dashboard.vehicle_details') }}</title>
@extends('template_driver_dashboard')
@section('main')
<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 flexbox__item four-fifths page-content" style="padding:0px;" ng-controller="vehicle_details">
  <div class="page-lead separated--bottom  text--center text--uppercase">
    <h1 class="flush-h1 flush">{{ trans('messages.driver_dashboard.edit') }} {{ trans('messages.driver_dashboard.vehicle_details') }}</h1>
  </div>
 	
	{!! Form::open(['url' => 'update_vehicle', 'class' => '','id'=>'vehicle_form','files' => true]) !!}
	<div class="parter-info separated--bottom col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 25px 0px 15px;">
		<div class="col-lg-12 form-group">
		    <label >{{ trans('messages.driver_dashboard.vehicle_make') }}</label>
		   	 {!! Form::select('vehicle_make_id',$make, $result->vehicle_make_id, ['class' => 'form-control', 'id' => 'vehicle_make', 'placeholder' => trans('messages.driver_dashboard.select') ]) !!}
			  <span class="text-danger">{{ $errors->first('vehicle_make_id') }}</span>
	  	</div>
	  	<div class="col-lg-12 form-group vehicle_model">
		    <label>{{ trans('messages.driver_dashboard.vehicle_model') }}</label>
		   	 {!! Form::select('vehicle_model_id',$model, $result->vehicle_model_id, ['class' => 'form-control', 'id' => 'vehicle_model', 'placeholder' => trans('messages.driver_dashboard.select') ]) !!}
			 <span class="text-danger">{{ $errors->first('vehicle_model_id') }}</span>
	  	</div>
	  	<div class="col-lg-12 form-group">
	  		<label>{{ trans('messages.driver_dashboard.vehicle_number') }}</label>
	  		{!! Form::text('vehicle_number',$result->vehicle_number, ['class' => 'form-control', 'id' => 'vehicle_number', 'placeholder' => trans('messages.driver_dashboard.vehicle_number') ]) !!}
			<span class="text-danger">{{ $errors->first('vehicle_number') }}</span>
	  	</div>
	  	<div class="col-lg-12 form-group">
	  		<label>{{ trans('messages.driver_dashboard.vehicle_color') }}</label>
	  		{!! Form::text('color',$result->color, ['class' => 'form-control', 'id' => 'color', 'placeholder' => trans('messages.driver_dashboard.vehicle_color') ]) !!}
			<span class="text-danger">{{ $errors->first('color') }}</span>
	  	</div>
	  	<div class="col-lg-12 form-group">
	  		<label>{{ trans('messages.driver_dashboard.vehicle_year') }}</label>
	  		{!! Form::text('year',$result->year, ['class' => 'form-control', 'id' => 'year', 'placeholder' => trans('messages.driver_dashboard.vehicle_year'),'autocomplete'=>'off']) !!}
			<span class="text-danger">{{ $errors->first('year') }}</span>
	  	</div>
	  	<div class="col-lg-12 form-group">
		    <label>{{ trans('messages.driver_dashboard.vehicle_type') }}</label>
		    <div class="cls_vehicle">
		     @php $vehicle_types = explode(',', $result->vehicle_id); @endphp
		     
		   	 @foreach($vehicle_type as $type)
		   	 <li class="col-lg-6 col-md-12 col-12">
				<input type="checkbox"  name="vehicle_type[]" class="form-check-input vehicle_type" value="{{ $type->id }}" 
				{{ in_array($type->id,$vehicle_types) ? "checked" : "" }} /> {{ $type->car_name }}
			</li>
			@endforeach
			 <span class="text-danger">{{ $errors->first('vehicle_type') }}</span>
			</div>
	  	</div>

	  	  	
		@foreach($vehicle_documents as $document)	  	
		  	<div class="col-lg-12 form-group">
			  	<label>{{$document->document_name}}</label>
			  	<input type="file" name="{{$document->doc_name}}" class="form-control">
			  	<span class="text-danger">
			  		{{ $errors->first($document->doc_name) }} 
			  	</span>
			  	@php $image = ($document->document !='') ? storage_url($document->document) : url('images/driver_doc.png'); @endphp
			  	<div class="license-img">
					<a href="{{$image}}" target="_blank">
						<img style="width: 200px;height: 100px;object-fit: cover;" src="{{$image}}">
					</a>
				</div>		  		
		  	</div>		  	
		  	@if($document->expiry_required == '1')
		  	<div class="col-lg-12 form-group">
			  	<input type="date" min="{{ date('Y-m-d') }}" name="expired_date_{{$document->id}}" class="form-control" value="{{$document->expired_date}}">
			  	<span class="text-danger"> 
			  		{{ $errors->first('expired_date_'.$document->id) }}
			  	</span>		  		
		  	</div>
		  	@endif
		@endforeach
	  	</div>
	  	
	<input type="hidden" name="vehicle_id" value="{{$result->id ?? ''}}">
	<div class="page-lead separated--bottom col-lg-12 col-md-12 col-sm-12 col-xs-12" style="border-bottom:0px !important;">
	  <button style="padding: 0px 30px !important;
	  font-size: 14px !important;" type="submit" class="btn btn--primary btn-blue" id="update_btn">{{trans('messages.user.update')}}</button>
	</div>
{{ Form::close() }}
</div>
</div>
</div>
</div>
</main>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" rel="stylesheet"/>
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
