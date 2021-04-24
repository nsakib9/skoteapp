@extends('layouts.master')

@section('css')
   
@endsection

@section('content')
<div class="content-wrapper" ng-controller="destination_admin">
	<section class="content-header">

		<h1 style="display: inline-block; "> Add Custom CSS </h1>
		<ol style=" float: right;" class="breadcrumb">
			<li>
				<a href="{{ url(LOGIN_USER_TYPE.'/dashboard') }}"> <i class="fa fa-dashboard"> </i> Home </a>
			</li>
			<li>
				<a href="{{ url(LOGIN_USER_TYPE.'/customCSS') }}">/ customcss </a>
			</li>
			<li class="active">/ Add </li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-8 offset-2">
				<div class="card card-danger">
					<div class="card-header shadow p-3 mb-5 bg-body rounded with-border">
						<h3 class="card-title"> Add Custom CSS Form </h3>
					</div>
					<form action="{{ url('admin/add_customCSS')}}" method="post">
						@csrf
					<div class="card-body">
						<span class="text-danger">(*)Fields are Mandatory</span>
						<div class="form-group">
							<label class="col-sm-3 control-label">customcss Name <em class="text-danger">*</em></label>
							<div class="col-sm-8">
								<textarea name="csscode" class="form-control" rows="3" id="customcss"></textarea>

								<span class="text-danger">{{ $errors->first('csscode') }}</span>
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

@push('scripts')
{{-- ckeditor --}}
{{--  <script type="text/javascript" src="https://cdn.ckeditor.com/4.12.0/standard/ckeditor.js"></script>
<script>
	
	CKEDITOR.replace('customcss');
</script>  --}}
@endpush