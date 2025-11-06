<?php
include("../../Database/config.php");

if(isset($_POST['record_no'])){
	$department = $_POST['record_no'];
	
	$qry = $conn->query("SELECT departments.department_code, departments.department_name, department_hods.hod, trainers.first_name, trainers.last_name FROM departments LEFT JOIN trainers ON trainers.department_id = departments.department_code LEFT JOIN department_hods ON department_hods.hod = trainers.trainer_id WHERE departments.department_code = '$department'");

	while($row=mysqli_fetch_assoc($qry)){
		$department_id=$row['department_code'];
		$department_name = $row['department_name'];
		$hod_id = $row['hod'];
		$hod_name = $row['first_name']." ".$row['last_name'];
	}

	$hods="SELECT * FROM trainers INNER JOIN departments ON departments.department_code = trainers.department_id WHERE departments.department_code = '$department' ";
	$query=mysqli_query($conn, $hods);
	
echo '<html>
    <body>
        <form action="#" id="Department_form" class="form">
            <div class="card editted">
   <div class="card-header">
   <h3 class="card-title">Edit Department</h3>
   </div>
   <div class="card-body">
					<div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-6">
                            <h6>Identity Number:</h6>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control form-control-sm" name="deptid"  readonly value='.$department_id.' >
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-6">
                            <h6>Full Name:</h6>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control form-control-sm" name="deptname" value ="'.$department_name.'">
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-6">
                            <h6>Department Head:</h6>
                        </div>
						<div class="col-md-6">
						<select class="form-control  form-control-sm" name=hod>
							<option  value='.$hod_id.'>'.strtoupper($hod_name).'</option>';
							if ($query->num_rows>0) {
								echo "<option value = ''>--Select trainer--</option>";
								while($row=mysqli_fetch_assoc($query)) {
									echo '<option value='.$row['trainer_id'].'>'.strtoupper($row['first_name']." ".$row['last_name']).'</option>';
								}
							}
							else{echo "<option value = ''>No trainers found</option>";}
							echo' 
						</select>
					</div>
					
				</div>

						
              </div>
                    
                
                <div class="card-footer ">
                    <button type="button" name="update_department" id=update_department class="btn btn-info float-right">Update</button>
                </div>
            </div>  
        </form>
    </body>

    <style>
    	.editted{
    		margin: auto;
    		width: fit-content;
    	}
    </style>
</html>'; } ?>

<script>
$(document).ready(function () {
$('#update_department').click(function()
  {	
 
  	 $.ajax({url:'Update/update_department.php',
	  method:'post',
	  data:$('#Department_form').serialize(),
	  
	  	success:function(data)
		{
			alert(data);
			
		}
		
  })
	
    })
  
  
})	


</script>