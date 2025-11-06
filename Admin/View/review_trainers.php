<?php
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
	$sql="SELECT * FROM trainers LEFT JOIN departments ON trainers.department_id = departments.department_code ORDER BY trainer_id ASC"; 	
	$results=mysqli_query($conn, $sql) or die("Problem fetching users from database") ;
	if(mysqli_num_rows($results)>0)
	{?>
   <div class="card card-info">
   <div class="card-header">
   <h3 class="card-title">Review trainers</h3>
   </div>
   <div class="card-body ">
  
<div class=cont>
<div> <input  type="Text" id="search" class="form-control search" placeholder="Search..." ></div> <br>

<div class=table-responsive>
   <div id="mytable">
       <table class="table table-bordered table-striped text-nowrap" id=mytable>
		    <tr class="bg-info">
                            <th>SN</th>
                            <th>Identity Code</th>
                            <th>Full Name</th>
                            <th>Contact</th>
                            <th>Department</th>
                            <th colspan=2>Actions</th>
                        </tr>
                    </thead>
                    <tbody id=records>
                        <?php $sn=0; while ($row=mysqli_fetch_assoc($results)){ ?>
                        <tr style="font-size: 12px;">
                            <td><?php echo ++$sn; ?></td>
                            <td><?php echo $row['trainer_id']; ?></td>
                            <td><?php echo strtoupper($row['first_name'])." ".strtoupper($row['last_name']); ?></td>
                            <td><?php echo strtoupper($row['phone_no']); ?></td>
                            <td><?php echo strtoupper($row['department_name']); ?></td>
                            <td><a href="#" class="edit" id=<?php echo $row['trainer_id']; ?> style="color:blue"><i class="fa fa-edit fa-lg"></i></a></td>
                            <td><a href="#" class="delete" id=<?php echo $row['trainer_id']; ?> style="color:red"><i class="fa fa-trash fa-lg"></i></a></td>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
                <?php } else{?>
                
                    <table class="table table-head-fixed text-nowrap">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Identity Code</th>
                            <th>Full Name</th>
                            <th>Contact</th>
                            <th>Department</th>
                            <th colspan=2>Actions</th>
                        </tr>
                    </thead>
                    <tbody id=records>
                        <tr>
							<td colspan=6 style="text-align: center;"><?php echo '<h6>There are no registered trainers<h6>'; ?></td>
                        </tr>
                    </tbody>
                </table>

                <?php } ?>    
        </div>
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


  $("#search").keyup(function() {
    var value = $(this).val().toLowerCase();
    $("#records tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    })
  })
	
	
	$('.delete').click(function(event)
	{	
	event.preventDefault();
  	var record_no=$(this).attr('id');
	
	if(confirm('Are you sure you want to remove this teacher?'))
	{
	  $.ajax({url:'Delete/delete_trainer.php',
	  method:'post',
	  data:{record_no},
	  
	  	success:function(data)
		{
			alert(data);
			$('.container').load('View/review_trainers.php');
		}
		
	  })
	  $(this).parents('tr').remove(); 
	}
	  
	})
	
	
	$('.edit').click(function()
	  {	
		
		var record_no=$(this).attr('id');
		
		$.ajax({url:'Edit/select_trainer.php',
		method:'post',
		data:{record_no},
	  
	  	success:function(data)
		{
			if(data == 'Error'){
				alert('Problem selecting the trainer identity number.');
			}
			else{
				$(".cont").html(data);
			}
		}
		
	  })
			
	  });
	
  });
  
	
	


</script>