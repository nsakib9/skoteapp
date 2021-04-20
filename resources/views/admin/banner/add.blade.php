@extends('layouts.master')

@section('css')
   
@endsection

@section('content')
<div class="content-wrapper" ng-controller="destination_admin">
	<section class="content-header">

		<h1 style="display: inline-block; "> Add Banner </h1>
		<ol style=" float: right;" class="breadcrumb">
			<li>
				<a href="{{ url(LOGIN_USER_TYPE.'/dashboard') }}"> <i class="fa fa-dashboard"> </i> Home </a>
			</li>
			<li>
				<a href="{{ url(LOGIN_USER_TYPE.'/banner') }}">/ Banners </a>
			</li>
			<li class="active">/ Add </li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-8 offset-2">
				<div class="card card-danger">
					<div class="card-header shadow p-3 mb-5 bg-body rounded with-border">
						<h3 class="card-title"> Add Banner Form </h3>
					</div>
					<form action="{{ url('admin/add_banner')}}" method="post" enctype="multipart/form-data">
						@csrf
					{{-- {!! Form::open(['url' => 'admin/add_banner', 'enctype' => 'multipart/formdata' 'class' => 'form-horizontal']) !!} --}}
					<div class="card-body">
						<span class="text-danger">(*)Fields are Mandatory</span>
						<div class="form-group">
							<label for="input_line_one" class="col-sm-3 control-label">Line One<em class="text-danger">*</em></label>
							<div class="col-sm-8">
								<input type="text" name="line_one" placeholder="Line One" class="form-control">
								{{-- {!! Form::text('line_one', '', ['class' => 'form-control', 'id' => 'input_line_one', 'placeholder' => 'Line One']) !!} --}}
								<span class="text-danger">{{ $errors->first('line_one') }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="input_line_two" class="col-sm-3 control-label">Line Two<em class="text-danger">*</em></label>
							<div class="col-sm-8">
								<input type="text" name="line_two" placeholder="Line Two" class="form-control">

								{{-- {!! Form::text('line_two', '', ['class' => 'form-control', 'id' => 'input_line_two', 'placeholder' => 'Line Two']) !!} --}}
								<span class="text-danger">{{ $errors->first('line_two') }}</span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Banner Image <em class="text-danger">*</em></label>
							<div class="col-sm-8">
								<input type="file" name="banner_img" class="form-control document_file">
							</div>
						</div>
						<div class="form-group">
							<label for="input_button_one" class="col-sm-3 control-label">Button One<em class="text-danger">*</em></label>
							<div class="col-sm-8">
								<input type="text" name="button_one" placeholder="Button One" class="form-control">

								{{-- {!! Form::text('button_one', '', ['class' => 'form-control', 'id' => 'input_button_one', 'placeholder' => 'Button One']) !!} --}}
								<span class="text-danger">{{ $errors->first('button_one') }}</span>
							</div>
						</div>
						<div class="form-group">
							<label for="input_button_two" class="col-sm-3 control-label">Button Two<em class="text-danger">*</em></label>
							<div class="col-sm-8">
								<input type="text" name="button_two" placeholder="Button Two" class="form-control">

								{{-- {!! Form::text('button_two', '', ['class' => 'form-control', 'id' => 'input_button_two', 'placeholder' => 'Button Two']) !!} --}}
								<span class="text-danger">{{ $errors->first('button_two') }}</span>
							</div>
						</div>
					</div>
					
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
