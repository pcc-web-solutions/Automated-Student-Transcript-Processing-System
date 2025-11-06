<?php
	session_start();
	$dept = $_SESSION['dept'];
	include("../../Database/config.php");
	$trainer_id = $_SESSION['hod'];

	//retrieve year from years table
	$sql="select year from years";
	$current_year=mysqli_query($conn, $sql) or die("Unable to retrieve year");
	while($row=mysqli_fetch_assoc($current_year))
	{$year=$row['year'];}

	//retrieve term from term table
	$sql="select term_name from terms";
	$current_term=mysqli_query($conn, $sql) or die("Unable to retrieve term");
	while($row=mysqli_fetch_assoc($current_term))
	{$term=$row['term_name'];}

	$select_courses = $conn->query("SELECT DISTINCT(results_entry.course_code), courses.course_name FROM results_entry INNER JOIN trainees ON trainees.adm = results_entry.adm INNER JOIN courses ON courses.code = trainees.course_code INNER JOIN departments ON departments.department_code = courses.department_code WHERE courses.department_code = '$dept' AND term = '$term' AND exam_year = '$year' AND trainees.status = '1' ORDER BY results_entry.course_code, courses.course_name ASC");
	
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
  <link rel="stylesheet" href="../Libraries/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  
  <style>
	#load-marks{
		width: 100%;
	}
	#delete_all, #delete_all_specific{
		width: 100%;
	}
	table{
		width: 100%;
		border-collapse: collapse;
	}
	thead tr th{
		background-color: cyan;
		border: 1px solid grey;
	}
	tbody tr td{
		border: 1px solid grey;
	}
	tbody tr:nth-child(even){
		background-color: white;
	}
	.search:focus {
	  width: 100%;
	}
	.search {
	 width: 100%;
	 transition: width 0.4s ease-in-out;
	 margin-bottom:0px
	  }
  </style>
</head>
<body>
	<div class="card card-success card-outline">
		<div class="card-header">
			<div class="card-title">
				<strong>Review Marks</strong>
			</div>
			<button type="button" class="btn btn-info float-right" id="view_all"><i class="fa fa-eye"></i>&nbsp View all Marks</button>
		</div>
		<div class="card-body">
			<div id="specific">
				<div class="row" id="">
					<div class="col-md-3" style="margin-bottom: 10px;">	  
					  <select class="form-control" id="courses">
						<option value="">--Select course--</option>
						<?php
						if($select_courses->num_rows>0){
							while ($course = mysqli_fetch_array($select_courses)) {
								$course_code = $course['course_code'];
								$course_name = $course['course_name'];

								echo "<option value=".$course_code.">".$course_name."</option>";
							}
						}	
						?>
					  </select>
					</div>
					<div class="col-md-3" style="margin-bottom: 10px;">	  
					  <select class="form-control" id="classes">
						<option value="">--Select class--</option>
					  </select>
					</div>
					<div class="col-md-3" style="margin-bottom: 10px;">	  
					  <select class="form-control" id="units">
						<option value="">--Select unit--</option>
					  </select>
					</div>
					<div class="col-md-3" style="margin-bottom: 10px;">
						<button type="button" class="btn btn-primary float-right" id="load-marks"><i class="fa fa-check"></i>&nbsp Load Marks</button>
					</div>
				</div>
				
				<hr>
				
				<div class="row">
					<div class="col-sm-7" style="margin-bottom: 10px;">
						<input  type="Text" id="search" class="form-control search" placeholder="Start typing to search..." >
					</div>
					<div class="col-sm-5" style="margin-bottom: 10px;">
						<button type="button" class="btn btn-danger btn-block float-right" id="delete_all_specific"><i class="fa fa-trash"></i>&nbsp Delete All</button>
					</div>
				</div>	
			</div>

			<div id="general">
                <div class="row">
                    <div class="col-sm-8" style="margin-bottom: 10px;">
                        <input  type="Text" id="search" class="form-control search" placeholder="Start typing to search..." >
                    </div>
                    <div class="col-sm-4" style="margin-bottom: 10px;">
                        <button type="button" class="btn btn-danger btn-block float-right" id="delete_all"><i class="fa fa-trash"></i>&nbsp Delete All</button>
                    </div>
                </div>
			</div>
			<hr>

			<div class="error_message"></div>
			<div class="success_message"></div>
			
			<div class="row">
				<div class="col-sm-12">
					<div class="tbl"></div>
				</div>
			</div>
		</div>
	</div>

