<?php
include("../../Database/config.php");

if($_POST['record_no'] == NULL){
	echo "Error";
}
else{
	$trainer = $_POST['record_no'];
	
		$qry = "SELECT trainers.trainer_id, trainers.first_name, trainers.last_name, trainers.phone_no, trainers.department_id, departments.department_name FROM (trainers 
		INNER JOIN departments ON trainers.department_id = departments.department_code) 
		WHERE trainer_id = '$trainer'";
	
	$results=mysqli_query($conn, $qry) or die("Error running query");
	
	while($row=mysqli_fetch_assoc($results)){
		$trainer_id= $row['trainer_id'];
		$tr_fname = $row['first_name'];
		$tr_lname = $row['last_name'];
		$trainer_phone = $row['phone_no'];
		$department_id = $row['department_id'];
		$department_name = $row['department_name'];
	
		}

	$departments="select department_name, department_code from departments";
	
	$query=mysqli_query($conn, $departments);
	
echo '<html>
    <body>
        <form action="#" id="Trainer_form" class="form">
            <div class="card">
   <div class="card-header">
   <h3 class="card-title">Edit trainer</h3>
   </div>
	<div class="card-body ">
					<div class="row">
                        <div class="col-md-6">
                            <h6>Identity Number:</h6>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control form-control-sm" name="trainer_id"  readonly value='.$trainer_id.' >
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h6>First Name:</h6>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control form-control-sm" name="first_name" value ="'.$tr_fname.'">
                        </div>
                    </div>
					<div class="row">
                        <div class="col-md-6">
                            <h6>Last Name:</h6>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control form-control-sm" name="last_name" value ="'.$tr_lname.'">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Phone Number:</h6>
                        </div>
                        <div class="col-md-6">
                            <input type="number" class="form-control form-control-sm" name="trainer_phone" value ='.$trainer_phone.'>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Department Name:</h6>
                        </div>
                        
				<div class="col-md-6">
				<select class="form-control  form-control-sm department" name=department_id>
				<option  value='.$department_id.'>'.$department_name.'</option>';

				while($row=mysqli_fetch_assoc($query)) {

				echo '<option value='.$row['department_code'].'>'.$row['department_name'].'</option>';
				}
				
				echo' 
				</select>

				</div>
				</div>

						
                    </div>
                    
                
                <div class="card-footer ">
                    <button type="button" name="update_trainer" id=update_trainer class="btn btn-info float-right col-md-3">Update</button>
                </div>
            </div>  
        </form>
    </body>
<style>
.card{
	width: fit-content;
	margin: auto;
}
</style>
    
</html>'; } ?>

<style>

</style>

<script>
$(document).ready(function () {
$('#update_trainer').click(function()
  {	
 
  	 $.ajax({url:'Update/update_trainer.php',
	  method:'post',
	  data:$('#Trainer_form').serialize(),
	  
	  	success:function(data)
		{
			alert(data);
			
		}
		
  })
	
    })
  
  
})	


</script>