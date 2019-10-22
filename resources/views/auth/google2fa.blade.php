@extends('layouts.app')
 
@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-3"></div>
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading text-center">
						<strong>Two Factor Authentication</strong>
					</div>
					<div class="panel-body">
						<!-- <p>
							Two factor authentication (2FA) strengthens access security by requiring two methods (also referred to as factors) to verify your identity. Two factor authentication protects against phishing, social engineering and password brute force attacks and secures your logins from attackers exploiting weak or stolen credentials.
						</p> -->
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
						<strong>
							Two-factor authentication code
						</strong><br/><br/>
						<form class="form-horizontal" action="{{ route('2faVerify') }}" method="POST">
						   {{ csrf_field() }}
							<div class="form-group{{ $errors->has('one_time_password-code') ? ' has-error' : '' }}">
							   
							   <!-- <label for="one_time_password" class="col-md-4 control-label">One Time Password</label> -->

							   	<div class="col-md-12">
								   <input name="one_time_password" class="form-control"  type="text" autofocus/>
								   <p>
								   	Enter the code from the two-factor app on your mobile device. If you've lost your device, you may enter one of your recovery codes.
								   </p>
							   	</div>
						   </div>
						   <div class="form-group">
							   <div class="col-md-12 text-center">
									<button class="btn btn-success" type="submit">Verify code</button><br/>
									<span>If you lost your device then <a href="{{url('re-authenticate')}}">click here</a> to reauthenticate.</span>
							   </div>
						   </div>
					   </form>
					</div>
				</div>
			</div>
			<div class="col-md-3"></div>
		</div>
	</div>
@endsection

 
