<?php
include('../../Database/config.php');

$get_departments = $conn->query("SELECT * FROM departments INNER JOIN trainers ON trainers.department_id = departments.department_code GROUP BY departments.department_code ORDER BY departments.department_code ASC");
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- Theme style -->
		<link rel="stylesheet" href="dist/css/adminlte.min.css">
		<link rel="stylesheet" type="text/css" href="../Css/assign_hods.css">
	</head>
	<body>
   
 		<div class="card card-info">
 			<div class="card-header">
 				<h5 class="card-title text-center">Assign Department HODs</h5>
	 		</div>	
 			<div class="card-body">
 				<form id="form">
 					<div class="row">
 						<div class="col-md-4" style="margin-bottom: 10px;">
 							<select class="form-control" id="select_department">
 								<option value="">--choose department--</option>
 								<?php
 									if($get_departments->num_rows>0){
 										while ($dept=mysqli_fetch_assoc($get_departments)) {
 											echo "<option value=" .$dept['department_code']. "> ".strtoupper($dept['department_name'])." </option>";
 										}
 									}
 								?>
 							</select>
 						</div>
 						<div class="col-md-4" style="margin-bottom: 10px;">
 							<select class="form-control" id="select_trainer">
 								<option value="">--choose trainer--</option>
 							</select>
 						</div>
 						<div class="col-md-4" style="margin-bottom: 10px;">
 							<button type="button" class="btn btn-primary btn-block float-right" id="save"><i class="fa fa-save"></i>&nbsp Click here to assign</button>
 						</div>
 					</div>
 				</form>
 				<hr>
 				<div id="error_message"></div>
 				<div id="success_message"></div>
 				<div id="ajax-response"></div>
 			</div>
 		</div>

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
</body>

</html>
<script>
$(document).ready(function(){
	
	$('#save').click(function(e)
	{	
		e.preventDefault();
		$('#success_message').empty();
		$('#error_message').empty();

		var selected_department = $('#select_department').children("option:selected").val();
		var selected_trainer = $('#select_trainer').children("option:selected").val();

		if(selected_department == ''){
			$('#success_message').empty();
			$('#error_message').html('<div class="alert alert-warning"><strong>Error! </strong> Department not selected</div>');
		}
		else if(selected_trainer == ''){
			$('#success_message').empty();
			$('#error_message').html('<div class="alert alert-warning"><strong>Error! </strong> Trainer not selected</div>');
		}
		else{
			
			$.ajax({
				url:'Insertdata/insert_department_hod.php',
				method:'post',
				data:{department:selected_department, trainer:selected_trainer},
			 	success:function(data)
				{
					if(data == "Error")
		
						$('#error_message').html('<div class="alert alert-warning"><strong>Error! </strong> Department H.O.D is already assigned to that department</div>');
					else{
						$('#error_message').empty();
						$('#success_message').html('<div class="alert alert-success alert-dismissible"><strong>Success! </strong> H.O.D assigned successfully</div>');
						$('#ajax-response').html('<div class="table-responsive"> <table class ="table-striped text-nowrap" cellspacing="1">	<thead id="thead_response">	<tr><th>Department Code</th> <th>Department Name</th> <th>H.O.D Name</th> </tr>	</thead> <tbody id=inserteddata> </tbody> </table> </div>');
						$('#inserteddata').prepend(data);
					}
				}
			});
		}	
	});
});
</script>

<script type="text/javascript">
	
	$(document).ready(function(){
		$('#select_department').on("change", function(e){
			e.preventDefault();
			$('#success_message').empty();
			$('#error_message').empty();
			var department = $('#select_department').children("option:selected").val();
			$.ajax({
				url:'New/select_trainers.php',
				method: 'post',
				data: {selected_department:department},
				dataType: 'json',
				success: function(response){
					var len = response.length;
	                $('#select_trainer').empty();
					for( var i = 0; i < len; i++){
	                    var trainer_id = response[i]['trainer_code'];
	                    var trainer_name = response[i]['trainer_name'];
	                    $('#select_trainer').append("<option value='"+trainer_id+"'>"+trainer_name+"</option>");
	                }
				}
			});		
		});

		$('#select_trainer').on("change", function(e){
			e.preventDefault();
			$('#success_message').empty();
			$('#error_message').empty();
		});
	});
</script>