<?php
	require('../../Database/config.php');
	$departments = $conn->query("SELECT * FROM departments");
	?>
<!DOCTYPE html>
<html lang="en">
<head>
    
		
<style>
input{
	margin-bottom: 10px;
}
label{
	margin-bottom: 0px;
}
</style>

</head>

<body>
	<div class="card card-info  card-outline">
    	<div class="card-header">
            <h3 class="card-title">Add course</h3>
	    </div>
	    <div class="card-body">
			<form class=form method=post id=form>
				<label> Course code</label>
				<input type=text class="form-control form-control-sm" id=course_code placeholder="e.g 1920/1" name='course_code'>
				<label> Course name</label>
				<input type=text class="form-control form-control-sm" id=course_name placeholder="e.g DIPLOMA IN INFORMATION STUDIES" name ='course_name'>
				<label> Abreviation</label>
				<input type=text class="form-control form-control-sm" id=abrev placeholder="e.g DICT1">
			</form>
 
		 </div>
		 <div class="card-footer">
		 	<button type="button" class="btn btn-info float-right" id=button style=width:25%>Save</button>
		 </div>
	</div>
</body>

<script>

		
$(document).ready(function() {	

	$('#button').click(function()
	{
		
		var code = $('#course_code').val();
		var course_name = $('#course_name').val();
		var course_abrev = $('#abrev').val();
		
		if(code == ""){
			toastr.error('please enter course code');
		}
		if(course_name == ""){
			toastr.error('please enter course code');
		}
		else if(course_abrev == ""){
			toastr.error('Please enter course abreviation');
		}
		else{
			// alert('Submit');
			$.ajax({
				url:'insertdata/insert_course.php',
				method:'post',
				data:{code:code,coursename:course_name,abrev:course_abrev},
				
				success:function(data)
				{
					if(data == "Saved"){
						toastr.success("Course registered successfully");
					}
					else{
						toastr.error(data);
					}
					$('.form').trigger('reset'); 
				}
		
			})
		}
			
	})
	
})
	
</script>

</html>