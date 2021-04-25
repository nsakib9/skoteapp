@extends('layouts.master')

@section('css')
   
@endsection

@section('content')
<div class="content-wrapper" ng-controller="destination_admin">
	<section class="content-header">
		<h1 style="display: inline-block; "> Edit Custom CSS </h1>
		<ol style=" float: right;" class="breadcrumb">
			<li><a href="{{ url(LOGIN_USER_TYPE.'/dashboard') }}"><i class="fa fa-dashboard"></i> Home </a></li>
			<li><a href="{{ url(LOGIN_USER_TYPE.'/customCSS') }}"> /Custom CSS </a></li>
			<li class="active"> /Edit </li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-10 offset-1">
				<div class="card card-danger">
					<div class="card-header  p-3 mb-5 bg-body rounded with-border">
						<h3 class="card-title">Edit Custom CSS Form</h3>
					</div>
					<form action="{{ url('admin/edit_customCSS', $customCSS->id)}}" method="post">
						@csrf
						<div class="card-body">
							<span class="text-danger">(*)Fields are Mandatory</span>
							<div class="form-group overflow-h">
								<label class="col-sm-12 ">customcss Name <em class="text-danger">*</em></label>
								<div class="col-sm-12">
									<textarea name="csscode" class="form-control" rows="30" id="customcss">{{$customCSS->csscode}}</textarea>
	
									<span class="text-danger">{{ $errors->first('csscode') }}</span>
								</div>
							</div>
						</div>
					<br>
					<div class="card-footer mt-20">
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
