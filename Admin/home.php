<?php
	session_start();

	if(!$_SESSION['Admin']){
		header("location: ../login-page.php");
		exit();
	}
	else{
		include('../Database/config.php');
		
		$sql="SELECT count(DISTINCT adm) AS trainees FROM trainees WHERE status = '1' AND trainees.deleted_by = ''";
		$trainees_count=mysqli_query($conn, $sql);

		$sql="SELECT count(DISTINCT trainer_id) as trainers from trainers";
		$trainers_count=mysqli_query($conn, $sql);

		$sql="select count(DISTINCT department_name) as departments from departments";
		$departments_count=mysqli_query($conn, $sql);

		$sql="select count(DISTINCT course_name) as courses from courses";
		$courses_count=mysqli_query($conn, $sql);

		$sql="SELECT * from years order by  year";
		$years_result=mysqli_query($conn, $sql);
		while($row=mysqli_fetch_assoc($years_result)) {$_SESSION['year'] = $row['year'];}
		
		$sql="SELECT * from terms order by  term_name";
		$terms_result=mysqli_query($conn, $sql);
		while($row=mysqli_fetch_assoc($terms_result)) {$_SESSION['term'] = $row['term_name'];}
		
		//Logged in user session
		$loggedin = $_SESSION["Admin"];
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
	<title>TPS || Admin Panel </title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/jpeg" href="../Images/mtvc_logo.jpg"/>
	<link rel="stylesheet" href="../Libraries/plugins/fontawesome-free/css/all.min.css">
	<link rel="stylesheet" href="../Libraries/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="../Libraries/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <link rel="stylesheet" href="../Libraries/plugins/flag-icon-css/css/flag-icon.min.css">
  <link rel="stylesheet" href="../Libraries/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="../Libraries/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="../Libraries/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <link rel="stylesheet" href="../Libraries/plugins/jqvmap/jqvmap.min.css">
  <link rel="stylesheet" href="../Libraries/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../Libraries/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../Libraries/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
	<link rel="stylesheet" href="../Libraries/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
	<link rel="stylesheet" href="../Libraries/plugins/toastr/toastr.min.css">
	<link rel="stylesheet" href="../Libraries/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
	<link rel="stylesheet" href="../Libraries/plugins/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="../Libraries/plugins/summernote/summernote-bs4.min.css">
	<link rel="stylesheet" href="../Libraries/dist/css/adminlte.min.css">
<script type="text/javascript">

function search_table(tbodyid){
  $('.search').on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $(tbodyid+" tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    })
  })
}
function edit(request, obj, sn, column, table) {
    var values = {
        request : request,
        sn 		  : sn,
        column 	: column,
        value 	: obj.innerHTML,
        table   : table
    }
    $.ajax({
        type: "POST",
        url: "Edit/edit.php",
        data: values,
        dataType: 'json',
        success: function(response){
            if (response) {
                $(obj).closest("td").css({"border":"2px solid darkcyan", "color":"darkcyan"})
            }
            else{
            	$(obj).closest("td").css({"border":"2px solid red", "color":"red"})
            }
        },
       	error: function(){
       		$(obj).closest("td").css({"border":"2px solid red", "color":"red"})
       	}
   });
}

function deleteRecord(obj, request, table, column, value){
	var values = {
		request:request, 
		table:table, 
		column:column, 
		value:value
	}
	if (confirm("Are you sure you want to delete this "+table+"?")) {
		$.ajax({
			type: 'post',
			url: 'Delete/delete.php',
			data: values,
			dataType: 'json',
			success: function(response){
				if (response.status == 'success') {
					Toast.fire({icon:"success", title: response.message})
				}
				else{
					Toast.fire({icon:"error", title: response.message})
				}
			},
			error: function(response){
				toastr.error(response.message)
			}
		})
		$(obj).parents('tr').remove();
	}
}

function submitData(form, rules, messages, url, method, datavalues, modalID){
  $.validator.setDefaults({
    submitHandler: function(){
      $.ajax({
        url: url,
        method: method,
        data: datavalues,
        dataType: 'json',
        success: function(response){
          if(response.status=='success'){
            Toast.fire({icon: 'success', title: response.message})
            $(form).trigger('reset');
            hideModal(modalID);
          }
          else{
            Toast.fire({icon: 'error', title: response.message})
          }
        },
        error: function(){
          Toast.fire({icon: 'error', title: response.message})
        }
      })
    }
  });

  $(form).validate({
    rules: rules,
    messages: messages,
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
       $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass){
      $(element).removeClass('is-invalid');
    }
  })
}

