<?php
	include("../../Database/config.php");

	$get_courses = $conn->query("SELECT DISTINCT(courses.code), courses.course_name FROM courses INNER JOIN trainees ON trainees.course_code = courses.code WHERE trainees.status = '1' ORDER BY course_name ASC");

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
</head>
<body>
	<div class="card card-info card-outline">
		<div class="card-header">
			<h4 class="card-title">Generate Exam Attendance Sheet</h4>
			<span id="sheet_specification" class="float-right">
				<input type="radio" name="specification" value="blank" checked>&nbsp<strong>Blank sheet</strong>
				<input type="radio" name="specification" value="autofilled">&nbsp<strong> Auto-filled with trainees</strong>
			</span>
		</div>
		<div class="card-body">
			
			<div id="blank_rows">
				<div class="row">
					<div class="col-md-2" style="margin-bottom: 10px;">
						<select class="form-control form-control-sm" id="course">
							<option value="">--Select course--</option>
							<?php
							if ($get_courses->num_rows>0) {
								while ($row = $get_courses->fetch_array()) {
									echo "<option value = ".$row['code'].">".$row['course_name']."</option>";
								}
							}else{echo "<option selected value=''>No courses found</option>";}
							?>
						</select>
					</div>
					<div class="col-md-2" style="margin-bottom: 10px;">
						<select class="form-control form-control-sm" id="class">
							<option selected value="">--Select class--</option>
						</select>
					</div>
					<div class="col-md-2" style="margin-bottom: 10px;">
						<select class="form-control form-control-sm" id="unit">
							<option selected value="">--Select unit--</option>
						</select>
					</div>
					<div class="col-md-2"style="margin-bottom: 10px;">
						<select class="form-control form-control-sm" id="supervisor">
							<option selected value="">--Supervisor--</option>
						</select>
					</div>
					<div class="col-md-2" style="margin-bottom: 10px;">
						<input type="date" id="exam_date" placeholder="Exam Date" class="form-control form-control-sm">
					</div>
					<div class="col-md-1" style="margin-bottom: 10px;">
						<input type="number" id="no_of_trainees" placeholder="No of trainees" class="form-control form-control-sm">
					</div>
					<div class="col-sm-1" style="margin-bottom: 10px;">
						<button type="button" class="btn btn-primary float-right form-control-sm" id="generate">Generate</button>
					</div>
				</div>
			</div>	

			<div class=".error_message"></div>
			<div class=".success_message"></div>
			<div class="report"></div>
		</div>
		
	</div>
	<!-- jQuery -->
	<script src="../Libraries/plugins/jquery/jquery.min.js"></script>
	<script type="text/javascript" src="exam_attendance_sheet.js"></script>
</body>

<style type="text/css">
	#btn_specification{
		padding-top: 1px;
		padding-bottom: 1px;
	}
	#generate{
		padding-bottom: 2px;
		padding-top: 2px;
	}
</style>
</html>

<script type="text/javascript">
	$(document).ready(function(){

		var choice = $('input[name=specification]:checked').val();
		if(choice == 'blank'){
			document.querySelector('#no_of_trainees').style.visibility = "visible";
		}
		else if(choice == 'autofilled'){
			document.querySelector('#no_of_trainees').style.visibility = "hidden";
		}
		else{

		}
	})
</script>

<script type="text/javascript">
	$(document).ready(function(){
		const Toast = Swal.mixin({
			toast: true,
			position: 'top-end',
			showConfirmButton: false,
			timer: 3000
		});
		$('#generate').click(function(){

			var choice = $('input[name=specification]:checked').val();
			var selected_course=$('#course').children("option:selected").val();
			var selected_class=$('#class').children("option:selected").val();
			var selected_unit=$('#unit').children("option:selected").val();
			var selected_trainer=$('#supervisor').children("option:selected").val();
			var exam_date = $('input[id=exam_date]').val();
			var no_of_trainees = $('input[id=no_of_trainees]').val();

			if(selected_course == ''){
				toastr.error('Course not selected.')
			}else if(selected_class == ''){
				toastr.error('Class not selected.')
			}else if(selected_unit == ''){
				toastr.error('Unit not selected.')
			}
			else if(exam_date == ''){
				toastr.error('Please specify when the exam will take place')
			}
			else if(choice == 'blank' && no_of_trainees == ''){
				toastr.error('Please specify the number of trainees for blank attendance sheet')
			}
			else{
				$.ajax({
					url: "Sessions/exam_attendance.php",
					method: "post",
					data: {choice:choice, course:selected_course, class:selected_class, unit:selected_unit, supervisor:selected_trainer, exam_date:exam_date, no_of_trainees:no_of_trainees},
					success: function(data){
						toastr.success('Success. Exam attendance sheet loading successfully')
						$('.report').html('<hr style="margin-bottom: 1px"><iframe id=iframe style="border: 1px solid grey; width: 100%; height: 100vh;" ></iframe>');
						$("#iframe").attr("src", "Reports/rpt_examattendancesheet.php");
						
					}
				});
			}
		});
	})
