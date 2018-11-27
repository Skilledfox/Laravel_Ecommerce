/*price range*/

 $('#sl2').slider();

	var RGBChange = function() {
	  $('#RGB').css('background', 'rgb('+r.getValue()+','+g.getValue()+','+b.getValue()+')')
	};	
		
/*scroll to top*/

$(document).ready(function(){
	$(function () {
		$.scrollUp({
	        scrollName: 'scrollUp', // Element ID
	        scrollDistance: 300, // Distance from top/bottom before showing element (px)
	        scrollFrom: 'top', // 'top' or 'bottom'
	        scrollSpeed: 300, // Speed back to top (ms)
	        easingType: 'linear', // Scroll to top easing (see http://easings.net/)
	        animation: 'fade', // Fade, slide, none
	        animationSpeed: 200, // Animation in speed (ms)
	        scrollTrigger: false, // Set a custom triggering element. Can be an HTML string or jQuery object
					//scrollTarget: false, // Set a custom target element for scrolling to the top
	        scrollText: '<i class="fa fa-angle-up"></i>', // Text for element, can contain HTML
	        scrollTitle: false, // Set a custom <a> title if required.
	        scrollImg: false, // Set true to use image
	        activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
	        zIndex: 2147483647 // Z-Index for the overlay
		});
	});
});

// Change Price & Stock with Size
$(document).ready(function(){
	$("#selSize").change(function(){
		var idSize = $(this).val();
		/*alert(idSize);*/
		if (idSize == "") {
			return false;
		}
		$.ajax({
			type:'get',
			url:'/get-product-price',
			data:{idSize:idSize},
			success:function(resp){
				// alert(resp); return false;
				var arr = resp.split('#');

				$("#getPrice").html("BDT "+arr[0]);
				$("#price").val(arr[0]);
				if(arr[1]==0){
					$("#cartButton").hide();
					$("#Availability").text("Out Of Stock");
				}else{
					$("#cartButton").show();
					$("#Availability").text("In Stock");
				}
			},error:function(){
				alert("Error");
			}
		});
	});
});


// Replace Main Image with Alternate Image
$(document).ready(function(){
	$(".changeImage").click(function(){
		var image = $(this).attr('src');
		$(".mainImage").attr("src",image);
	});
});


// Instantiate EasyZoom instances
var $easyzoom = $('.easyzoom').easyZoom();

// Setup thumbnails example
var api1 = $easyzoom.filter('.easyzoom--with-thumbnails').data('easyZoom');

$('.thumbnails').on('click', 'a', function(e) {
	var $this = $(this);

	e.preventDefault();

	// Use EasyZoom's `swap` method
	api1.swap($this.data('standard'), $this.attr('href'));
});

// Setup toggles example
var api2 = $easyzoom.filter('.easyzoom--with-toggle').data('easyZoom');

$('.toggle').on('click', function() {
	var $this = $(this);

	if ($this.data("active") === true) {
		$this.text("Switch on").data("active", false);
		api2.teardown();
	} else {
		$this.text("Switch off").data("active", true);
		api2._init();
	}
});


$().ready(function(){
	// Validate Register form on keyup and submit
	$("#registerForm").validate({
		rules:{
			name:{
				required:true,
				minlength:2,
				accept: "[a-zA-Z]+"
			},
			password:{
				required:true,
				minlength:6
			},
			email:{
				required:true,
				email:true,
				remote:"/check-email"
			}
		},
		// Messagess
		messages:{
			name: {
				required: "Please enter your Name",
				minlength: "Your Name must be at least 2 characters long",
				accept: "Your Name must contain only letters"

			},
			password: {
				required: "Please provide your Password",
				minlength: "Your Password must be atleast 6 characters long"
			},
			email: {
				required: "Please enter your Email",
				email: "Please enter valid Email",
				remote: "Email already exists!"
			}
		}
	});

	// Validate Register form on keyup and submit
	$("#accountForm").validate({
		rules:{
			name:{
				required:true,
				minlength:2,
				accept: "[a-zA-Z]+"
			},
			address:{
				required:true,
				minlength:6
			},
			city:{
				required:true,
				minlength:2
			},
			state:{
				required:true
			},
			country:{
				required:true
			}
		},
		// Messagess
		messages:{
			name: {
				required: "Please enter your Name",
				minlength: "Your Name must be at least 2 characters long",
				accept: "Your Name must contain only letters"

			},
			address: {
				required: "Please provide your Address",
				minlength: "Your Address must be atleast 6 characters long"
			},
			city: {
				required: "Please provide your City",
				minlength: "Your City must be atleast 2 characters long"
			},
			state: {
				required: "Please provide your State"
			},
			country: {
				required: "Please provide your Country"
			}
		}
	});

	// Validate Login form on keyup and submit
	$("#loginForm").validate({
		rules:{
			email:{
				required:true,
				email:true
			},
			password:{
				required:true,
				minlength:6
			}
		},
		// Messagess
		messages:{
			email: {
				required: "Please enter your Email",
				email: "Please enter valid Email"
			},
			password: {
				required: "Please provide your Password"
			}	
		}
	});

	// Check Current User Password
	$("#current_pwd").keyup(function(){
		var current_pwd = $(this).val();
		$.ajax({
			headers:{
			    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			  },
			type:'post',
			url:'/check-user-pwd',
			data:{current_pwd:current_pwd},
			success:function(resp){
				/*alert(resp);*/
				if (resp=="false") {
					$("#chkPwd").html("<font color='red'>Current Password is incorrect</font>");
				}else if(resp=="true"){
					$("#chkPwd").html("<font color='green'>Current Password is correct</font>");
				}
			},error:function(){
				alert("Error");
			}
		});
	});

	// Password Strength Script
    $('#myPassword').passtrength({
      minChars: 6,
      passwordToggle: true,
      tooltip: true,
      eyeImg: "/images/frontend_images/eye.svg"
	});

});