@extends('layouts.master')

@section('css')
   
@endsection

@section('content')
<div class="content-wrapper" ng-controller="destination_admin">
	<section class="content-header">

		<h1 style="display: inline-block; "> Add Menu </h1>
		<ol style=" float: right;" class="breadcrumb">
			<li>
				<a href="{{ url(LOGIN_USER_TYPE.'/dashboard') }}"> <i class="fa fa-dashboard"> </i> Home </a>
			</li>
			<li>
				<a href="{{ url(LOGIN_USER_TYPE.'/menu') }}">/ Menu </a>
			</li>
			<li class="active">/ Add </li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-8 offset-2">
				<div class="card card-danger">
					<div class="card-header shadow p-3 mb-5 bg-body rounded with-border">
						<h3 class="card-title"> Add Menu Form </h3>
					</div>
					<form action="{{ url('admin/add_menu')}}" method="post" enctype="multipart/form-data">
						@csrf
					<div class="card-body">
						<span class="text-danger">(*)Fields are Mandatory</span>
						<div class="form-group">
							<label class="col-sm-3 control-label">Menu Name <em class="text-danger">*</em></label>
							<div class="col-sm-8">
								<input type="text" name="name" placeholder="menu name" class="form-control">

								<span class="text-danger">{{ $errors->first('name') }}</span>
							</div>
						</div>
						<br><br>
						<div class="form-group">
							<label for="input_button_one" class="col-sm-3 control-label">Menu Type<em class="text-danger">*</em></label>
							<div class="col-sm-8">
								<input type="text" name="type" placeholder="menu type" class="form-control">

								<span class="text-danger">{{ $errors->first('type') }}</span>
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