</body>
</html>
<script>
$(document).ready(function()
{	
	document.querySelector('#general').style.visibility="hidden";

	$("#search").on("keyup", function() {
		var value = $(this).val().toLowerCase();
		$("#marks-table tbody tr").filter(function() {
		  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		})
	})
	$('#courses').change(function()
	{
		var selected_course=$('#courses').children("option:selected").val();
		var selected_class=$('#classes').children("option:selected").val();
		var selected_unit=$('#units').children("option:selected").val();

		if(selected_course == ''){
			$('.tbl').html('<div class="alert alert-warning"><strong>Error!</strong> No classes registered for the selected course</div>');
		}
		else{

			$('#units').empty();
			$('#units').append("<option value = ''>--Select unit--</option>");

			$.ajax({
				url: 'New/select_class.php',
				type: 'post',
				data: {selected_course:selected_course},
				dataType: 'json',
				success:function(response){

					var len = response.length;
					
					$("#classes").empty();
					if(len==0){
						$('.success_message').empty();
						$('.tbl').empty();
						$("#classes").append("<option value=''>No class found</option>");
						$('.error_message').html("<div class='alert alert-warning'><strong>Error!</strong>&nbspCourse "+selected_course+" has no classes</div>");
					}
					else{
						$('.error_message').empty();
						$("#classes").append("<option value=''>--Select class--</option>");
						for( var i = 0; i < len; i++){
							var class_name = response[i]['class_name'];
							
							$("#classes").append("<option value='"+class_name+"'>"+class_name+"</option>");
						}
					}
				} 
			});

		}
	});

	$('#load-marks').click(function()
	{
		var selected_course=$('#courses').children("option:selected").val();
		var selected_class=$('#classes').children("option:selected").val();
		var selected_unit=$('#units').children("option:selected").val();
		
		if (selected_course == '' ){
			$('.success_message').empty();
			$('.tbl').empty();
			$('.error_message').html("<div class='alert alert-warning'><strong>Error!</strong>&nbspPlease select a course first</div>");
		}
		else if (selected_class == '' && selected_unit == ''){
			$('.success_message').empty();
			$('.tbl').empty();
			$('.error_message').html("<div class='alert alert-warning'><strong>Error!</strong>&nbspPlease select a class and a unit for "+selected_course+" first</div>");
		}
		else if (selected_class == '' ){
			$('.success_message').empty();
			$('.tbl').empty();
			$('.error_message').html("<div class='alert alert-warning'><strong>Error!</strong>&nbspPlease select a class first</div>");
		}
		else if (selected_unit == '' ){
			$('.success_message').empty();
			$('.tbl').empty();
			$('.error_message').html("<div class='alert alert-warning'><strong>Error!</strong>&nbspPlease select a unit first</div>");
		}
		else{
			$.ajax({
				url:'View/review_marks_new.php',
				method:'post',
				data:{selected_course: selected_course, selected_unit:selected_unit, selected_class:selected_class},

				success:function(data)
				{
					$('.tbl').html(data);	
				}
			});
		}
	});

	
	$('#view_all').click(function(event)
	{	
		event.preventDefault();
		$('.success_message').empty();
		$('.error_message').empty();
		$('#specific').empty();
		document.querySelector('#general').style.visibility="visible";

	  	var request="View all marks";

		$.ajax({
			url:'Requests/review_marks_requests.php',
			method:'post',
			data:{request:request},
			success:function(data)
			{
				$('.tbl').html(data);
			}
		});
	});

	$('#delete_all_specific').click(function(event)
	{	
	  	var request="Delete all marks";
	  	var selected_course = $('#courses').children("option:selected").val();
		var selected_class = $('#classes').children("option:selected").val();
		var selected_unit = $('#units').children("option:selected").val();
		
		if(confirm("Are you sure you want to delete all "+selected_unit+" marks for "+selected_class+" class?"))
		{
			$.ajax({
				url:'Requests/review_marks_requests.php',
				method:'post',
				data:{request:request, selected_course: selected_course, selected_unit:selected_unit, selected_class:selected_class},
				success:function(data)
				{
					$('.tbl').html(data);
				}
			});
		}else{
			event.preventDefault();
		}	
	});

	$('#delete_all').click(function(event){
		var request="Delete this session marks";
		
		if(confirm("Are you sure you want to delete all marks for this term session?"))
		{
			$.ajax({
				url:'Requests/review_marks_requests.php',
				method:'post',
				data:{request:request},
				success:function(data)
				{
					$('.tbl').html(data);
				}
			});
		}else{
			event.preventDefault();
		}	
	});	

	$('#units').change(function(){
		var selected_course = $('#courses').children("option:selected").val();
		var selected_class = $('#classes').children("option:selected").val();
		var selected_unit = $('#units').children("option:selected").val();
		$.ajax({
			url:'View/review_marks_new.php',
			method:'post',
			data:{selected_course: selected_course, selected_unit:selected_unit, selected_class:selected_class},

			success:function(data)
			{
				$('.tbl').html(data);	
			}
		})
		
	});
	
	$('#classes').change(function(){
		var selected_course = $('#courses').children("option:selected").val();
		var selected_class = $('#classes').children("option:selected").val();
		var selected_unit = $('#units').children("option:selected").val();

		$.ajax({
			url: 'New/select_units.php',
			type: 'post',
			data: {selected_course:selected_course, selected_class:selected_class},
			dataType: 'json',
			success:function(response){

				var len = response.length;

				$("#units").empty();

				if(len==0){
					$('.success_message').empty();
					$('.tbl').empty();
					$("#units").append("<option value=''>No unit found</option>");
					$('.error_message').html("<div class='alert alert-warning'><strong>Error!</strong>&nbspCourse "+selected_course+" has no units</div>");
				}
				else{
					for( var i = 0; i < len; i++){
						var unit_code = response[i]['unit_code'];
						var unit_name = response[i]['unit_name'];
						
						$("#units").append("<option value='"+unit_code+"'>"+unit_name+"</option>");
					}
				}
			} 
		});
		$('.tbl').empty();
	});
});
</script>