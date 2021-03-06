@extends('layouts.master-without-nav')

@section('title')
    @lang('translation.Login')
@endsection

@section('body')

    <body>
    @endsection

    @section('content')
        <div class="account-pages my-5 pt-sm-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <img src="{{ URL::asset('/front_assets/img/logo.png') }}" alt="" class="mb-3 logo-center" style="display: block;margin-left: auto;margin-right: auto;" height="34">
                        <div class="card overflow-hidden">
                            <div class="bg-primary bg-soft">
                                <div class="row">
                                    <div class="col-7">
                                        <div class="text-primary p-4">
                                            <h5 class="text-primary">Welcome Back !</h5>
                                            <p>Sign in to continue to Zcon.</p>
                                        </div>
                                    </div>
                                    <div class="col-5 align-self-end">
                                        <img src="{{ URL::asset('/assets/images/profile-img.png') }}" alt=""
                                            class="img-fluid">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="auth-logo">
                                    <a href="index" class="auth-logo-light">
                                        <div class="avatar-md profile-user-wid mb-4">
                                            <span class="avatar-title rounded-circle bg-light">
                                                <img src="{{ URL::asset('/assets/images/logo-light.svg') }}" alt=""
                                                    class="rounded-circle" height="34">
                                            </span>
                                        </div>
                                    </a>

                                    <a href="index" class="auth-logo-dark">
                                        <div class="avatar-md profile-user-wid mb-4">
                                            <span class="avatar-title rounded-circle bg-light">
                                                <img src="{{ URL::asset('/front_assets/img/favicon.png') }}" alt=""
                                                    class="rounded-circle" height="34">
                                            </span>
                                        </div>
                                    </a>
                                </div>
                                <div class="p-2">
                                    <form class="form-horizontal" action="{{ url('admin/authenticate') }}" method="post">
										@csrf
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Username</label>
											<input type="text" class="form-control" id="username" value="" name="username" placeholder="Enter the username">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Password</label>
                                            <div class="input-group auth-pass-inputgroup">
												<input type="password" class="form-control" id="password" value="{{ canDisplayCredentials() ? '123456':'' }}" 
												name="password" placeholder="Enter the password" aria-label="Password" aria-describedby="password-addon">
                                                
                                                <button class="btn btn-light " type="button" id="password-addon"><i
                                                        class="mdi mdi-eye-outline"></i></button>
                                            </div>
                                        </div>
                                        <div class="mt-3 d-grid">
                                            <button style="background: #4E2465; color: #ffffff" class="btn waves-effect waves-light" type="submit">Log
                                                In</button>
                                        </div>

                                        <div class="mt-4 text-center">
                                            <h5 class="font-size-14 mb-3">Sign in with</h5>

                                            <ul class="list-inline">
                                                <li class="list-inline-item">
                                                    <a href="javascript::void()"
                                                        class="social-list-item bg-primary text-white border-primary">
                                                        <i class="mdi mdi-facebook"></i>
                                                    </a>
                                                </li>
                                                <li class="list-inline-item">
                                                    <a href="javascript::void()"
                                                        class="social-list-item bg-info text-white border-info">
                                                        <i class="mdi mdi-twitter"></i>
                                                    </a>
                                                </li>
                                                <li class="list-inline-item">
                                                    <a href="javascript::void()"
                                                        class="social-list-item bg-danger text-white border-danger">
                                                        <i class="mdi mdi-google"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="mt-4 text-center">
                                            <a href="auth-recoverpw" class="text-muted"><i
                                                    class="mdi mdi-lock me-1"></i> Forgot your password?</a>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                        <div class="mt-5 text-center">

                            <div>
                                <p>Don't have an account ? <a href="auth-register" class="fw-medium text-primary">
                                        Signup now </a> </p>
                                <p>?? <script>
                                        document.write(new Date().getFullYear())

                                    </script> Zcon. Crafted with <i class="mdi mdi-heart text-danger"></i> Zcon
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- end account-pages -->

    @endsection
@section('script')
<script>
	$(document).ready(function() {
		var canDisplayCredentials = '{!! canDisplayCredentials() !!}';
		$('.login_btn').click(function() {
			$('.login_btn').removeClass('active')
			$(this).addClass('active')
			login_change();
		});
		login_change();
		function login_change() {
			user = $('.login_btn.active').attr('user')
			$('.user_type').val(user);
			if (user == 'Admin') {
				if(canDisplayCredentials == '1') {
					$('#username').val('admin');
				}
				$('#username').attr('placeholder','Username');
				$('.username_label').text('Username');
				$(".username_label").attr('label','Username');
			}
			else if(user == 'Dispatcher') {
				if(canDisplayCredentials == '1') {
					$('#username').val('dispatcher');
				}
				$('#username').attr('placeholder','Username');
				$('.username_label').text('Username');
				$(".username_label").attr('label','Username');
			}
			else if(user == 'Company') {
				if(canDisplayCredentials == '1') {
					$('#username').val('9876543211');
				}
				$('#username').attr('placeholder','Email / Mobile Number');
				$('.username_label').text('Email / Mobile Number');
				$(".username_label").attr('label','Email / Mobile Number');
			}
		}
	});
	</script>
@endsection