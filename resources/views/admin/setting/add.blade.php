@extends('layouts.master')

@section('css')
   
@endsection

@section('content')
<div class="content-wrapper" ng-controller="destination_admin">
	<section class="content-header">

		<h1 style="display: inline-block; "> Add Setting </h1>
		<ol style=" float: right;" class="breadcrumb">
			<li>
				<a href="{{ url(LOGIN_USER_TYPE.'/dashboard') }}"> <i class="fa fa-dashboard"> </i> Home </a>
			</li>
			<li>
				<a href="{{ url(LOGIN_USER_TYPE.'/setting') }}">/ Settings </a>
			</li>
			<li class="active">/ Add </li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-8 offset-2">
				<div class="card card-danger">
					<div class="card-header shadow p-3 mb-5 bg-body rounded with-border">
						<h3 class="card-title"> Add Setting Form </h3>
					</div>
					<form action="{{ url('admin/add_setting')}}" method="post" enctype="multipart/form-data">
						@csrf
					<div class="card-body">
						<span class="text-danger">(*)Fields are Mandatory</span>
						<div class="form-group">
							<label class="col-sm-3 control-label">Setting Logo <em class="text-danger">*</em></label>
							<div class="col-sm-8">
								<input type="file" name="logo_img" class="form-control document_file">
							</div>
						</div>
						<br><br>
						<div class="form-group">
							<label for="input_button_one" class="col-sm-3 control-label">Button Text<em class="text-danger">*</em></label>
							<div class="col-sm-8">
								<input type="text" name="button_text" placeholder="Button Text" class="form-control">

								<span class="text-danger">{{ $errors->first('button_text') }}</span>
							</div>
						</div>
					</div>
					<br><br>
					<div class="card-footer shadow-sm bg-body rounded">
						<button type="submit" class="btn btn-info pull-right" name="submit" value="submit">Submit</button>
						<button type="submit" class="btn btn-default pull-left" name="cancel" value="cancel">Cancel</button>
					</div>
				</form>
				</div>
			</div>
		</div>
	</section>
</div>
@endsection
