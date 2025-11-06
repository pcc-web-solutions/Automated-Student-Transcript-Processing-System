<?php
include("../../Database/config.php");

if(isset($_POST['record_no']))
	{
		$record_no=$_POST['record_no'];
		
		$sql="SELECT * FROM users where user_id='$record_no'";
		$results=mysqli_query($conn, $sql) or die("Unable to load data");
		
		while($row=mysqli_fetch_assoc($results))
		{
			$id = $row['user_id'];
			$fname = $row['FirstName'];
			$lname = $row['LastName'];
			$phone = $row['Phone_No'];
			$username = $row['username'];
			$password = $row['password'];
			$usertype = $row['usertype'];
		}	
	}	
?>

<!DOCTYPE html>
<html lang="en">
<head>
    
</head>

<body>
<div class="card card-info">
    <div class="card-header" >Edit User</div>
		<div class="card-body">
			<form id=form method=post action=#>
				<div class="row">
					<div class="col-md-5">
						<h6>Identity:</h6>
					</div>
					<div class="col-md-7">
						<input type="text" class="form-control form-control-sm" name="id" value = <?php echo "'$id'" ; ?> readonly>
					</div>
				</div>    
				<div class="row">
					<div class="col-md-5">
						<h6>First Name:</h6>
					</div>
					<div class="col-md-7">
						<input type="text" class="form-control form-control-sm" name="fname" value = <?php echo "'$fname'" ; ?> readonly>
					</div>
				</div>
				<div class="row">
					<div class="col-md-5">
						<h6>Last Name:</h6>
					</div>
					<div class="col-md-7">
						<input type="text" class="form-control form-control-sm" name="lname" value = <?php echo "'$lname'" ; ?>  readonly>
					</div>
				</div>
				<div class="row">
					<div class="col-md-5">
						<h6>Phone Number:</h6>
					</div>
					<div class="col-md-7">
						<input type="number" class="form-control form-control-sm" name="phone" value = <?php echo $phone; ?>  readonly>
					</div>
				</div>
				<div class="row">
					<div class="col-md-5">
						<h6>Usertype:</h6>
					</div>
					<div class="col-md-7">
						<input type="text" class="form-control form-control-sm" name="usertype" value = <?php echo "'$usertype'"; ?> readonly >
					</div>
				</div>
				<div class="row">
					<div class="col-md-5">
						<h6>Username:</h6>
					</div>
					<div class="col-md-7">
						<input type="text" class="form-control form-control-sm" name="username" value = <?php echo "'$username'"; ?>  >
					</div>
				</div>
				<div class="row">
					<div class="col-md-5">
						<h6>Password:</h6>
					</div>
					<div class="col-md-7">
						<input type="password" class="form-control form-control-sm" name="password" value = <?php echo "'$password'"; ?> >
					</div>
				</div>
			</form>
		</div>
		<div class="card-footer ">
			<button type="button" name="submit" class="btn btn-danger" id="cancel"><i class="fa fa-times"></i>&nbspClose </button>
			<button type="submit" name="logoutbtn" class="btn btn-success float-right" id="updatebtn"><i class="fa fa-refresh"></i>&nbspUpdate </button>
		</div>
	</div>
</div>
<style>
        button:hover{cursor: pointer;}
        .card{
            width:fit-content;
            margin: auto;
        }
        .card-body{margin: 5px;}
        div.row{margin-top: 10px;}
        h6{
            font-size: 13px;
            font-weight: bold;
            padding-top: 5px;
        }
    </style>
</body>
<script>
	$(document).ready(function(){
		$('#updatebtn').click(function(event){
			event.preventDefault();

			$.ajax({
				url: 'Update/updateuser.php',
				method: 'post',
				data: $('#form').serialize(),

				success:function(data)
				{
					alert(data);
				}
			});
		});

		$('#cancel').click(function(){
			$('#container').load('View/manageusers.php');
		});
	});
</script>
</html>