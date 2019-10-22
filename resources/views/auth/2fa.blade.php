@extends('layouts.app')
 
@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-default">
					<div class="panel-heading">
						<strong>Two Factor Authentication</strong>
					</div>
					<div class="panel-body">
					   	@if (session('error'))
							<div class="alert alert-danger">
								{{ session('error') }}
							</div>
						@endif
						@if (session('success'))
							<div class="alert alert-success">
								{{ session('success') }}
							</div>
						@endif

						@if(!count($data['user']->passwordSecurity))
							<form class="form-horizontal" method="POST" action="{{ route('generate2faSecret') }}">
							   	{{ csrf_field() }}
							   	<div class="row">
							   		<div class="col-md-12">
							   			<p>
							   			Two factor authentication (2FA) strengthens access security by requiring two methods (also referred to as factors) to verify your identity. Two factor authentication protects against phishing, social engineering and password brute force attacks and secures your logins from attackers exploiting weak or stolen credentials.</p>
										<p>
											To Enable Two Factor Authentication on your Account, you need to do following steps
										</p>
										<p>
											<strong>
												1.Click on Generate Secret Button,To Generate a Unique secret QR code for your profile
											</strong><br>
											<strong>
												2. Verify the OTP from Google Authenticator Mobile App
											</strong>
										</p>
							   		</div>
							   	</div>
							   	<div class="row">
							   		<div class="col-md-6 col-md-offset-3">
										<button type="submit" class="btn btn-primary">
										   Generate Secret Key to Enable 2FA
										</button>
									</div>
							   	</div>
							</form>
						@else($data['user']->passwordSecurity->google2fa_enable)
							<div class="row">
								<div class="col-md-12 text-center">
									<label>
										1. Scan this barcode with your Google Authenticator App:
									</label>
									<img src="{{$data['google2fa_url'] }}" alt=""><br>
									<label>{{$data['user']->passwordSecurity->google2fa_secret}}</label><br/>
									<span style="font-size: 11px">NOTE: If you can not scan above QR code then manually added into Google Authenticator App.</span>
								</div>
							</div><br>
							<div class="row">
								<div class="col-md-12 text-center">
									<label>
										2.After successfully added above secret code into App please click on below Logout Link. 
									</label>

									<a class="btn btn-primary" href="{{ url('logout') }}"
                                    onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
	                                    <i class="fa fa-sign-out"></i>
	                                    Logout
	                                </a>

	                                <form id="logout-form" action="{{ url('logout') }}" method="POST" style="display: none;">
	                                    {{ csrf_field() }}
	                                </form>
								</div>
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection