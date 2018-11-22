@extends('layouts.frontLayout.front_design')
@section('content')

<section id="form" style="margin-top:20px;"><!--form-->
	<div class="container">
		<div class="row">
			<!-- Error Alert for Add Cart -->
	        @if(Session::has('flash_message_error'))
	            <div class="alert alert-danger alert-block">
	                <button type="button" class="close" data-dismiss="alert">×</button> 
	                <strong>{!! session('flash_message_error') !!}</strong>
	            </div>
	        @endif
	        <!-- End Error Alert for Add Cart -->

	        <!-- Success Alert for Add Cart  -->
	        @if(Session::has('flash_message_success'))
	            <div class="alert alert-success alert-block">
	                <button type="button" class="close" data-dismiss="alert">×</button> 
	                <strong>{!! session('flash_message_success') !!}</strong>
	            </div>
	        @endif
	        <!-- End Success Alert for Add Cart -->
			<div class="col-sm-4 col-sm-offset-1">
				<div class="login-form">
					<h2>Update Account</h2>
					<form id="accountForm" name="accountForm" method="post" action="{{ url('/account') }}">{{ csrf_field() }}
						<input value="{{ $userDetails->name }}" id="name" name="name" type="text" placeholder="Name"/>
						<input value="{{ $userDetails->address }}" id="address" name="address" type="text" placeholder="Address"/>
						<input value="{{ $userDetails->city }}" id="city" name="city" type="text" placeholder="City"/>
						<input value="{{ $userDetails->state }}" id="state" name="state" type="text" placeholder="State"/>
						<select id="country" name="country">
							<option value="">Select Country</option>
							@foreach($countries as $country)
								<option value="{{ $country->country_name }}" @if($country->country_name == $userDetails->country) selected @endif>{{ $country->country_name }}</option>
							@endforeach
						</select>
						<input value="{{ $userDetails->pincode }}" style="margin-top: 10px;" id="pincode" name="pincode" type="text" placeholder="Pincode"/>
						<input value="{{ $userDetails->mobile }}" id="mobile" name="mobile" type="text" placeholder="Mobile"/>
						<button type="submit" class="btn btn-default">Update</button>
					</form>
				</div>
			</div>
			<div class="col-sm-1">
				<h2 class="or">OR</h2>
			</div>
			<div class="col-sm-4">
				<div class="signup-form">
					<h2>Update Password</h2>
					
				</div>
			</div>
		</div>
	</div>
</section><!--/form-->


@endsection