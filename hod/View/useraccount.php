<?php
session_start();

if(!$_SESSION["hod"])
{
    header('location: ../../login-page.php');		
}
	
include "../../Database/config.php";

//Logged in user session
$loggedin = $_SESSION["hod"];
$sql = "SELECT * FROM users WHERE user_id = '$loggedin'";
$run = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($run)){
    $fname = $row['FirstName'];
    $lname = $row['LastName'];
    $phone = $row['Phone_No'];
    $uname = $row['username'];	
    $password = $row['password'];
    $usertype = $row['usertype'];
    $regdate = $row['Date_Registered'];
    $attempts = $row['Attempts'];
    if( $attempts <= 0){$accstatus = "Inactive";}else{$accstatus = "Active";}
}

if(isset($_POST['updatebtn'])){

}
?>

<html>
    <head>
        <title></title>
    </head>
    <body>
        <form action="#" id="useraccountform" class="form">
            <div class="card card-info">
                <div class="card-header">
                    <h5 class="card-title">User Profile</h5>
                </div>
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-mb-6" style="margin-left: 15px; margin-right: 15px;">
                            <div class="row">
                                <div class="col-sm-5">
                                    <label for="fname" >First Name: </label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control form-control-sm" name="fname" value = <?php echo $fname; ?> readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-5">
                                    <label for="lname" >Last Name: </label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control form-control-sm " name="lname" value = <?php echo $lname; ?> readonly></input>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-5">
                                    <label for="phone" >Phone number:</label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="number" class="form-control form-control-sm" name="phone" value = <?php echo $phone; ?> readonly></input>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-5">
                                    <label for="regdate" >Date Registered: </label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control form-control-sm" name="regdate" value = <?php echo $regdate; ?> readonly></input>
                                </div>
                                
                            </div>
                        </div>
                        <div class="col-mb-6" style="margin-left: 15px; margin-right: 15px;">
                            <div class="row">
                                <div class="col-sm-5">
                                    <label for="acctype">Account Type:</label> 
                                </div>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control form-control-sm" name="usertype" value = <?php echo "'$usertype'"; ?> readonly></input>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-5">
                                    <label for="pswd">Password: </label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="password" class="form-control form-control-sm" name="opassword" value = <?php echo "$password"; ?> readonly></input>
                                </div>
                            </div> 
                            <div class="row">
                                <div class="col-sm-5">
                                    <label for="uname">Username: </label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control form-control-sm" name="username" value = <?php echo $uname; ?> >
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-5">
                                    <label for="pswd">New Password: </label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="password" class="form-control form-control-sm" name="npassword" ></input>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <input type="text" name="click" value="update" style="display: none;">
                </div>
                <div class="card-footer ">
                    <button type="submit" name="updatebutton" class="btn btn-secondary float-right" id="updatebtn"><i class="fa fa-key"></i> Update Details</button>
                    <button type="submit" name="logoutbtn" class="btn btn-success float-left" id="logoutbtn"><i class="fa fa-sign-out"></i> Logout here</button>
                </div>
            </div>  
        </form>
    </body>
    <style>
        button:hover{
            cursor: pointer;
        }
        .card{
            width:fit-content;
            margin: auto;
        }
        div.row{
            margin-bottom: 10px;
        }
    </style>
</html>
<script>
    $(document).ready(function(){

        $('#updatebtn').click(function(event){
            event.preventDefault();
            $.ajax({
                url: 'Update/mydetails.php',
                method: 'post',
                data: $('#useraccountform').serialize(),

                success:function(data)
                {
                    if(data == 'success'){toastr.success("Profile updated successfully")}
                    else{toastr.error(data)}
                }
            });
        });

        $('#logoutbtn').click(function(event){
            event.preventDefault();

            if(confirm('Are you sure you want to logout?')){
                $.ajax({
                    success:function()
                    {
                        location.replace('logout.php');   
                    }
                });
            }
        });
    });
</script>