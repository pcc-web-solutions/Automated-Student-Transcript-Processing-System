<?php
include("../Database/config.php");

if (isset($_POST['btnNewUser'])){
  
  function validate($data){
        $data=trim($data);
        $data=stripslashes($data);
        $data=htmlspecialchars($data);
        return $data;
    }
  
  $date = date("Y-m-d");
  
  //Count number of members and inreement by 1
  $sql = "SELECT COUNT(DISTINCT user_id) AS totalusers FROM users";
  $run = mysqli_query($conn, $sql);
  while($row=mysqli_fetch_assoc($run)){
    $totalnumber = $row['totalusers']+1;
  }
  
  $userid = "User".sprintf('%03d',$totalnumber);
  $fname = validate($_POST['fname']);
  $lname = validate($_POST['lname']);
  $number = validate($_POST['phone']);
  $username = validate($_POST['username']);
  $password = validate($_POST['password']);
  $usertype = "Admin";
  
  //Confirm if the user is already registered
  $sql = "SELECT COUNT(DISTINCT username) AS availableusers FROM users WHERE username = '$username' ";
  $run = mysqli_query($conn, $sql);
  while($row=mysqli_fetch_assoc($run)){
    $usersavailable = $row['availableusers'];
  }
  if($usersavailable >= 1){
    header ("location: signup.php?error=There already exists a user with the email address supplied. Please try another username");
  }
  else{
    
    //Insert data to the users table
    $insert = "INSERT INTO users (user_id,FirstName,LastName,Phone_No,username,password,Date_Registered,Attempts) 
    VALUES ('$userid','$fname','$lname','$number','$username','$password','$date','3')";
    $runsql = mysqli_query($conn,$insert);
    
    //Checking if data is submitted successfully or not
    if(!$runsql){ 
      header ("location: signup.php?error=Problem submitting information");
    }
    else{
      header ("location: ../index.php?error=Account created successfully");
    }
  } 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SignUp Form</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>
<body class="login-page" style="background-image: url(../images/home-img.jpg); background-repeat: no-repeat; background-size: cover;">
<div class="wrapper" >
  <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- jquery validation -->
            <div class="card card-primary">
              <div class="card-header">
                <center><h4><center>First-Time Login</h4></center>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form id="firstForm" action="signup.php" method="POST">
                <div class="card-body">
                  <?php if (isset($_GET['error'])) { ?>
            <p class="error" style="color: red; text-align: center;"><?php echo $_GET['error']; ?></p>
          <?php }?>
          
          <div class="form-group">
                    <label for="exampleInputEmail1">First Name:</label>
                    <input type="text" name="fname" class="form-control" placeholder="e.g Abiud">
                  </div>

                  <div class="form-group">
                    <label for="exampleInputPassword1">Last Name:</label>
                    <input type="text" name="lname" class="form-control" placeholder="e.g Musee">
                  </div>


          <div class="form-group">
                    <label for="exampleInputEmail1">Username:</label>
                    <input type="email" name="username" class="form-control" placeholder="Enter email address">
                  </div>

                  <div class="form-group">
                    <label for="exampleInputPassword1">Password:</label>
                    <input type="password" name="password" class="form-control" placeholder="Password">
                  </div>



          <div class="form-group">
          <label for="exampleInputEmail1">Phone Number:</label>
          <input type="number" name="phone" class="form-control" placeholder="Enter phone number">
          </div>

          <br>

          <div class="form-group mb-0">
                    <div class="custom-control">
                      <p style="color: green;">Already have an account? <a href="signin.php">Sign In</a></p>
                    </div>
                  </div>

                </div>
        
        <div class="card-footer">
                  <button type="submit" class="btn btn-primary" name="btnNewUser">Register Now</button>
                </div>
        
              </form>
            </div>
            <!-- /.card -->
            </div>
          <!--/.col (left) -->
          <!-- right column -->
          <div class="col-md-6">

          </div>
          <!--/.col (right) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- jquery-validation -->
<script src="../plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="../plugins/jquery-validation/additional-methods.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>

<style>
.login-page{
  padding-top: 70px;
  padding-left: 10px;
  padding-right: 10px;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    flex-direction: column;
}
</style>
<script>
$(function () {
  $.validator.setDefaults({
    
  });
  $('#firstForm').validate({
    rules: {
      fname: {
        required: true,
        maxlength: 20
      },
    lname: {
        required: true,
        maxlength: 20
      },
    username: {
        required: true,
        email: true
      },
      password: {
        required: true,
        minlength: 6
      },
    confirm: {
        required: true,
        minlength: 5,
      },
    phone: {
        required: true,
        minlength: 10
      },
      terms: {
        required: true
      },
    },
    messages: {
      fname: {
        required: "Please provide a your first name",
        maxlength: "Ätmost a length of 20 characters"
      },
    lname: {
        required: "Please provide a your last name",
        maxlength: "Ätmost a length of 20 characters"
      },
    username: {
        required: "Please enter a email address",
        email: "Please enter a valid email address"
      },
      password: {
        required: "Please provide a password",
        minlength: "Your password must be at least 6 characters long"
      },
    phone: {
        required: "Phone number is required",
        minlength: "Input length of 10 characters"
      },
      terms: "Please accept our terms"
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
    },
    locked: function (element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
  },
  unlocked: function (element, errorClass, validClass) {
      $(element).addClass('is-invalid');
  },
  highlight: function (element, errorClass, validClass) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
    }
  });
});
</script>
</body>
</html>