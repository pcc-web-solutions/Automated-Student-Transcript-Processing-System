<!DOCTYPE html>
		<html lang="en">
			<div id="loader" class="center"></div>
		<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<title>Sign In</title>
			<!-- Font Awesome -->
			<link rel="stylesheet" href="Libraries/plugins/fontawesome-free/css/all.min.css">
			<!-- Theme style -->
			<link rel="stylesheet" href="Libraries/dist/css/adminlte.min.css">
		</head>
			<style>
				#loader{
					border: 12px solid #f3f3f3;
					border-radius: 50%;
					border-top: 12px solid #444444;
					width: 50px;
					height: 50px;
					animation: spin 1s linear infinite;
				}
				
				@keyframes spin{
					100% {
						transform: rotate(360deg);
					}
				}
				
				.center{
					position: absolute;
					top: 0;
					bottom: 0;
					left: 0;
					right: 0;
					margin: auto;
				}
				.login-page{
					background-image: url(Images/login_background.jpg); 
					background-repeat: no-repeat; 
					background-size: cover;
				}
				.login-logo{
					width: 100px;
					margin: auto;
					margin-top: 10px;
				}
				.card{
					width: 100%;
					margin: auto;
				}
				.wrapper{
					display: flex;
					justify-content: center;
					align-items: center;
					height: 100vh;
					flex-direction: column;
				}
				.callout-success{
					background-color: green;
					color: white;
					font-weight: bold;
				}
				.callout-danger{
					background-color: orange;
					color: white;
					font-weight: bold;
				}
			</style>
		<body class="hold-transition login-page">
			<div class="wrapper">
				<!-- Main content -->
				<section class="content">
				  <div class="container-fluid">
					<div class="row">
					  <!-- left column -->
					  <div class="col-md-12">
						<!-- jquery validation -->

						<h2 style="color: green; text-align: center;"> Exam System </h2>
						<div class="card">
						
						<img src="Images/mtvc_logo.jpg" class="login-logo"></img>
					   
						  <!-- form start -->
						  <form id="signin" action="Login/signin.php" class="form" method="POST" autocomplete="off">
							<div class="card-body">
							  	<?php if (isset($_GET['error'])) { $error = $_GET['error'];?>
							  
									<div class="callout callout-danger text-center" id="errorbox" readonly> <?php echo $error; ?>
									</div>
								<?php }?>
								<?php if (isset($_GET['success'])) { $error = $_GET['success'];?>
							  
									<div class="callout callout-success text-center" id="infobox" readonly> <?php echo $error; ?>
									</div>
								<?php }?>		  
							  <div class="form-group">
								<label for="exampleInputEmail1">User Name</label>
								<input type="text" name="username" id = "uname" class="form-control" placeholder="Phone number">
							  </div>
							  <div class="form-group">
								<label for="exampleInputPassword1" id = "passwordlbl">Password</label>
								<input type="password" name="password" id = "passwordipt" class="form-control" placeholder="Password">
							  </div>

							</div>
							<!-- /.card-body -->
							<div class="card-footer">
							  <button type="submit" class="btn btn-primary float-right" id="btnlogin" name="submit"><i class="fa fa-sign-in"></i>&nbsp Login</button>
							</div>
						  </form>
						</div>
						<!-- /.card -->
						</div>
					  <!--/.col (left) -->
					  <!-- right column -->
					  <div class="col-md-6">

					  </div>
					  <!--/.col (right) -->
					</div>
					<!-- /.row -->
				  </div><!-- /.container-fluid -->
				</section>
			</div>
			<!-- ./wrapper -->

		<!-- jQuery -->
		<script src="Libraries/plugins/jquery/jquery.min.js"></script>
		<!-- Bootstrap 4 -->
		<script src="Libraries/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
		<!-- jquery-validation -->
		<script src="Libraries/plugins/jquery-validation/jquery.validate.min.js"></script>
		<script src="Libraries/plugins/jquery-validation/additional-methods.min.js"></script>
		<!-- AdminLTE App -->
		<script src="Libraries/dist/js/adminlte.min.js"></script>

		<script>
			function resetform(){
				$('.form').trigger('reset');
			}

	  		document.onreadystatechange = function() {
				if (document.readyState !== "complete") {
					document.querySelector(
					"body").style.visibility = "hidden";
					document.querySelector(
					"#loader").style.visibility = "visible";
				} else {
					document.querySelector(
					"#loader").style.display = "none";
					document.querySelector(
					"body").style.visibility = "visible";
					resetform()
				}
			};
			
			document.querySelector('#infobox').style.visibility = 'visible';
			$('#signin').trigger('reset'); 
			
			$('.form-control').keyup(function(){
				
				var value = $(this).val();
				var strlength = value.length;
				
				if(strlength > 0){
					document.querySelector('#infobox').style.display = 'none';
				}
				else{
					document.querySelector('#infobox').show();
				}
			});
				
		$(function () {
			
		  $('#signin').validate({
			rules: {
			  username: {
				required: true
			  },
			  password: {
				required: true,
				minlength: 6
			  },
			  terms: {
				required: true
			  },
			},
			messages: {
			  username: {
				required: "Username is required"
			  },
			  password: {
				required: "Password is required"
			  },
			  terms: "Please accept our terms"
			},
			errorPlacement: function (error, element) {
			  error.addClass('invalid-feedback');
			  element.closest('.form-group').append(error);
			},
			highlight: function (element, errorClass, validClass) {
			  $(element).addClass('is-invalid');
			},
			unhighlight: function (element, errorClass, validClass) {
			  $(element).removeClass('is-invalid');
			}
		  });
		});
		</script>
	</body>
</html>