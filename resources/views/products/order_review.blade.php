@extends('layouts.frontLayout.front_design')
@section('content')

<section id="form" style="margin-top: 20px;">
	<div class="container">
		<div class="breadcrumbs">
			<ol class="breadcrumb">
			  <li><a href="{{url('/')}}">Home</a></li>
			  <li class="active">Order Review</li>
			</ol>
		</div>
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
		<div class="row">
			<div class="col-sm-4 col-sm-offset-1">
				<div class="login-form">
					<h2>Billing Details</h2>
						<div class="form-group">
							{{ $userDetails->name }}
						</div>
						<div class="form-group">
							{{ $userDetails->address }}
						</div>
						<div class="form-group">
							{{ $userDetails->city }}
						</div>
						<div class="form-group">
							{{ $userDetails->state }}
						</div>
						<div class="form-group">
							{{ $userDetails->country }}
						</div>
						<div class="form-group">
							{{ $userDetails->pincode }}
						</div>
						<div class="form-group">
							{{ $userDetails->mobile }}
						</div>
				</div>
			</div>
			<div class="col-sm-1">
				<h2 class="or">&</h2>
			</div>
			<div class="col-sm-4 col-sm-offset-1">
				<div class="signup-form">
					<h2>Shipping Details</h2>
						<div class="form-group">
							{{ $shippingDetails->name }}
						</div>
						<div class="form-group">
							{{ $shippingDetails->address }}
						</div>
						<div class="form-group">
							{{ $shippingDetails->city }}
						</div>
						<div class="form-group">
							{{ $shippingDetails->state }}
						</div>
						<div class="form-group">
							{{ $shippingDetails->country }}
						</div>
						<div class="form-group">
							{{ $shippingDetails->pincode }}
						</div>
						<div class="form-group">
							{{ $shippingDetails->mobile }}
						</div>
				</div>
			</div>
		</div>
	</div>
</section>

@endsection