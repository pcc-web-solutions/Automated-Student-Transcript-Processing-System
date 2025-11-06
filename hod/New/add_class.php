<?php
session_start();
$dept = $_SESSION['dept'];
include("../../Database/config.php");
$intakes = $conn->query("SELECT * FROM intakes");
$courses = $conn->query("SELECT DISTINCT(code), course_abrev, course_name FROM courses INNER JOIN departments ON departments.department_code = courses.department_code WHERE courses.department_code = '$dept' ORDER BY course_name ASC");
?>
<html>
    <head>
        <title></title>
    </head>
    <body>
        <form action="#" id="form" class="form">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h5 class="card-title">Add Class</h5>
                </div>
                <div class="card-body">
					<div id="errorbox"></div>
					<div id="successbox" ></div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-3">
                            <h6>Select Course:</h6>
                        </div>
                        <div class="col-md-9">
                            <select class="form-control form-control-sm" id="course" name="course">
								<option value="">--Select--</option>
								<?php 
									while($data=mysqli_fetch_assoc($courses)){
										echo "<option value=".$data['course_abrev'].">".$data['course_name']."</option>";
									}
								?>
							</select>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-3">
                            <h6>Academic Year:</h6>
                        </div>
                        <div class="col-md-9">
                           <input type="text" class="form-control form-control-sm" placeholder="e.g 2022"id="year">
                        </div>
                    </div>
					<div class="row" style="margin-top: 10px;">
                        <div class="col-md-3">
                            <h6>Intake:</h6>
                        </div>
                        <div class="col-md-9">
                            <select class="form-control form-control-sm" id="intake">
								<option value="">--Select--</option>
								<?php 
									while($data=mysqli_fetch_assoc($intakes)){
										echo "<option value=".$data['int_abrev'].">".$data['int_name']."</option>";
									}
								?>
							</select>
                        </div>
                    </div>
                </div>
                <div class="card-footer ">
                    <button type="submit" name="refresh" class="btn btn-success" id="refresh"><i class="fa fa-refresh"></i> &nbsp Refresh</button>
                    <button type="submit" name="submit" class="btn btn-primary float-right" id="submitbtn">Submit</button>
                </div>
            </div>  
        </form>
    </body>
    <style>
        button:hover{
            cursor: pointer;
        }
        
        .row{
            margin-top: 10px;
        }
        h6{
            font-size: 13px;
            font-weight: bold;
            padding-top: 5px;
        }
    </style>
</html>
<script>
    $(document).ready(function(){
		
		//
		$('#year').keyup(function(){
			$('#errorbox').style.display('none');
		});
		$('#submitbtn').click(function(event){
            event.preventDefault();
			
			var course = $('#course').val();
			var year = $('#year').val();
			var intake = $('#intake').val();
			
			if(course == ""){
				$('#errorbox').html('<div class="callout callout-danger text-center" style="color: white; background-color: brown;"><h5>Please select course</h5></div>');
			}
			else if(year == ""){
				$('#errorbox').html('<div class="callout callout-danger text-center" style="color: white; background-color: brown;"><h5>Please enter academic year</h5></div>');
			}
			else if(intake == ""){
				$('#errorbox').html('<div class="callout callout-danger text-center" style="color: white; background-color: brown;"><h5>Please select intake</h5></div>');
			}
			else{
				$.ajax({
					url: 'Insertdata/insertclass.php',
					method: 'post',
					data: {course:course, year:year, intake:intake},

					success:function(data)
					{
						// $('#successbox').html(data);
						if(data == 'Error'){
							$('#errorbox').html('<div class="callout callout-danger text-center"  style="color: white; background-color: brown;"><h5>The class is already registered</h5></div>');
							$('#successbox').style.display('none');
						}
						else if(data == 'Success'){
							$('#errorbox').html('<div class="callout callout-success text-center" style="color: white; background-color: green;"><h5>Class registered successfuly</h5></div>');
							$('#errorbox').style.display('none');
						}
					}
				});
			}
        });

        $('#refresh').click(function(event){
            event.preventDefault();
            $('#container').load('New/add_class.php');
        });
    });
</script>