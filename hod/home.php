<?php
	session_start();

	if(!$_SESSION['hod']){
		header("location: ../login-page.php");
		exit();
	}
	elseif (!$_SESSION['dept']) {
		header("location: ../login-page.php?error=Department not ready!");
		exit();
	}
	else{
		include('../Database/config.php');
		
		$hod = $_SESSION['hod'];
		$dept = $_SESSION['dept'];

		$sql="SELECT count(DISTINCT adm) AS trainees FROM trainees INNER JOIN courses ON courses.code = trainees.course_code WHERE trainees.status = '1' AND trainees.deleted_by = '' AND courses.department_code = '$dept'";
		$trainees_count=mysqli_query($conn, $sql);

		$sql="SELECT count(DISTINCT trainer_id) as trainers from trainers WHERE trainers.department_id = '$dept'";
		$trainers_count=mysqli_query($conn, $sql);

		$sql="SELECT count(DISTINCT class_name) as classes from trainees INNER JOIN classes ON trainees.class = classes.class_name INNER JOIN courses ON courses.code = trainees.course_code WHERE trainees.status = '1' AND trainees.deleted_by = '' AND courses.department_code = '$dept'";
		$classes_count=mysqli_query($conn, $sql);

		$sql="SELECT count(DISTINCT code) as courses from courses INNER JOIN departments ON departments.department_code = courses.department_code WHERE courses.department_code = '$dept'";
		$courses_count=mysqli_query($conn, $sql);

		$sql="SELECT * from years order by  year";
		$years_result=mysqli_query($conn, $sql);
		while($row=mysqli_fetch_assoc($years_result)) {$_SESSION['year'] = $row['year'];}
		
		$sql="SELECT * from terms order by  term_code";
		$terms_result=mysqli_query($conn, $sql);
		while($row=mysqli_fetch_assoc($terms_result)) {$_SESSION['term'] = $row['term_name'];}
		
		//Logged in user session
		$loggedin = $_SESSION["hod"];
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
	<title>TPS || H.O.D Panel </title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/jpeg" href="../Images/mtvc_logo.jpg"/>
	<!-- Font Awesome -->
	<!-- Font Awesome Icons -->
	<link rel="stylesheet" href="../Libraries/plugins/fontawesome-free/css/all.min.css">
	<link rel="stylesheet" href="../Libraries/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="../Css/small_boxes.css">

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
      
	  <!-- Notifications Dropdown Menu -->

	  <li class="nav-item">
        <a class="nav-link" id="logoutuser" href="#"><i class="fa fa-sign-out" ></i>&nbspLogout </a>
      </li>
	  
    </ul>  

  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <!-- <div class=left-sidebar>-->
  <aside class="main-sidebar sidebar-light-primary elevation-2" style="color: orange;">
    <!-- Brand Logo -->
    <a href="" class="brand-link" style="margin-left: 0px;">
      <img src="../Images/mtvc_logo.jpg" alt="logo" class="brand-image img-square elevation-0" style="width: 50px; height: 150px;">
      <span class="brand-text font-weight-light"><h6 class="text-muted">Paramount Technical</h6></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="../Images/user.jpg" class="img-circle elevation-0" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block" id="loggedinuser"><?php echo "<strong class='text-muted' style='font-size: 13px;'>".strtoupper($fullname)."</strong>"; ?> &nbsp <i class="fa fa-circle text-success" ></i></a>
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
		<?php include("Includes/small-boxes.php");?>
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

<!-- Bootstrap 4 -->
<script src="../Libraries/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="../Libraries/plugins/chart.js/Chart.min.js"></script>
<!-- SweetAlert2 -->
<script src="../Libraries/plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="../Libraries/plugins/toastr/toastr.min.js"></script>
<!-- overlayScrollbars -->
<script src="../Libraries/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="../Libraries/dist/js/adminlte.min.js"></script>
<script type="text/javascript" src="../Includes/Toasts_SweetAlerts.js"></script>
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
</html>

<script type="text/javascript" src="charts.js"></script>

<script type="text/javascript">
	$(document).ready(function()
{
	$("#home").click(function(e){
		e.preventDefault();
		location.reload();
	})
	
	$("#container").load("View/statistics.html");
	
	$("#add_trainee").click(function(){
		// $("#container").load("New/add_trainee.php");
    toastr.error("You do not have permission to perform this action!")

	})
	
	$("#review_trainees").click(function(){
		$("#container").load("View/review_trainees.php");
	})
	
	$("#trainees").click(function(){
		$("#container").load("View/review_trainees.php");
	})
	
	$("#trainees_report").click(function(){
		$("#container").html('<iframe id="ifrm" style="width: 100%; height: 100vh; border: 1px solid blue;"></iframe>');
		$("#ifrm").attr("src", "Reports/rpt_trainees.php");
	})

	$("#add_trainer").click(function(){
		var request = 'Add trainer';
		// $.ajax({
		// 	url: 'Requests/trainer_registration.php',
		// 	method: 'post',
		// 	data: {request:request},
		// 	success: function(data){
		// 		if(data == 'Success'){
		// 			$("#container").load("New/add_trainer.php");
		// 		}
		// 		else{
		// 			alert(data);
		// 		}
		// 	}
		// })
		toastr.error("You do not have permission to perform this action!")
	})
	
	$("#review_trainers").click(function(){
		$("#container").load("View/review_trainers.php");
	})		
	
	$("#assign_units").click(function(){
		// $("#container").load("View/assign_units.php");
		$("#container").load("Modals/assign_units.php");
	})
	
	$("#trainers").click(function(){
		$("#container").load("View/review_trainers.php");
	})
	
	$("#add_department").click(function(){
		$("#container").load("New/add_department.php");
	})
	
	$("#review_departments").click(function(){
		$("#container").load("View/review_departments.php");
	})
	
	$("#assign_hods").click(function(){
		$("#container").load("Edit/assign_hods.php");
	})
	
	$("#departments").click(function(){
	$("#container").load("View/review_departments.php");
	})

	$("#add_course").click(function(){
	$("#container").load("New/add_course.php");
	})
	
	$("#review_courses").click(function(){
	$("#container").load("View/review_courses.php");
	})
	
	$("#courses_report").click(function(){
		$("#container").html('<iframe id="ifrm" style="width: 100%; height: 100vh; border: 1px solid blue;"></iframe>');
		$("#ifrm").attr("src", "Reports/rpt_courses.php");
	})

	$("#courses").click(function(){
	$("#container").load("View/review_courses.php");
	})
		
	$("#add_class").click(function(){
	$("#container").load("New/add_class.php");
	})
	
	$("#review_classes").click(function(){
	$("#container").load("View/review_classes.php");
	})
	
	$("#add_unit").click(function(){
	$("#container").load("New/add_unit.php");
	})
	
	$("#review_unit").click(function(){
	$("#container").load("View/review_unit.php");
	})

	$("#mark_class_register").click(function(){
		$("#container").load("Modals/mark_class_register.php");
	})

	$("#update_class_register").click(function(){
		$("#container").load("Modals/update_class_register.php");
	})

	$("#class_attendance_report").click(function(){
		$("#container").load("Modals/class_attendance_report.php");
	})

	$("#mark_exam_register").click(function(){
		$("#container").load("Modals/mark_exam_register.php");
	})

	$("#update_exam_register").click(function(){
		$("#container").load("Modals/update_exam_register.php");
	})

	$("#exam_attendance_report").click(function(){
		$("#container").load("Modals/exam_attendance_report.php");
	})

	$("#mark_entry").click(function(){
	$("#container").load("Modals/mark_entry_new.php");
	})

	$("#mark_lists").click(function(){
		$("#container").html('<iframe id="ifrm" style="width: 100%; height: 100vh; border: 1px solid blue;"></iframe>');
		$("#ifrm").attr("src", "Reports/allmarklists.php");
	})

	$("#mark_sheets").click(function(){
		$("#container").html('<iframe id="ifrm" style="width: 100%; height: 100vh; border: 1px solid blue;"></iframe>');
		$("#ifrm").attr("src", "Reports/allmarksheets.php");
	})

	$("#review_marks_dialog").click(function(){
	$("#container").load("Modals/review_marks_modal.php");
	})

	$("#course_analysis").click(function(){
		$("#container").html('<iframe id="ifrm" style="width: 100%; height: 100vh; border: 1px solid blue;"></iframe>');
		$("#ifrm").attr("src", "Reports/rpt_course_analysis.php");
	})
	
	$("#general_analysis").click(function(){
		$("#container").html('<iframe id="ifrm" style="width: 100%; height: 100vh; border: 1px solid blue;"></iframe>');
		$("#ifrm").attr("src", "Reports/rpt_general_analysis.php");
	})

	$("#trainee_performance").click(function(){
		$("#container").html('<iframe id="ifrm" style="width: 100%; height: 100vh; border: 1px solid blue;"></iframe>');
		$("#ifrm").attr("src", "Reports/PDF.php");
	})

	$("#add_user").click(function(){
		toastr.info("Add a trainer then update the details instead.")
	})
	
	$("#review_users").click(function(){
	$("#container").load("View/manageusers.php");
	})
	
	$("#transcripts").click(function(){
		$("#container").load("Downloads/course_transcripts.php");
	})
	
	$("#course_transcripts").click(function(){
		$("#container").load("Downloads/course_transcripts.php");
	})
	
	$("#individual_transcripts").click(function(){
		$("#container").load("Downloads/individual_transcripts.php");
	})

	$("#list_of_shame").click(function(){
		var request = 'list of shame';
		$.ajax({
			url: 'Requests/req_list_of_shame.php',
			method: 'post',
			data: {request:request},
			success: function(data){
				// alert(data);
				if(data == 'Success'){
					$("#container").html('<iframe id="ifrm" style="width: 100%; height: 100vh; border: 1px solid blue;"></iframe>');
					$("#ifrm").attr("src", "Reports/list_of_shame.php");
				}
				else{
					alert(data);
				}
			}
		})
	})
	
	$("#loggedinuser").click(function(){
	$("#container").load("View/useraccount.php");
	})
	
	
	$("#backup").click(function(){
	window.location.replace('../Database/backup.php');
	})
	
	$("#logoutuser").click(function(event){
		if(confirm('Are you sure you want to logout?')){
			window.location.replace('logout.php');
		}
		else{
			event.PreventDefault();
		}
		
	})
	
	$("#update_term").click(function(){
	$("#container").load("Update/update_term.php");
	})
});	
</script>
<?php } ?>