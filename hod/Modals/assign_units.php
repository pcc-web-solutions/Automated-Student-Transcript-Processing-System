<?php 
session_start();

	$dept = $_SESSION['dept'];
	include("../../Database/config.php");

	$trainers=$conn->query("SELECT * FROM trainers INNER JOIN departments ON trainers.department_id = departments.department_code WHERE departments.department_code = '$dept' ORDER BY trainer_id ASC"); 	
	$no_of_trainers = $trainers->num_rows;

	$courses=$conn->query("SELECT DISTINCT(code), course_name FROM courses INNER JOIN classes ON classes.course_abrev = courses.course_abrev INNER JOIN departments ON departments.department_code = courses.department_code WHERE departments.department_code = '$dept' ORDER BY course_name ASC");

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
					<div class="col-md-4 col-sm-4">
						<select class="form-control form-control-sm" name="trainers" id="trainer">
							<option value="">--select trainer--</option>
							<?php
								while ($trainer=mysqli_fetch_assoc($trainers)) {
									echo "<option value = ".$trainer['trainer_id'].">".strtoupper($trainer['first_name']." ".$trainer['last_name'])."</option>";
								}
							?>
						</select>
					</div>
					<div class="col-md-4 col-sm-4">
						<select class="form-control form-control-sm" name="courses" id="course">
							<option value="">--select course--</option>
							<?php
								while ($course=mysqli_fetch_assoc($courses)) {
									echo "<option value = ".$course['code'].">".strtoupper($course['course_name'])."</option>";
								}
							?>
						</select>
					</div>
					<div class="col-md-4 col-sm-4">
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
			position: 'top-end',
			showConfirmButton: false,
			timer: 3000
		});
	})
</script>

<script type="text/javascript">
	$(document).ready(function(){
		$('#refresh').click(function(){
				var trainer = $('#trainer').children("option:selected").val();
				var course = $('#course').children("option:selected").val();
				var selected_class = $('#class').children("option:selected").val();

				if(course != '' && selected_class != ''){
					$.ajax({
						url: 'New/assigned_units.php',
						type: 'post',
						data: {selected_course:course, selected_class:selected_class, selected_trainer:trainer},
						success:function(data){
							if(data != 'No units'){
								$('#chosen_units').html(data);
							}
							else{
								$('#chosen_units').empty();
								$('#chosen_units').empty();
								toastr.error('No units assigned to the selected trainer for that class');
							}
						} 
					}); 

					$.ajax({
			            url: 'New/choose_units.php',
			            type: 'post',
			            data: {selected_course:course, selected_class:selected_class, selected_trainer:trainer},
			            success:function(data){
							if(data != 'No units'){
								$('#available_units').html(data);
							}
							else{
								$('#available_units').empty();
								$('#available_units').empty();
							}
			            } 
			        });
				}
				
			})
	});
</script>

<script>
	$(document).ready(function () {

		$('#search').on('keyup', function() {
		var value = $(this).val().toLowerCase();
		$('#records').filter(function() {
		  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	  });

		$('#trainer').change(function(){
			var trainer = $('#trainer').children("option:selected").val();
			var course = $('#course').children("option:selected").val();
			var selected_class = $('#class').children("option:selected").val();

			if(course != '' && selected_class != ''){
				$.ajax({
					url: 'New/assigned_units.php',
					type: 'post',
					data: {selected_course:course, selected_class:selected_class, selected_trainer:trainer},
					success:function(data){
						if(data != 'No units'){
							$('#chosen_units').html(data);
						}
						else{
							$('#chosen_units').empty();
							$('#chosen_units').empty();
							toastr.error('No units assigned to the selected trainer for that class');
						}
					} 
				}); 

				$.ajax({
		            url: 'New/choose_units.php',
		            type: 'post',
		            data: {selected_course:course, selected_class:selected_class, selected_trainer:trainer},
		            success:function(data){
						if(data != 'No units'){
							$('#available_units').html(data);
						}
						else{
							$('#available_units').empty();
							$('#available_units').empty();
						}
		            } 
		        });
			}
			
		})

	  $('#course').change(function(){
	  	// $('#save').addClass('disabled');
		var post = "posted";
		var trainer = $('#trainer').children("option:selected").val();
		var course = $('#course').children("option:selected").val();
		var selected_class = $('#class').children("option:selected").val();
		$.ajax({
            url: 'New/select_class_for_registration.php',
            type: 'post',
            data: {selected_course:course, selected_class:selected_class, selected_trainer:trainer},
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

		$.ajax({
            url: 'New/assigned_units.php',
            type: 'post',
            data: {selected_course:course, selected_class:selected_class, selected_trainer:trainer},
            success:function(data){
				if(data != 'No units'){
					$('#chosen_units').html(data);
				}
				else{
					$('#chosen_units').empty();
					$('#chosen_units').empty();
				}
            } 
        }); 
        $('#available_units').empty();
		$('#available_units').empty();

	}); 

	$('#class').change(function(){
		var trainer = $('#trainer').children("option:selected").val();
		var course = $('#course').children("option:selected").val();
		var selected_class = $('#class').children("option:selected").val();

		$.ajax({
            url: 'New/choose_units.php',
            type: 'post',
            data: {selected_course:course, selected_class:selected_class, selected_trainer:trainer},
            success:function(data){
				if(data != 'No units'){
					$('#available_units').html(data);
				}
				else{
					$('#available_units').empty();
					$('#available_units').empty();
				}
            } 
        });

        $.ajax({
            url: 'New/assigned_units.php',
            type: 'post',
            data: {selected_course:course, selected_class:selected_class, selected_trainer:trainer},
            success:function(data){
				if(data != 'No units'){
					$('#chosen_units').html(data);
				}
				else{
					$('#chosen_units').empty();
					$('#chosen_units').empty();
					toastr.error('No units assigned to the selected trainer for that class');
				}
            } 
        }); 
	}); 
});
</script>

<script type="text/javascript">
	$(document).ready(function(){
		$('input[type="checkbox"]').click(function(){

			var selected_trainer = $('#trainer').children("option:selected").val();
			var selected_course = $('#course').children("option:selected").val();
			var selected_class = $('#class').children("option:selected").val();
			var selected_unit = $(this).attr('id');

			if($(this).prop("checked") == true){
				$.ajax({
		            url: 'Insertdata/inserttrainer_units.php',
		            method: 'post',
		            data: {trainers:selected_trainer,courses:selected_course,classes:selected_class,unitchoice:selected_unit},
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