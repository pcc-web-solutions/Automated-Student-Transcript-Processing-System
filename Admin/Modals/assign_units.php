<?php 
	include("../../Database/config.php");

	$sql = "SELECT DISTINCT departments.department_code, department_name FROM departments INNER JOIN courses ON courses.department_code = departments.department_code INNER JOIN units on units.courses_code = courses.code ORDER BY department_name ASC";
	$departments = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" type="text/css" href="../Css/assign_units.css">
</head>
<body>
	<div class="card card-warning card-outline">
		<div class="card-header">
			<h4 class="card-title text-muted"><strong>Unit Assignment Dialog</strong></h4>
		</div>
		<div class="card-body">
			<form id="form">
				<div class="row">
					<div class="col-md-3 col-sm-3">
						<select class="form-control form-control-sm" name="dept" id="dept">
							<option value="">--select department--</option>
							<?php
								while($department=mysqli_fetch_assoc($departments)) {
									echo "<option value = ".$department['department_code'].">".strtoupper($department['department_name'])."</option>";
								}
							?>
						</select>
					</div>
					<div class="col-md-3 col-sm-3">
						<select class="form-control form-control-sm" name="trainers" id="trainer">
							<option value="">--select trainer--</option>
						</select>
					</div>
					<div class="col-md-3 col-sm-3">
						<select class="form-control form-control-sm" name="courses" id="course">
							<option value="">--select course--</option>
						</select>
					</div>
					<div class="col-md-3 col-sm-3">
						<select class="form-control form-control-sm" name="classes" id="class">
							<option value="">--select class--</option>
						</select>
					</div>
				</div>

				<hr style="margin-bottom: 10px; margin-top: 10px;">

				<div class="row">
					<div class="col-md-6 col-sm-6">
						<div class="text_heading_1 text-muted">
							<strong class="text-muted p-2">Select units</strong>
						</div>
						<div class="card table-responsive">
							<div id="available_units"></div>
						</div>
					</div>
					<div class="col-md-6 col-sm-6">
						<div type="button" class="btn float-left text_heading_2 text-muted" id="refresh" >
							<strong class="text-muted p-2">Assigned Units</strong>
						</div>
						<div class="card table-responsive">
							<div id="chosen_units"></div>
						</div>
					</div>
				</div>
			</form>
		</div>

		<div class="card-footer">
			
		</div>
	</div>

	<!-- jQuery -->
    <script src="../../Assets/plugins/jquery/jquery.min.js"></script>
    <script src="../../Assets/plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="../../Assets/plugins/toastr/toastr.min.js"></script>
</body>

<script type="text/javascript">
	$(document).ready(function(){
		const Toast = Swal.mixin({
			toast: true,
			position: 'top-middle',
			showConfirmButton: true,
			timer: 3000
		});
	})
</script>

<script type="text/javascript">
	$(document).ready(function(){
		function dept(){return $('#dept').children("option:selected").val();}
		function trainer(){return $('#trainer').children("option:selected").val();}
		function course(){return $('#course').children("option:selected").val();}
		function selected_class(){return $('#class').children("option:selected").val();}
		function selected_unit(code){return code}

		function validate_inputs(){
			if(dept() != '' && trainer() != '' && course() != '' && selected_class() != ''){return 'success'}else{return 'error';}
		}

		function load_trainers(){
			$.ajax({
	            url: 'Requests/req_trainers_in_this_dept.php',
	            type: 'post',
	            data: {dept:dept()},
	            dataType: 'json',
	            success:function(response){
	                var len = response.length;
	                $("#trainer").empty();
	                $("#trainer").append("<option value=''>--select trainer--</option>");
					for( var i = 0; i < len; i++){
	                    var trainer_code = response[i]['trainer_code'];
	                    var trainer_name = response[i]['trainer_name'];
	                    $("#trainer").append("<option value='"+trainer_code+"'>"+trainer_name+"</option>");
	                }
	            } 
	        });
		}
		function load_courses(){
			$.ajax({
	            url: 'Requests/req_courses.php',
	            type: 'post',
	            data: {dept:dept()},
	            dataType: 'json',
	            success:function(response){
	                var len = response.length;
	                $("#course").empty();
	                $("#course").append("<option value=''>--select course--</option>");
					for( var i = 0; i < len; i++){
	                    var course_code = response[i]['code'];
	                    var course_name = response[i]['course_name'];
	                    $("#course").append("<option value='"+course_code+"'>"+course_name+"</option>");
	                }
	            } 
	        });
		}
		function load_classes(){
			$.ajax({
	            url: 'New/select_class_for_unit_allocation.php',
	            type: 'post',
	            data: {selected_course:course(), selected_class:selected_class(), selected_trainer:trainer()},
	            dataType: 'json',
	            success:function(response){

	                var len = response.length;

	                $("#class").empty();
	                $("#class").append("<option value=''>--select class--</option>");
					for( var i = 0; i < len; i++){
	                    var class_name = response[i]['class_name'];
	                    $("#class").append("<option value='"+class_name+"'>"+class_name+"</option>");
	                }
	            } 
	        });
		}
		function load_unassigned_units(){
			$.ajax({
	            url: 'New/choose_units.php',
	            type: 'post',
	            data: {selected_course:course(), selected_class:selected_class(), selected_trainer:trainer()},
	            success:function(data){
					if(data != 'No units'){
						$('#available_units').html(data);
					}
					else{
						$('#available_units').empty();
					}
	            } 
	        });
		}

		function load_assigned_units(){
			$.ajax({
				url: 'New/assigned_units.php',
				type: 'post',
				data: {selected_course:course(), selected_class:selected_class(), selected_trainer:trainer()},
				success:function(data){
					if(data != 'No units'){
						$('#chosen_units').html(data);
					}
					else{
						$('#chosen_units').empty();
						toastr.error('No units assigned to the selected trainer for that class');
					}
				} 
			});
		}

		function reload(){
			if(validate_inputs() == 'success'){
				load_unassigned_units()
				load_assigned_units()
			}
		}

		$('#refresh').click(function(){
			reload()	
		})

		$('#dept').change(function(){
			$("#class").empty();
	        $("#class").append("<option value=''>--select class--</option>");
			$('#available_units').empty();
			$('#chosen_units').empty();
			load_courses()
			load_trainers()
		})

		$('#trainer').change(function(){
			if(validate_inputs() == 'success'){
				load_unassigned_units()
				load_assigned_units()
			}
		})

		$('#course').change(function(){
			load_classes()
	        $('#available_units').empty();
	        $('#chosen_units').empty();
		}); 

		$('#class').change(function(){
			if(validate_inputs() == 'success'){
				load_unassigned_units()
		        load_assigned_units()
	    	}
		});
	});
</script>

<script type="text/javascript">
	$(document).ready(function(){
		$('input[type="checkbox"]').click(function(){
			if($(this).prop("checked") == true){
				$.ajax({
		            url: 'Insertdata/inserttrainer_units.php',
		            method: 'post',
		            data: {trainers:trainer(),courses:course(),classes:selected_class(),unitchoice:selected_unit($(this).attr('id'))},
		            success:function(data){
		            	alert(data);
		            } 
		        });
			}
			else if($(this).prop("checked") == false){
				alert("Unchecked");
			}
		})
	})
</script>

<style type="text/css">
	.text_heading_1, .text_heading_2{
		width: 100%;
		height: 40px;
		text-decoration-color: inherit;
		margin-bottom: 10px;
		border-radius: 5px;
		padding: 5px;
	}
	.text_heading_1{
		background-color: cyan;
	}
	.text_heading_2{
		background-color: lightskyblue;
	}
</style>

</html>