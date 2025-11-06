<?php
	session_start();
	require_once("../../Database/dbcontroller.php");
	$db_handle = new DBController();
	
	include('../../Database/config.php');

	$get_departments = $conn->query("SELECT DISTINCT departments.department_code, departments.department_name FROM departments INNER JOIN courses ON courses.department_code = departments.department_code INNER JOIN trainees ON trainees.course_code = courses.code ORDER BY department_name ASC");
?>

<html>
<head>
<script src="../../Assets/plugins/jquery/jquery.min.js"></script>
<script src="../../Assets/jquery-ui.min.js"></script>
<script>
	function user_inputs(){
		var dept = $("#dept").children("option:selected").val();
		var course = $("#course").children("option:selected").val();
		var unit = $("#unit_name").children("option:selected").val();
		var selected_class = $("#class_name").children("option:selected").val();
		toastr.info("Department:"+dept+" Course:"+course+" Unit:"+unit+" Class:"+selected_class);
	}
</script>
<style>
.row{margin-bottom:5px}
.card-body{
	padding-left:9px;
	padding-right:9px;
}
</style>
</head>

<body>
<div class="card card-info card-outline">
<div class="card-header">
<h5 class="card-title">Mark entry</h5>
</div>
<div class="card-body">   

<form class="form-horizontal" id=dialog>

<div class=row>

	<div class=col-sm-3  style="margin-bottom: 10px;">
		<select class="form-control" name=dept id=dept>
			<option value="">--Select Department--</option>
			<?php
			while($row=mysqli_fetch_assoc($get_departments)) { 
				$dept_code = $row['department_code'];
				$dept_name = $row['department_name'];
				?>
			<option value="<?php echo $dept_code;?>"><?php echo $dept_name;?></option>
			<?php } ?>
		</select>
	</div>

	<div class=col-sm-3  style="margin-bottom: 10px;">
		<select class="form-control" name="course" id="course">
			<option value="">--Select Course--</option>
		</select>
	</div>

	<div class=col-sm-2  style="margin-bottom: 10px;">
		<select class="form-control" name="unit_name"  id="unit_name">
			<option value="">--Select Unit--</option>
		</select>
	</div>

	<div class=col-sm-2  style="margin-bottom: 10px;">
		<select class="form-control" name="class_name"  id="class_name">
			<option value="">--Select Class--</option>
		</select>
	</div>
	<div class="col-md-2"  style="margin-bottom: 10px;">
		<button type="button" class="btn btn-primary float-right" id="load-trainees"><i class="fa fa-search"></i>&nbsp Get Trainees</button>
	</div>
</div>
</form>

<hr>
<div class=tbl>

</div>
</div>
</div>


</body>

<script>
$(document).ready(function()
{	
	$('#load-trainees').click(function()
	{
		var selected_course=$('#course').children("option:selected").val();
		var selected_unit=$('#unit_name').children("option:selected").val();
		var selected_class=$('#class_name').children("option:selected").val();
		
		if ($('#dept').children("option:selected").val() == "") {
			toastr.error("Please select a department to proceed");
		}
		else if ( selected_course == "" )
		{
			toastr.error('Please select course');
		}
		else if ( selected_unit == "" )
		{
			toastr.error('Please select unit');
		}
		else if ( selected_class == "" )
		{
			toastr.error('Please select class');
		}
		else{
			$.ajax({
				url:'New/mark_entry_sheet.php',
				method:'post',
				data:{selected_course: selected_course, selected_unit:selected_unit, selected_class:selected_class},

				success:function(data)
				{
					$('.tbl').html(data);	
				}
			})
		}
	})
})
</script>

<script type="text/javascript">
$(document).ready(function(){

	function get_courses(){
		var dept = $("#dept").children("option:selected").val();
		$.ajax({
			url: 'Requests/req_courses_for_mark_entry.php',
			method: 'post',
			data: {dept_code:dept},
			dataType: 'json',
			success: function(response){
				var len = response.length;
				if(len>0){
					$("#course").empty();
					$("#course").append("<option value=''>--Select Course--</option>");
					for(var i = 0; i<len; i++){
						var course_code = response[i]['course_code'];
						var course_name = response[i]['course_name'];
						$("#course").append("<option value="+course_code+">"+course_name+"</option>");
					}
				}
				else{
					$("#course").empty();
					$("#course").append("<option value=''>--No courses found--</option>");
				}
			}
		})
	}
    
    $("#dept").change(function(){
    	get_courses();
    })

    $("#course").change(function(){
        var selected_course = $('#course').children("option:selected").val();
        var selected_unit=$('#unit_name').children("option:selected").val();
		var selected_class=$('#class_name').children("option:selected").val();
		
		if(selected_course == ''){
			$('.tbl').html('<div class="alert alert-warning"><strong>Error!</strong> No classes registered for the selected course</div>');
		}
		else{
			$.ajax({
				url: 'New/select_units.php',
				type: 'post',
				data: {selected_course:selected_course},
				dataType: 'json',
				success:function(response){

					var len = response.length;

					$("#unit_name").empty();
					$("#unit_name").append("<option value=''>--Select Unit--</option>");
					if(len<1){
						$("#unit_name").append("<option value=''>No units Found</option>");
						document.querySelector('#load-trainees').style.visibility="hidden";
					}
					else{
						document.querySelector('#load-trainees').style.visibility="visible";
						for( var i = 0; i < len; i++){
							var unit_code = response[i]['unit_code'];
							var unit_name = response[i]['unit_name'];
							
							$("#unit_name").append("<option value='"+unit_code+"'>"+unit_name+"</option>");
						}
					}
				} 
			});
			
			$.ajax({
				url: 'New/select_class.php',
				type: 'post',
				data: {selected_course:selected_course},
				dataType: 'json',
				success:function(response){

					var len = response.length;

					$("#class_name").empty();
					// $("#class_name").append("<option value=''>--Select Class--</option>");
					if(len<1){
						$("#class_name").append("<option value=''>No Classes Found</option>");
						document.querySelector('#load-trainees').style.visibility="hidden";
					}
					else{
						document.querySelector('#load-trainees').style.visibility="visible";
						for( var i = 0; i < len; i++){
							var class_name = response[i]['class_name'];
							
							$("#class_name").append("<option value='"+class_name+"'>"+class_name+"</option>");
						}
					}
				} 
			});

			$('.tbl').empty();
		}
    });
});


</script>


<script type="text/javascript">
	$(document).ready(function(){
		
		
		$('#unit_name').change(function(){
			var selected_course=$('#course').children("option:selected").val();
			var selected_unit=$('#unit_name').children("option:selected").val();
			var selected_class=$('#class_name').children("option:selected").val();
			$.ajax({
				url:'New/mark_entry_sheet.php',
				method:'post',
				data:{selected_course: selected_course, selected_unit:selected_unit, selected_class:selected_class},

				success:function(data)
				{
					$('.tbl').html(data);	
				}
			})
			
		});
		
		$('#class_name').change(function(){
			var selected_course=$('#course').children("option:selected").val();
			var selected_unit=$('#unit_name').children("option:selected").val();
			var selected_class=$('#class_name').val();
			$.ajax({
				url:'New/mark_entry_sheet.php',
				method:'post',
				data:{selected_course: selected_course, selected_unit:selected_unit, selected_class:selected_class},

				success:function(data)
				{
					$('.tbl').html(data);	
				}
			})
		});

	})
</script>

</html>