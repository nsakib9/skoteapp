@extends('layouts.master')

@section('css')
   
@endsection

@section('content')
<div class="content-wrapper" ng-controller="destination_admin">
	<section class="content-header">
		<h1 style="display: inline-block; "> Edit Setting </h1>
		<ol style=" float: right;" class="breadcrumb">
			<li><a href="{{ url(LOGIN_USER_TYPE.'/dashboard') }}"><i class="fa fa-dashboard"></i> Home </a></li>
			<li><a href="{{ url(LOGIN_USER_TYPE.'/setting') }}"> /Setting </a></li>
			<li class="active"> /Edit </li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-8 offset-2">
				<div class="card card-danger">
					<div class="card-header shadow p-3 mb-5 bg-body rounded with-border">
						<h3 class="card-title">Edit Setting Form</h3>
					</div>
					<form action="{{ url('admin/edit_setting', $setting->id)}}" method="post" enctype="multipart/form-data">
						@csrf
					{{-- {!! Form::open(['url' => 'admin/add_setting', 'enctype' => 'multipart/formdata' 'class' => 'form-horizontal']) !!} --}}
					<div class="card-body">
						<span class="text-danger">(*)Fields are Mandatory</span>
						<div class="form-group">
							<label class="col-sm-3 control-label">Site Logo <em class="text-danger">*</em></label>
							<div class="row">
							<div class="col-sm-7">
								<input type="file" name="logo_img"  class="form-control document_file">
							</div>
							<div class="col-sm-1">
								<img src="{{url('/setting/'."$setting->logo_img")}}" width="70" alt="Image"/>
							</div>
						</div>
						</div>
						<br><br>
						<div class="form-group">
							<label for="input_button_one" class="col-sm-3 control-label">Button Text<em class="text-danger">*</em></label>
							<div class="col-sm-8">
								<input type="text" name="button_text" value="{{$setting->button_text }}" class="form-control">

								{{-- {!! Form::text('button_one', '', ['class' => 'form-control', 'id' => 'input_button_one', 'placeholder' => 'Button One']) !!} --}}
								<span class="text-danger">{{ $errors->first('button_text') }}</span>
							</div>
						</div>
					</div>
					<br><br>
					<div class="card-footer shadow-sm bg-body rounded">
						<button type="submit" class="btn btn-info pull-right" name="submit" value="submit">Submit</button>
						<button type="submit" class="btn btn-default pull-left" name="cancel" value="cancel">Cancel</button>
					</div>
					{{-- {!! Form::close() !!} --}}
				</form>
				</div>
			</div>
		</div>
	</section>
</div>
@endsection