function loadReports(){
	$.ajax({
		url: 'View/select.php',
		method: 'post',
		data: {request:'reports'},
		dataType: 'json',
		success: function(response){
			if(response.status == "success"){
				var data = response.data;
				var len = data.length;
				var count = 1;
				
				for(var i=0; i<len; i++){
					var sn = data[i]['sn'];
					var description = data[i]['description'];
					var min_nos = data[i]['min_nos'];
					var max_nos = data[i]['max_nos'];
					$("#report_records").append("<tr><td>"+count+"</td><td contentEditable='false'>"+description+"</td><td contentEditable='true' onblur=edit('editReport',this,"+sn+",'min_nos','')>"+min_nos+"</td><td contentEditable='true' onblur=edit('editReport',this,"+sn+",'max_nos','')>"+max_nos+"</td><td><button type='button' class='btn-xs btn-flat btn-danger' onclick=deleteRecord('this','delete','reports','sn',"+sn+")><i class='fa fa-trash'></i>&nbspDelete</button></td></tr>"); count++;
				}
			}
			else if(response.message == "no data"){
				$("#report_records").append("<tr><td colspan=5>No report added</td></tr>")
			} 
			else{
				Toast.fire({icon:"error", title: response.message})
			}
		},
		error: function(){
			Toast.fire({icon:"error",title:"Error sending request"})
		}
	})
}

function loadDesignations(){
	$.ajax({
		url: 'View/select.php',
		method: 'post',
		data: {request:'designations'},
		dataType: 'json',
		success: function(response){
			if(response.status == "success"){
				var data = response.data;
				var len = data.length;
				var count = 1;
				
				for(var i=0; i<len; i++){
					var sn = data[i]['sn'];
					var code = data[i]['code'];
					var description = data[i]['description'];
					$("#designation_records").append("<tr><td>"+count+"</td><td contentEditable='true' onblur=edit('editDesignation',this,"+sn+",'code','')>"+code+"</td><td contentEditable='true' onblur=edit('editDesignation',this,"+sn+",'description','')>"+description+"</td><td><button type='button' class='btn-xs btn-flat btn-danger' onclick=deleteRecord('delete','designation','sn',"+sn+")><i class='fa fa-trash'></i>&nbspDelete</button></td></tr>"); count++;
				}
			}
			else if(response.message == "no data"){
				$("#designation_records").append("<tr><td colspan=4>No designation created</td></tr>")
			} 
			else{
				Toast.fire({icon:"error", title: response.message})
			}
		},
		error: function(){
			Toast.fire({icon:"error",title:"Error sending request"})
		}
	})
}

function loadSignatories(){
	$.ajax({
		url: 'View/select.php',
		method: 'post',
		data: {request:'signatories'},
		dataType: 'json',
		success: function(response){
			if(response.status == "success"){
				var data = response.data;
				var len = data.length;
				var count = 1;
				
				for(var i=0; i<len; i++){
					var sn = data[i]['sn'];
					var designation = data[i]['designation'];
					var name = data[i]['name'];
					var phone = data[i]['phone'];
					var rankpos = data[i]['rankpos'];
					$("#signatory_records").append("<tr><td>"+count+"</td><td contentEditable='false'>"+designation+"</td><td contentEditable='false'>"+name+"</td><td contentEditable='false' onblur=edit('editSignatory',this,"+sn+",'phone_no','trainers')>"+phone+"</td><td contentEditable='true' onblur=edit('editSignatory',this,"+sn+",'rankpos','signatory')>"+rankpos+"</td><td><button type='button' class='btn-xs btn-flat btn-danger' onclick=deleteRecord('delete','signatory','sn',"+sn+")><i class='fa fa-trash'></i>&nbspDelete</button></td></tr>"); count++;
				}
			}
			else if(response.message == "no data"){
				$("#signatory_records").append("<tr><td colspan=7>No signatory registered</td></tr>")
			} 
			else{
				Toast.fire({icon:"error", title: response.message})
			}
		},
		error: function(){
			Toast.fire({icon:"error",title:"Error sending request"})
		}
	})
}

