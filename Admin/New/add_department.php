<?php 
include('../../Database/config.php');
?>
<html lang="en">
<head>
    
		
<style>
.card{
	width: fit-content;
	margin: auto;
}
.row{
	margin-top: 10px;
}
</style>

</head>

<body>
	<div class="card card-info card-outline">
		<div class="card-header" > Add Department</div>
		<div class="card-body">
			<form id=form method=post>
				<div class="row">
					<div class="col-md-6">
						<h6>Department name:</h6>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control form-control-sm" name="department_name">
					</div>
				</div>
				<!-- <div class="row">
					<div class="col-md-6">
						<h6>Department Head:</h6>
					</div>
					<div class="col-md-6">
						<select class="form-control form-control-sm" name=hod>
							<option  value="">--Select--</option>
							<?php 
								// while($row=mysqli_fetch_assoc($hods)) {
								// 	echo '<option value='.$row['trainer_id'].'>'.strtoupper($row['trainer_name']).'</option>';
								// }				
							?>
						</select>
					</div>
				</div> -->
			</form>
		</div>
		<div class=card-footer>
			<button type=button class="btn btn-info col-md-3 float-right" id=save_dept>Save</button>
		</div>
	</div>
</body>

<script>
$(document).ready(function() {	
	
$('#save_dept').click(function()
	{
		$.ajax({
			url:'Insertdata/save_department.php',
			method:'post',
			data:$('#form').serialize(),
			
			success:function(data)
			{
				alert(data);
				$('#form').trigger('reset');
			}
		})

		})
	})
</script>
</html>