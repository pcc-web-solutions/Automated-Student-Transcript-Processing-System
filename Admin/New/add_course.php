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
<div class=row>
<div class="mx-auto">
<div class="card card-info  card-outline">
    <div class="card-header">
                <h3 class="card-title">Add course</h3>
              </div>
              <div class="card-body">
<form class=form method=post id=form>
<div class=row>
<div class="col-lg-12">

<label> Course code</label>
<input type=text class="form-control form-control-sm" id=course_code placeholder="e.g 1920/1" name='course_code'>
<label> Course name</label>
<input type=text class="form-control form-control-sm" id=course_name placeholder="e.g DIPLOMA IN INFORMATION STUDIES" name ='course_name'>
<label> Abreviation</label>
<input type=text class="form-control form-control-sm" id=abrev placeholder="e.g DICT1">
<label> Department</label>
<select class="form-control form-control-sm" name=department>
<option selected style='display:none'>Select department</option>
<?php
while($row=mysqli_fetch_assoc($departments)) {
?>
<option value="<?php echo $row['department_code'];?>"><?php echo $row['department_name'];?>
</option>
<?php }
?>
</select>
                 
<br>
 <button type="button" class="btn btn-info float-right" id="button" style=width:50%>OK</button></form>
 
 </div>
</div>
</div>
</div>
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
		var department = $('select[name=department]').val();
		
		if(code == ""){
			toastr.error('please enter course code');
		}
		if(course_name == ""){
			toastr.error('please enter course code');
		}
		else if(course_abrev == ""){
			toastr.error('Please enter course abreviation');
		}
		else if(department == ""){
			toastr.error('Please select department');
		}
		else{
			$.ajax({
				url:'Insertdata/insert_course.php',
				method:'post',
				data:{code:code,coursename:course_name,abrev:course_abrev,department:department},
				
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