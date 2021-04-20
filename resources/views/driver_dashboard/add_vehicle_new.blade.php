<title>Add Vehicle Details</title>
@extends('template_driver_dashboard') @section('main')
<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 flexbox__item four-fifths page-content" style="padding: 0px;" ng-controller="vehicle_details" ng-init="errors = {{json_encode($errors->getMessages())}};">
  <div class="page-lead separated--bottom text--center text--uppercase">
    <h1 class="flush-h1 flush">Add Vehicle Detail</h1>
  </div>

  {!! Form::open(['url' => 'update_vehicle', 'class' => '','id'=>'vehicle_form','files' => true]) !!}
  <div class="parter-info separated--bottom col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 25px 0px 15px;">
    <div class="col-lg-12 form-group">
      <label>{{trans('messages.driver_dashboard.vehicle_make')}}</label>
      {!! Form::select('vehicle_make_id',$make, '', ['class' => 'form-control', 'id' => 'vehicle_make', 'placeholder' => trans('messages.driver_dashboard.select')]) !!}
      <span class="text-danger">{{ $errors->first('vehicle_make_id') }}</span>
    </div>
    <div class="col-lg-12 form-group vehicle_model">
      <label>{{trans('messages.driver_dashboard.vehicle_model')}}</label>
      {!! Form::select('vehicle_model_id',$model, '', ['class' => 'form-control', 'id' => 'vehicle_model', 'placeholder' => trans('messages.driver_dashboard.select')]) !!}
      <span class="text-danger">{{ $errors->first('vehicle_model_id') }}</span>
    </div>
    <div class="col-lg-12 form-group">
      <label>{{trans('messages.driver_dashboard.vehicle_number')}}</label>
      {!! Form::text('vehicle_number','', ['class' => 'form-control', 'id' => 'vehicle_number', 'placeholder' => trans('messages.driver_dashboard.vehicle_number')]) !!}
      <span class="text-danger">{{ $errors->first('vehicle_number') }}</span>
    </div>
    <div class="col-lg-12 form-group">
      <label>{{trans('messages.driver_dashboard.vehicle_color')}}</label>
      {!! Form::text('color','', ['class' => 'form-control', 'id' => 'color', 'placeholder' => trans('messages.driver_dashboard.vehicle_color')]) !!}
      <span class="text-danger">{{ $errors->first('color') }}</span>
    </div>
    <div class="col-lg-12 form-group">
      <label>{{trans('messages.driver_dashboard.vehicle_year')}}</label>
      {!! Form::text('year','', ['class' => 'form-control', 'id' => 'year', 'placeholder' => trans('messages.driver_dashboard.vehicle_year'),'autocomplete'=>'off']) !!}
      <span class="text-danger">{{ $errors->first('year') }}</span>
    </div>
    <div class="col-lg-12 form-group">
      <label>{{trans('messages.driver_dashboard.vehicle_type')}}</label>
	      <div class="cls_vehicle">
	      @foreach($vehicle_type as $type)
	      <li class="col-lg-6 col-md-12 col-12">
	      <input type="checkbox" name="vehicle_type[]" id="vehicle_type" class="form-check-input vehicle_type" value="{{ $type->id }}" data-error-placement="container" data-error-container="#vehicle_type_error" /> {{ $type->car_name }}
	      </li>
	      @endforeach
	      <span class="text-danger">{{ $errors->first('vehicle_type') }}</span>
	    </div>
	</div>

    <div class="form-group">
      @foreach($documents as $document)
      <div class="col-lg-12 form-group">
        <label>{{$document->document_name}}</label>
        <input type="file" name="{{$document->doc_name}}" class="form-control" />
        <span class="text-danger">
          {{ $errors->first($document->doc_name) }}
        </span>
      </div>
      @if($document->expire_on_date == 'Yes')
      <div class="col-lg-12 form-group">
        <input type="date" min="{{ date('Y-m-d') }}" name="expired_date_{{$document->id}}" class="form-control" autocomplete="off" />
        <span class="text-danger">
          {{ $errors->first('expired_date_'.$document->id) }}
        </span>
      </div>
      @endif @endforeach
    </div>

  <div class="page-lead separated--bottom col-lg-12 col-md-12 col-sm-12 col-xs-12" style="border-bottom: 0px !important;">
    <button style="padding: 0px 30px !important; font-size: 14px !important;" type="submit" class="btn btn--primary btn-blue" id="update_btn">{{trans('messages.driver_dashboard.vehicle_add')}}</button>
  </div>
  {{ Form::close() }}
</div>
 </div>
</div>
</div>
</div>
</main>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" rel="stylesheet" />
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
