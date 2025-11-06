<?php
	
	session_start();

	$trainer_id = $_SESSION['Trainer'];

	include('../../Database/config.php');

	$sql="SELECT * 	FROM courses INNER JOIN trainer_units ON trainer_units.course_code = courses.code WHERE trainer_units.trainer_id = '$trainer_id' GROUP BY courses.code ORDER BY code";
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
 margin-bottom:0px
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
   <h3 class="card-title">Review courses</h3>
   </div>
   <div class="card-body">
<form id=service_form method=post>

<div> <input  type="Text" id="search" class="form-control search" placeholder="Search..." ></div> <br>
		
<div class=table-responsive>
   <div id="mytable">
       <table class="table table-bordered table-striped text-nowrap" id=mytable>
            <tr class="bg-info">
                <th>SN</th>
                <th>COURSE CODE</th>
                <th>COURSE NAME</th>
                <th>ABREVIATION</th>
                <th>DEPARTMENT</th>
                <!-- <th>ACTION</th> -->
				</tr>
			<tbody id=records>
            <?php
			$count = 0;
            while($row = mysqli_fetch_assoc($results)) { ?>
            <tr>
			
				<td><?php echo ++$count; ?></td>
                <td contenteditable="false" onfocus="changeBackground(this);" onblur="saveData(this, '<?php echo $row["code"]; ?>', 'code');"><?php echo strtoupper($row['code']); ?></td>
                <td contenteditable="false" onfocus="changeBackground(this);" onblur="saveData(this, '<?php echo $row["code"]; ?>', 'course_name');"><?php echo strtoupper($row['course_name']); ?></td>
                <td contenteditable="false" onfocus="changeBackground(this);" onblur="saveData(this, '<?php echo $row["code"]; ?>', 'code');"><?php echo strtoupper($row['course_abrev']); ?></td>
                <td contenteditable="false" onfocus="changeBackground(this);" onblur="saveData(this, '<?php echo $row["code"]; ?>', 'department_code');"><?php echo strtoupper($row['department_code']); ?></td>
			<!--	<td><a href="#" class="delete" id=<?php echo $row['code'];?> style="color:red">Delete</a></td> -->
			</tr> 
            <?php } ?>
				
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
            url: "Edit/change_course.php",
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
	
  $("#search").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#records tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    })
  })
  
  
	$('.delete').click(function(e)
	{
		e.preventDefault();
		toastr.error("You do not have permission to delete this course.");
    })
	
  
	})
  
 	
    </script>
</body>
</html>