<?php
session_start();
if (!$_SESSION['dept']) {
    header("location: ../login-page.php?error=Department not ready!");
    exit();
}
$dept = $_SESSION['dept'];
require('../../Database/config.php');

	
	$sql="SELECT * FROM trainees INNER JOIN courses ON courses.code = trainees.course_code INNER JOIN departments ON departments.department_code = courses.department_code WHERE departments.department_code = '$dept' ORDER BY status DESC, course_code ASC, class DESC, adm ASC";

	// $sql="select * from collections";
	$results=mysqli_query($conn, $sql) or die("Problem running query");
	
?>

<!DOCTYPE html>
<html>
<head>
    
		
<style>

.search:focus {
	width: 100%;
}
.search {
	width: 150px;
	transition: width 0.4s ease-in-out;
	margin-bottom:0px; margin-top: 2px;
}

td, th{padding:3px; text-align:left}


button:hover{ opacity: 0.9;
}

button{width:50%}


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
	

</style>
<script>

</script>

</head>
<body>
<div class="card card-info">
   <div class="card-header">
   <h3 class="card-title">Review trainee</h3>
   </div>
   <div class="card-body">
<form id=service_form method=post>

<div class="row"> <input  type="Text" id="search" class="form-control search" placeholder="Search..." ></div> <br>
		
<div class=table-responsive>
   <div id="mytable">
       <table class="table table-bordered table-striped text-nowrap" id=mytable>
			<thead>
				<tr class="bg-info">
	                <th>SN</th>
	                <th>ACTIVE</th>
	                <th>ADM NO</th>
	                <th>NAME</th>
	                <th>GENDER</th>
	                <th>COURSE</th>
	                <th>CLASS</th>
					<th>ACTION</th>
				</tr>
        </thead>
			<tbody id=records>
            <?php
			$count=1;
            while($row = mysqli_fetch_assoc($results)) { 
            	$status = $row['status']; 
            	if ($status == '1'){
            		$state = 'checked';
            	}else{
            		$state = "";
            	}
            ?>
            <tr>
				<td><?php echo $count; ?></td>
				<td><input type="checkbox" name="status" id=<?php echo strtoupper($row['adm']); ?> value=<?php echo strtoupper($row['status']); ?>  <?php echo $state; ?> ></td>
                <td contenteditable="false" onfocus="changeBackground(this);" onblur="saveData(this, '<?php echo $row["sn"]; ?>', 'adm');"><?php echo strtoupper($row['adm']); ?></td>
                <td contenteditable="false" onfocus="changeBackground(this);" onblur="saveData(this, '<?php echo $row["sn"]; ?>', 'name');"><?php echo strtoupper($row['name']); ?></td>
                <td contenteditable="false" onfocus="changeBackground(this);" onblur="saveData(this, '<?php echo $row["sn"]; ?>', 'gender');"><?php echo strtoupper($row['gender']); ?></td>
				<td contenteditable="false" onfocus="changeBackground(this);" onblur="saveData(this, '<?php echo $row["sn"]; ?>', 'course_code');"><?php echo strtoupper($row['course_code']); ?></td>
				<td contenteditable="false" onfocus="changeBackground(this);" onblur="saveData(this, '<?php echo $row["sn"]; ?>', 'class');"><?php echo strtoupper($row['class']); ?></td>
                <td><a href="#" class="delete" id=<?php echo $row['sn'];?> style="color:red">Delete</a></td>
			</tr> 
            <?php $count++; } ?>
				
				</tbody>
        </table>
    </div>
	</div>
	
	</form>
	</div>
	</div>
<script src="js/jquery-3.3.1.js"></script>
<script src="jquery-ui.min.js"></script>
<script type="text/javascript">	</script>
<script type="text/javascript" src="script/functions.js"></script>


<script type="text/javascript">
	$(document).ready(function(){
		$('input[name="status"]').change(function(){
			var adm = $(this).attr('id');
			var current_status = $(this).attr('value');
			
			$.ajax({
				url: 'Update/update_trainee_status.php',
				method: 'post',
				data: {adm:adm,current_status:current_status},
				success: function(data){
					if(data != 'success'){
						toastr.error(data);
					}
				},
			})
		})
		
	})
</script>


<script>  
function changeBackground(obj) {
        $(obj).removeClass("bg-success");
        $(obj).addClass("bg-info");
    }

    function saveData(obj, sn, column) {
        var customer = {
            sn: sn,
            column: column,
            value: obj.innerHTML
        }
        $.ajax({
            type: "POST",
            url: "Edit/change_trainee.php",
            data: customer,
            dataType: 'json',
            success: function(data){
                if (data) {
                    $(obj).removeClass("bg-danger");
                    $(obj).addClass("bg-success");
                }
            }
       });
    }
</script>

<script>
	
	$(document).ready(function()
	{
		$('[contenteditable]').keypress(function(e){ return e.which != 13; });
	
	  $("#search").keyup(function() {
		var value = $(this).val().toLowerCase();
		$("#records tr").filter(function() {
		  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		})
	  })
  
  		
		$('.delete').click(function(e)
		{
		e.preventDefault();
		// var sn=$(this).attr('id');		
		
		// if(confirm('Are you sure you want to delete this record?'))
		// {
		//   $.ajax({url:'Delete/delete_trainee.php',
		//   method:'post',
		//   data:{sn},
		  
		// 	success:function(data)
		// 	{
		// 		alert(data); 
		// 	}
		//   });
		//  $(this).parents('tr').remove(); 
		
		// 	}
        toastr.error("You have no permission to perform this action!")
		})
	
  
	})
  
 	
    </script>
</body>
</html>