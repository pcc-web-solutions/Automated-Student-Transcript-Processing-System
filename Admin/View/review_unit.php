<?php

require('../../Database/config.php');

	
	$sql="SELECT * 	FROM units order by unit_code ASC, courses_code ASC";
	$results=mysqli_query($conn, $sql);
	
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
<div class="card card-success">
   <div class="card-header">
   <h3 class="card-title">Review units</h3>
   </div>
   <div class="card-body">
<form id=units_form method=post>

<div> <input  type="Text" id="search" class="form-control search" placeholder="Search..." ></div> <br>
		
<div class=table-responsive>
   <div id="unitstable">
       <table class="table table-bordered table-striped text-nowrap" id=unittable>
            <tr class="bg-success">
                <th>SN</th>
                <th>UNIT CODE</th>
                <th>UNIT NAME</th>
                <th>HRS PER LESSON</th>
                <th>LESSONS PER WEEK</th>
                <th>COURSE CODE</th>
                 <th>ACTION</th>
				 </tr>
			<tbody id=records>
            <?php
            $sn = 0;
            while($row = mysqli_fetch_assoc($results)) { ?>
            <tr>
				<td><?php echo ++$sn; ?></td>
                <td contenteditable="true" onfocus="changeBackground(this);" onblur="saveData(this, '<?php echo $row["sn"]; ?>', 'unit_code');"><?php echo strtoupper($row['unit_code']); ?></td>
                <td contenteditable="true" onfocus="changeBackground(this);" onblur="saveData(this, '<?php echo $row["sn"]; ?>', 'unit_name');"><?php echo strtoupper($row['unit_name']); ?></td>
                <td contenteditable="true" onfocus="changeBackground(this);" onblur="saveData(this, '<?php echo $row["sn"]; ?>', 'hourly_lessons');"><?php echo strtoupper($row['hourly_lessons']); ?></td>
                <td contenteditable="true" onfocus="changeBackground(this);" onblur="saveData(this, '<?php echo $row["sn"]; ?>', 'weekly_hours');"><?php echo strtoupper($row['weekly_hours']); ?></td>
                <td contenteditable="true" onfocus="changeBackground(this);" onblur="saveData(this, '<?php echo $row["sn"]; ?>', 'courses_code');"><?php echo strtoupper($row['courses_code']); ?></td>
              <td><a href="#" class="delete" id=<?php echo $row['sn'];?> style="color:red">Delete</a></td>
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
            url: "Edit/change_unit.php",
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
  
  
	$('.delete').click(function()
	{
	var sn=$(this).attr('id');		
	
  	if(confirm('Are you sure you want to delete this record?'))
	{
	  $.ajax({url:'Delete/delete_unit.php',
	  method:'post',
	  data:{sn},
	  
	  	success:function(data)
		{
			alert(data); 
		}
	  });
	 $(this).parents('tr').remove(); 
	
		}
    })
	
  
	})
  
 	
    </script>
</body>
</html>