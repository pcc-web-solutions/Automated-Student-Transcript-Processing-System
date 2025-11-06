<?php
include("../../Database/config.php");

if(isset($_POST['record_no'])){
	$trid = $_POST['record_no'];
	
	$trainer = $conn->query("SELECT * FROM trainers INNER JOIN departments ON trainers.department_id = departments.department_code WHERE tariners.trainer_id = '$trid'");
	while($row=mysqli_fetch_assoc($trainer)){
		$trname = $row['trainer_name'];
		$trphone = $row['phone_no'];
		$deptid = $row['department_code'];
		$deptname = $row['department_name'];
	}
}
?>
<html>
    <head>
        <title></title>
    </head>
    <body>
        <form action="#" id="form" class="form">
            <div class="card card-info">
                <div class="card-header">
                    <div class="card-title text-center"><h5>Add Trainer</h5></div>
                </div>
                <div class="card-body">
					<div class="row">
                        <div class="col-md-5">
                            <h6>Identity Number:</h6>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control form-control-sm" name="tr_name" value = <?php echo $trid ; ?> readonly >
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <h6>Full Name:</h6>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control form-control-sm" name="tr_name" value = <?php echo "$trname" ; ?> >
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <h6>Phone Number:</h6>
                        </div>
                        <div class="col-md-7">
                            <input type="number" class="form-control form-control-sm" name="phone" value = <?php echo "$trphone" ; ?> >
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <h6>Department Name:</h6>
                        </div>
                        <div class="col-md-7">
                            <select class="form-control form-control-sm" name=department>
                                <option value="">--Select--</option>
                                <?php 
                                    $sql = "SELECT * FROM departments";
                                    $run = mysqli_query($conn, $sql);
                                    while($result=mysqli_fetch_assoc($run)) {
                                        echo "<option value=".$result['department_code'].">".$result['department_name']."</option>";
                                    } 
                                ?>
                            </select>
                        </div>
                    </div>
                    <br>
                </div>
                <div class="card-footer ">
                    <button type="submit" name="logoutbtn" class="btn btn-primary float-right" id="submitbtn">Update</button>
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

        $('#submitbtn').click(function(event){
            event.preventDefault();
  
            $.ajax({
                url: 'Update/update_trainer.php',
                method: 'post',
                data: $('#form').serialize(),

                success:function(data)
                {
                    alert(data);
                }
            });
        });
    });
</script>