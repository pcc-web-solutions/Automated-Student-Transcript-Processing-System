<?php
session_start();
$loggedin = $_SESSION["Admin"];
include('../../Database/config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../Libraries/dist/css/adminlte.min.css">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body>
    <div class="card card-success">
        <div class="card-header">
            <h5 class="card-title">Manage Users</h5>
        </div>
        <div class="card-body table-responsive p-0">
        <div class="modal fade" id="modal-default">
          <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                  <h6 class="modal-title" style="padding-top: 0px; padding-bottom: 0px;">New User</h6>
                  <button type="button" style="color: red;" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    To add new users, kindly follow the following steps
                    <ol type="i">
                        <li><strong>Trainers: </strong>Locate and Expand the <i>Registration</i> menu, Expand on <i>Trainers</i>, Locate <i>Add Trainer</i>, input the details and click <i>Submit</i>. The trainer will automatically be added as a trainer user.</li>
                        <!-- <li><strong>H.O.D User: </strong>Go to the <i>Departments</i> Menu, Then <i>Add Department</i>, supply details to the form and click <i>Save</i>. The trainer will automatically be added as a H.O.D user.</li> -->
                    </ol>
                    <strong>Note: </strong>Users can only be editted or deleted.
                </div>
            </div>
            <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
            <?php            
            $sql="SELECT * FROM users WHERE user_id != '$loggedin' AND usertype != 'Admin'"; 	
            $results=mysqli_query($conn, $sql) or die("Problem fetching users from database");
            if(mysqli_num_rows($results)>0)
            {?>
                <div class="row" style="margin:10px 2px 5px 2px; margin-bottom: 0px;">
                    <div class="col-lg-9 col-md-9 col-sm-9">
                        <input  type="Text" id="search" class=" form-control search" placeholder="Start typing..." >
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3">
                        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#modal-default"><i class="fa fa-plus"></i>&nbsp Add New</button>
                    </div>
                </div> 
                <table class="table table-head-fixed table-striped text-nowrap">
                    <thead>
                        <tr>
                            <th>SN</th>
							<th>Name</th>
							<th>Contact</th>
							<th>Usertype</th>
							<th>username</th>
							<th>Password</th>
							<th>Acount Status</th>
							<th colspan=3><center>Actions</center></th>
                        </tr>
                    </thead>
                    <tbody id=records>
                        <?php 
						$sn=0; while ($row=mysqli_fetch_assoc($results)){ 
						$count = $row['Attempts'];
                        $user = $row['user_id'];
                        $checkusertype = strtolower($row['usertype']);
						if($count<=0){$status="Deactivated"; $action = "lock";}else{$status="Activated"; $action = "lock-open";}
						?>
                        <tr style="font-size: 12px;">
                            <td><?php echo ++$sn; ?></td>
                            <td><?php echo $row['FirstName']." ".$row['LastName']; ?></td>
                            <td><?php echo $row['Phone_No']; ?></td>
							<td><?php echo $row['usertype']; ?></td>
							<td><?php echo $row['username']; ?></td>
							<td><?php echo $row['password']; ?></td>
							<td><?php echo $status; ?></td>
                            <td><a href="#" class="edit" id=<?php echo $row['user_id']; ?> style="color:blue"><i class="fa fa-edit fa-lg"></i></a></td>
                            <td><a href="#" class="block" id=<?php echo $row['user_id']; ?> style="color:grey"><i class="fas fa-<?php echo $action; ?> fa-lg"></i></a></td>
                            <td><?php if($user == $loggedin){echo '';}else{echo '<a href="#" class="delete" id= '.$row['user_id'].' style="color:red" ><i class="fa fa-trash fa-lg"></i></a>';} ?></td>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
                <?php } 
                else{?>
                <table class="table table-head-fixed table-striped text-nowrap">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Usertype</th>
                            <th>username</th>
                            <th>Password</th>
                            <th>Acount Status</th>
                        </tr>
                    </thead>
                    <tbody id=records>
                        <tr style="font-size: 14px;">
                            <td colspan="7" ><center><b>No registered users available</b></center></td>
                        </tr>
                    </tbody>
                </table>    
                <?php } ?>    
        </div>
    </div> 
<style>
  .row{
    margin-bottom: 10px;
  }
</style>
    <!-- jQuery -->
    <script src="../../Assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../../Assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="../../Assets/plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- Toastr -->
    <script src="../../Assets/plugins/toastr/toastr.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../../Assets/dist/js/adminlte.min.js"></script>
</body>

<style>
    .card{
        Width: 100%;
    }
    button:hover{
        cursor: pointer;
    }
    table{
        width: fit-content;
    }
    .search {
    width: 200px;
    transition: width 0.4s ease-in-out;
    margin-bottom:10px
    }
    .search:focus {
      width: 100%;
    }
</style>

</html>
<script>
$(document).ready(function () {

	$('#submitbtn').click(function(event){
	  event.preventDefault();

	  $.ajax({
		  url: 'Insertdata/insertuser.php',
		  method: 'POST',
		  data: $('#modalform').serialize(),

		  success:function(data)
		  {
			  alert(data);
		  }
	  });
	});

	$("#search").on("keyup", function() {
	var value = $(this).val().toLowerCase();
	$("#records tr").filter(function() {
	  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
	})
	});
  
  	
	$('.delete').click(function()
	{	
  	var record_no=$(this).attr('id');
	
	if(confirm('Are you sure you want to remove this user?'))
	{
	  $.ajax({url:'Delete/deleteuser.php',
	  method:'post',
	  data:{record_no},
	  
	  	success:function(data)
		{
			alert(data);
			
		}
	  });
	$(this).parents('tr').remove(); 
	}
    })

	$('.edit').click(function()
	  {	
		var record_no=$(this).attr('id');
		  $.ajax({url:'Edit/edituser.php',
		  method:'post',
		  data:{record_no},
		  
			success:function(data)
			{
				$("#container").html(data);
				
			}
	  })
		 
	});
	
	$('.block').click(function()
	{
  	var record_no=$(this).attr('id');
      $("#container").load('View/manageusers.php');
	if(confirm('Are you sure you want to perform this action?'))
	{
	  $.ajax({url:'Update/blockuser.php',
	  method:'post',
	  data:{record_no},
	  
	  	success:function(data)
		{
			alert(data);
			$("#container").load('View/manageusers.php');
		}
	  });
	}
    });

});
</script>