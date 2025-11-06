<?php
session_start();
$trainer_id = $_SESSION['Trainer'];
include('../../Database/config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Theme style -->
	<link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body>
    

            <?php            
            $classes=$conn->query("SELECT * FROM classes INNER JOIN intakes ON classes.intake = intakes.int_abrev INNER JOIN trainer_units ON trainer_units.class_name = classes.class_name INNER JOIN courses ON courses.code = trainer_units.course_code WHERE trainer_units.trainer_id = '$trainer_id' GROUP BY classes.class_name ORDER BY classes.class_name ASC"); 	
            if(mysqli_num_rows($classes)>0)
            {?>
   <div class="card card-info">
   <div class="card-header">
   <h3 class="card-title">Review Classes</h3>
   </div>
   <div class="card-body ">
  
<div class=cont>
<div> <input  type="Text" id="search" class="form-control search" placeholder="Search..." ></div> <br>

<div class=table-responsive>
       <table class="table table-bordered table-striped text-nowrap" id=mytable>
		    <tr class="bg-info">
                            <th>SN</th>
                            <th>Course Code</th>
                            <th>Course Name</th>
                            <th>Class ID</th>
                            <th>Class</th>
                            <!-- <th>Actions</th> -->
                        </tr>
                    </thead>
                    <tbody id=records>
                        <?php $sn=0; while ($row=mysqli_fetch_assoc($classes)){ ?>
                        <tr style="font-size: 12px;">
                            <td><?php echo ++$sn; ?></td>
                            <td><?php echo strtoupper($row['code']); ?></td>
                            <td><?php echo strtoupper($row['course_name']); ?></td>
                            <td><?php echo $row['class_id']; ?></td>
                            <td><?php echo strtoupper($row['class_name']); ?></td>
                            <!-- <td><a href="#" class="delete" id=<?php echo $row['class_id']; ?> style="color:red"><strong>Delete</strong></a></td> -->
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
                <?php } else{?>
                
                    <table class="table table-head-fixed text-nowrap">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Course Code</th>
                            <th>Course Name</th>
                            <th>Class ID</th>
                            <th>Class</th>
                        </tr>
                    </thead>
                    <tbody id=records>
                        <tr>
                        <td colspan=5 style="text-align: center;"><?php echo '<h6>There are no registered classes<h6>'; ?></td>
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
        width: 100%;
    }
    button:hover{
        cursor: pointer;
    }
    .search {
    width: 200px;
    transition: width 0.4s ease-in-out;
    margin-bottom:10px
    }
	
	tr{
	line-height:8px;
	min-height:8px;
	height:8px;
	
	}
	tr th{
	line-height:8px;
	min-height:8px;
	height:8px;
	
	}
	
	.search:focus {
        width: 100%;
    }
.search {
 width: 150px;
 transition: width 0.4s ease-in-out;
 margin-bottom:0px
  }
	
</style>

</html>
<script>
$(document).ready(function () {


  $("#search").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#records tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    })
  })
	
	
	$('.delete').click(function(event)
	{	
	event.preventDefault();
  	var record_no=$(this).attr('id');
	
	alert('Kindly liars with the exam officer to drop this class for you.');
	  
	})
	
	
  });
  
	
	


</script>