</script>

<script type="text/javascript">
$(document).ready(function(){
	$('input[name=specification]').change(function(){	
		var choice = $('input[name=specification]:checked').val();
		if(choice == 'blank'){
			document.querySelector('#no_of_trainees').style.visibility = "visible";
		}
		else if(choice == 'autofilled'){
			document.querySelector('#no_of_trainees').style.visibility = "hidden";
		}
	});

	$('#course').change(function(){
		var selected_course=$('#course').children("option:selected").val();
		var selected_class=$('#class').children("option:selected").val();
		var selected_unit=$('#unit').children("option:selected").val();

		$('#unit').empty();
		$('#unit').append("<option value = ''>--Select unit--</option>");

		$.ajax({
			url: 'New/select_class.php',
			type: 'post',
			data: {selected_course:selected_course},
			dataType: 'json',
			success:function(response){

				var len = response.length;
				
				$("#class").empty();
				if(len==0){
					$('.success_message').empty();
					$('.report').empty();
					$("#class").append("<option value=''>No class found</option>");
					$('.error_message').html("<div class='alert alert-warning'><strong>Error!</strong>&nbspCourse "+selected_course+" has no classes</div>");
				}
				else{
					$('.error_message').empty();
					$("#class").append("<option value=''>--Select class--</option>");
					for( var i = 0; i < len; i++){
						var class_name = response[i]['class_name'];
						
						$("#class").append("<option value='"+class_name+"'>"+class_name+"</option>");
					}
				}
			} 
		});

		$.ajax({
			url: 'New/choose_trainer.php',
			type: 'post',
			data: {selected_course:selected_course},
			dataType: 'json',
			success:function(response){

				var len = response.length;
				
				$("#supervisor").empty();
				if(len==0){
					$('.success_message').empty();
					$('.report').empty();
					$("#supervisor").append("<option value=''>No trainer found</option>");
					$('.error_message').html("<div class='alert alert-warning'><strong>Error!</strong>&nbspCourse "+selected_course+" has no trainers</div>");
				}
				else{
					$('.error_message').empty();
					$("#supervisor").append("<option value=''>--Select one trainer--</option>");
					for( var i = 0; i < len; i++){
						var trainer_id = response[i]['trainer_code'];
						var trainer_name = response[i]['trainer_name'];
						
						$("#supervisor").append("<option value='"+trainer_name+"'>"+trainer_name+"</option>");
					}
				}
			} 
		});
	})

	$('#unit').change(function(){
		var selected_course = $('#course').children("option:selected").val();
		var selected_class = $('#class').children("option:selected").val();
		var selected_unit = $('#unit').children("option:selected").val();
		$.ajax({
			url:'View/rpt_exam_attendance_sheet.php',
			method:'post',
			data:{selected_course: selected_course, selected_unit:selected_unit, selected_class:selected_class},

			success:function(data)
			{
				$('.report').html('<hr style="margin-bottom: 1px"><iframe id=iframe style="border: 1px solid grey; width: 100%; height: 100vh;" ></iframe>');
				$("#iframe").attr("src", "Reports/allmarklists.php");
			}
		})
	});

	$('#class').change(function(){
		var selected_course = $('#course').children("option:selected").val();
		var selected_class = $('#class').children("option:selected").val();
		var selected_unit = $('#unit').children("option:selected").val();

		$.ajax({
			url: 'New/select_units.php',
			type: 'post',
			data: {selected_course:selected_course, selected_class:selected_class},
			dataType: 'json',
			success:function(response){

				var len = response.length;

				$("#unit").empty();

				if(len==0){
					$('.success_message').empty();
					$('.report').empty();
					$("#unit").append("<option value=''>No unit found</option>");
					$('.error_message').html("<div class='alert alert-warning'><strong>Error!</strong>&nbspCourse "+selected_course+" has no units</div>");
				}
				else{
					$("#unit").append("<option value=''>--Select unit--</option>");
					for( var i = 0; i < len; i++){
						var unit_code = response[i]['unit_code'];
						var unit_name = response[i]['unit_name'];
						
						$("#unit").append("<option value='"+unit_code+"'>"+unit_name+"</option>");
					}
				}
			} 
		});
		$('.report').empty();
	});

})
</script>