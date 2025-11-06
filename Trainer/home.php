<?php
	session_start();

	if(!$_SESSION['Trainer']){
		header("location: ../login-page.php?error=Access Denied! You've to login first.");
		exit();
	}
	else{
	include('../Database/config.php');
	
	$sql="SELECT * from years order by  year";
	$years_result=mysqli_query($conn, $sql);
	while($row=mysqli_fetch_assoc($years_result)) {$_SESSION['year'] = $row['year'];}
	
	$sql="SELECT * from terms order by  term_code";
	$terms_result=mysqli_query($conn, $sql);
	while($row=mysqli_fetch_assoc($terms_result)) {$_SESSION['term'] = $row['term_name'];}

	//Logged in user session
	$loggedin = $_SESSION["Trainer"];
	$sql = "SELECT * FROM users WHERE user_id = '$loggedin'";
	$run = mysqli_query($conn, $sql);
	while($row = mysqli_fetch_assoc($run)){
		$fullname = $row['FirstName']." ".$row['LastName'];	
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>TPS || Trainers Panel </title>
	<!-- Tell the browser to be responsive to screen width -->
    <link rel="icon" type="image/jpeg" href="../Images/mtvc_logo.jpg"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Font Awesome -->
	<!-- Font Awesome Icons -->
	<link rel="stylesheet" href="../Libraries/plugins/fontawesome-free/css/all.min.css">
	<link rel="stylesheet" href="../Libraries/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="../Css/small_boxes.css">
	<!-- DataTables -->
	<link rel="stylesheet" href="../Libraries/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="../Libraries/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">

	<!-- SweetAlert2 -->
	<link rel="stylesheet" href="../Libraries/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
	<!-- Toastr -->
	<link rel="stylesheet" href="../Libraries/plugins/toastr/toastr.min.css">

	<!-- overlayScrollbars -->
	<link rel="stylesheet" href="../Libraries/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="../Libraries/dist/css/adminlte.min.css">
 
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed  layout-footer-fixed">

<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
     
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link"><h5><?php echo 'Examination Management System'; ?></h5></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
		<li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link" id="term_session"><?php echo '<strong>'.$_SESSION['term'].' '.$_SESSION['year'].'</strong>' ;?></a>
      </li>
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-envelope"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <div class="media-body">
                
                <p class="text-sm">You haven't entered end term marks...</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li>
	  
	  <li class="nav-item">
        <a class="nav-link" id="logoutuser" href="#"><i class="fa fa-sign-out" ></i>&nbspLogout </a>
      </li>
	  
    </ul>  

  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <!-- <div class=left-sidebar>-->
  <aside class="main-sidebar sidebar-light-primary elevation-4">
    <!-- Brand Logo -->
    <a href="" class="brand-link">
      <img src="../Images/mtvc_logo.jpg" alt="Logo" class="brand-image img-square elevation-0" style="width: 50px; height: 150px;">
      <span class="brand-text font-weight-light"><h6 class="text-muted">Paramount Technical</h6></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="../Images/user.jpg" class="img-circle elevation-2" alt="User">
        </div>
        <div class="info">
          <a href="#" class="d-block" id="loggedinuser"><?php echo strtoupper($fullname); ?> &nbsp <i class="fa fa-circle text-success" ></i></a>
        </div>
      </div>

		<?php include('Includes/sidebarmenu.php'); ?>

    </div>
    <!-- /.sidebar -->
  </aside>

  <!--</div>-->
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <section class="content ">
      <div class="container-fluid ">
		<?php include("Includes/small_boxes.php");?>
		 <div id=container>
		 <div id=section></div>
		 </div>
		 
      </div>
    </section>
	
    <!-- /.content -->
	
  </div>
  <style>
	#container{
		margin-left: 10px;
		margin-right: 10px;
	}
	#term_session{
		font-weight: bolder;
		font-size: 18px;
		color: green;
	}
 </style>
  <!-- /.content-wrapper -->
	
 
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
  <div class="noprint">
  	<?php include("Includes/footer.php");?>
  </div>
  
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../Libraries/plugins/jquery/jquery.min.js"></script>
<!-- <script type="text/javascript" src="home.js"></script> -->
<!-- Bootstrap 4 -->
<script src="../Libraries/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="../Libraries/plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="../Libraries/plugins/toastr/toastr.min.js"></script>