</script>
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

      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fa fa-cog" style="font-color:red"></i>
          </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header">System settings</span>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item" id=update_term>
          <i class="fa fa-cog mr-2"></i> Current session
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item" id=mark_entry_period>
          <i class="fas fa-poll mr-2"></i> Mark entry periods
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item" id=signatories>
          <i class="far fa-edit mr-2"></i> Signatories
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item" id=backup>
          <i class="fa fa-download mr-2"></i> Backup database
          </a>
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
  <aside class="main-sidebar sidebar-light-primary elevation-2" style="color: orange;">
    <!-- Brand Logo -->
    <a href="" class="brand-link" style="margin-left: 0px;">
      <img src="../Images/mtvc_logo.jpg" alt="Logo" class="brand-image img-square elevation-0" style="width: 50px; height: 150px;">
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

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <section class="content ">
      <div class="container-fluid ">
		<?php include("Includes/small-boxes.php");?>
		 <div id=container>
		 <div id=section></div>
		 </div>
		 
      </div>
    </section>

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

  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
  <div class="noprint">
  	<?php include("Includes/footer.php");?>
  </div>
  
</div>

<script src="../Libraries/plugins/jquery/jquery.min.js"></script>
<script src="../Libraries/jquery-ui.min.js"></script>
<script src="../Libraries/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="../Libraries/plugins/jquery-validation/additional-methods.min.js"></script>
<script src="../Libraries/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../Libraries/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../Libraries/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../Libraries/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../Libraries/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../Libraries/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../Libraries/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../Libraries/plugins/jszip/jszip.min.js"></script>
<script src="../Libraries/plugins/pdfmake/pdfmake.min.js"></script>
<script src="../Libraries/plugins/pdfmake/vfs_fonts.js"></script>
<script src="../Libraries/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../Libraries/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../Libraries/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="../Libraries/plugins/select2/js/select2.full.min.js"></script>
<script src="../Libraries/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script src="../Libraries/plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="../Libraries/plugins/toastr/toastr.min.js"></script>
<script src="../Libraries/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="../Libraries/plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="../Libraries/plugins/raphael/raphael.min.js"></script>
<script src="../Libraries/plugins/jquery-mapael/jquery.mapael.min.js"></script>
<script src="../Libraries/plugins/jquery-mapael/maps/usa_states.min.js"></script>
<script src="../Libraries/plugins/chart.js/Chart.min.js"></script>
<script src="../Libraries/plugins/sparklines/sparkline.js"></script>
<script src="../Libraries/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="../Libraries/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<script src="../Libraries/plugins/jquery-knob/jquery.knob.min.js"></script>
<script src="../Libraries/plugins/moment/moment.min.js"></script>
<script src="../Libraries/plugins/daterangepicker/daterangepicker.js"></script>
<script src="../Libraries/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="../Libraries/plugins/summernote/summernote-bs4.min.js"></script>
<script src="../Libraries/dist/js/adminlte.min.js"></script>

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

$(document).ready(function(){
	var Toast = Swal.mixin({
	  toast: true,
	  position: 'top-right',
	  showConfirmButton: false,
	  timer: 3000
	});

	$('#container').load("View/statistics.html");
    
	$("#home").click(function(e){
		e.preventDefault();
		location.reload();
	})

	$("#add_trainee").click(function(){
		$("#container").load("New/add_trainee.php");

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
		$.ajax({
			url: 'Requests/trainer_registration.php',
			method: 'post',
			data: {request:request},
			success: function(data){
				if(data == 'Success'){
					$("#container").load("New/add_trainer.php");
				}
				else{
					alert(data);
				}
			}
		})
		
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
		// toastr.info("Feature coming soon..")
		$("#container").load("Downloads/class_attendance_reports.php");
	})

	$("#mark_exam_register").click(function(){
		// $("#container").load("Modals/mark_exam_register.php");
		toastr.info("Feature coming soon..")
	})

	$("#update_exam_register").click(function(){
		// $("#container").load("Modals/update_exam_register.php");
		toastr.info("Feature coming soon..")
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

	$("#passlist").click(function(){
		$("#container").html('<iframe id="ifrm" style="width: 100%; height: 100vh; border: 1px solid blue;"></iframe>');
		$("#ifrm").attr("src", "Reports/rpt_passlist.php");
	})


	$("#referlist").click(function(){
		$("#container").html('<iframe id="ifrm" style="width: 100%; height: 100vh; border: 1px solid blue;"></iframe>');
		$("#ifrm").attr("src", "Reports/rpt_referlist.php");
	})

	$("#add_user").click(function(){
	$("#container").load("New/adduser.php");
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
	
	$("#signatories").click(function(e){
		e.preventDefault();
		$("#container").load("View/signatories.php");
		loadReports();
		loadDesignations();
		loadSignatories();
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
		$("#container").load("Modals/term_session.php");
	})
	
	$("#mark_entry_period").click(function(){
		$("#container").load("Modals/mark_entry_dates.php");
	})
});	
</script>
<?php } ?>