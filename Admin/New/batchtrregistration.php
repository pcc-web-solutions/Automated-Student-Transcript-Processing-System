<?php
	session_start();
	include "../../Database/config.php";					
	$requirednumber = $_SESSION['nop'];
	$courses = $conn->query("SELECT course_name, code FROM courses ORDER BY course_name ASC");
	$classes = $conn->query("SELECT class_name, class_id FROM classes ORDER BY class_name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>		
<style>
	.card{width: fit-content; margin: auto;}

	.txt-heading{
		padding: 10px 10px;
	    border-radius: 2px;
	    color: #333;
	    background: #d1e6d6;
		margin:2px 0px 5px;
	}
	td, th{
		text-align:left;
		border: 1px solid brown;
	}
	tr{
		text-align:left;
		border: 1px solid brown;
	}
	thead{
		font-weight: bold;
		background-color: gold;
	}

	button{
	cursor:pointer;
	}
	input{
		border: transparent;
		background-color: transparent;
	}
	.form-control, .readonly{
		border: transparent;
		background-color: transparent;
	}
	.txt-heading{    
		padding: 10px 10px;
		border-radius: 2px;
		color: #333;
		background: #d1e6d6;
		margin:2px 0px 5px;
	}
</style>

</head>
<body>
<div class="card card-info card-outline">
<div class="card-header" ><center><?php echo '<h6>MULTIPLE PUPILS REGISTRATION FORM</h6>'; ?></center></div>
<div class="card-body" style=" padding: 30px">
<form id=form action=#>
   	<div class="table-responsive">
        <table class="table-striped text-nowrap">
            <thead>
                <tr>
                    <td>SN</td>
					<td>Adm. No</td>
                    <td>First Name</td>
                    <td>Last Name</td>
                    <td>Gender</td>
                    <td>Class</td>
                    <td>Course</td>
                </tr>
            </thead>
            <tbody id="records">
            <?php 
                for($i=1; $i<=$requirednumber; $i++){
                ?>
                    <tr id="datainput">
                        <td><?php echo $i; ?></td>
						<td><input type =text id=<?php echo $i ; ?> name=adm[] class="form-control form-control-sm adm" ></input></td>
                        <td><input type =text id=<?php echo $i ; ?> name=fname[] class="form-control form-control-sm fname" ></input></td>
                        <td><input type =text id=<?php echo $i ; ?> name=lname[] class="form-control form-control-sm lname" ></input></td>
                        <td>
                            <select name=gender[] class="form-control form-control-sm" >
                                <option value="">--Select--</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </td>
                        <td>
							<div class="col-md-12">
								<select id=class name="class[]" class="form-control form-control-sm" >
									<option value="">--Select--</option>
									<?php
										while($data=mysqli_fetch_assoc($classes)){
											echo "<option value=".$data['class_name'].">".strtoupper($data['class_name'])."</option>";
										}
									?>
								</select>
							</div>
						</td>
                        <td>
                            <select name=course[] class="form-control form-control-sm" >
                                <option value="">--Select--</option>
                                <?php
                                    while($row = mysqli_fetch_array($courses)){
                                        echo "<option value = ".$row['code'].">".$row['course_name']."</option>";
                                    }
                                ?>
                            </select>
                        </td>
                    </tr>
                <?php } ?> 
            </tbody>
        </table>
    </div>
</form>
</div>
<div class="txt-heading">Trainee info.</div>
<div class="card-footer">
    <button type="button" class="btn btn-success float-left" id="btnback" name="Back"><i class="fa fa-arrow-left"></i>&nbspBack </button>
    <button type="submit" class="btn btn-primary float-right" id="btnsubmit" name="save"><i class="fa fa-save"></i>&nbspSave to Database </button>
</div>
</div>
</div>

</body>
<script>
                        

$(document).ready(function() {	
	// $('#datainput .form-control').keyup(function() {
	// 	var btnid = $('input.btn').attr('id');
	// 	if($(this).val().length > 0) {
	// 		$('#datainput input.btn').removeClass("disabled");
	// 	} else {
	// 	$('input.btn').addClass("disabled");
	// 	}
	// });

	$('.adm').focus(function(){
		var course = $(this).attr('id').val();
		// var rec_no = $(this).attr('id').val();
		alert(course);
		// if(course == ''){
		// 	alert(rec_no);
		// }
	})
	$('.fname').focus(function(){
		var course = $('#course').val();
		if(course == ''){
			alert('Please select a course first');
		}
	})
	

    $('#btnsubmit').click(function()
    {
        $.ajax({
            url:'Insertdata/insertbatchedtrainees.php',
            method:'post',
            data:$('#form').serialize(),
            
            success:function(data)
            {
                alert(data);
            }
        });
    });

    $('#btnback').click(function(){
        $('#container').load('Modals/batchtrregistration-modal.php');
    });

});
</script>
</html>	