<!-- overlayScrollbars -->
<script src="../Libraries/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="../Libraries/dist/js/adminlte.min.js"></script>
</body>

	<style>
	
	@media print{
		.break {
			page-break-after:always;
		}
	}
	
	@media print{
		.noprint {
			display:none;
		}
	}
	
		@page{size:A4; margin:10mm 0mm 0mm 0mm;}
		@print{@page:header{display:none}}
		
	.left-sidebar{
     background-image: linear-gradient( 95.2deg, rgba(173,252,234,1) 26.8%, rgba(192,229,246,1) 64% );
	 }
	</style>

	<script>
	
$(document).ready(function()
{
	$("#container").load("Modals/mark_entry_new.php");

	$("#add_trainee").click(function(){
		$("#container").load("New/add_trainee.php");
	})
	
	$("#review_trainees").click(function(){
		$("#container").load("View/review_trainees.php");
	})
	
	$("#add_course").click(function(){
		toastr.error("You do not have permission to add courses.");
	})
	
	$("#review_courses").click(function(){
		$("#container").load("View/review_courses.php");
	})

	$("#add_unit").click(function(){
		toastr.error("Kindly liars with the Exam Officer to add you more units. Thank you.");
	})
	
	$("#review_unit").click(function(){
		$("#container").load("View/review_unit.php");
	})

	$("#add_class").click(function(){
		toastr.error("Kindly liars with the Exam Officer to add you more classes. Thank you");
	})
	
	$("#review_classes").click(function(){
		$("#container").load("View/review_classes.php");
	})

	$("#mark_class_register").click(function(){
		$("#container").load("Modals/mark_class_register.php");
	})

	$("#update_class_register").click(function(){
		// $("#container").load("Modals/update_class_register.php");
		toastr.info("This feature is coming up next.")
	})

	$("#class_attendance_report").click(function(){
		// $("#container").load("Modals/class_attendance_report.php");
		toastr.info("This feature is coming up next.")
	})

	$("#mark_exam_register").click(function(){
		// $("#container").load("Modals/mark_exam_register.php");
		toastr.info("This feature is coming up next.")
	})

	$("#update_exam_register").click(function(){
		// $("#container").load("Modals/update_exam_register.php");
		toastr.info("This feature is coming up next.")
	})

	$("#exam_attendance_report").click(function(){
		$("#container").load('Modals/exam_attendance_sheet.php');
	})
		
	$("#mark_entry").click(function(){
		$("#container").load("Modals/mark_entry_new.php");
	})

	$("#review_marks_dialog").click(function(){
		$("#container").load("Modals/review_marks_modal.php");
	})
	
	$("#list_of_shame").click(function(){
		$("#container").html('<iframe id="ifrm" style="width: 100%; height: 100vh; border: 1px solid blue;"></iframe>');
		$("#ifrm").attr("src", "Reports/list_of_shame.php");
	})

	$("#mark_lists").click(function(){
		$("#container").html('<iframe id="ifrm" style="width: 100%; height: 100vh; border: 1px solid blue;"></iframe>');
		$("#ifrm").attr("src", "Reports/marklists.php");
	})

	$("#mark_sheets").click(function(){
		$("#container").html('<iframe id="ifrm" style="width: 100%; height: 100vh; border: 1px solid blue;"></iframe>');
		$("#ifrm").attr("src", "Reports/marksheets.php");
	})

	$("#loggedinuser").click(function(){
	$("#container").load("View/useraccount.php");
	})

	$("#logoutuser").click(function(event){
		if(confirm('Are you sure you want to logout?')){
			window.location.replace('logout.php');
		}
		else{
			event.PreventDefault();
		}
		
	})

});	

	</script>
</html>
<?php } ?>