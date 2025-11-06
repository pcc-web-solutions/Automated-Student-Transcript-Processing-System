<?php

require('../../Database/config.php');
if(isset($_POST['selected_course']) && isset($_POST['selected_class']) && isset($_POST['selected_unit']))
{
	$course_code=$_POST['selected_course'];
	$class=$_POST['selected_class'];	
	$unit=$_POST['selected_unit'];

	//retrieve year from years table
	$sql="SELECT year from years";
	$current_year=mysqli_query($conn, $sql) or die("Unable to retrieve year");
	
	while($row=mysqli_fetch_assoc($current_year))
	{$year=$row['year'];}


	//retrieve term from term table
	$sql="SELECT * from terms";
	$current_term=mysqli_query($conn, $sql) or die("Unable to retrieve term");
	
	while($row=mysqli_fetch_assoc($current_term))
	{$term=$row['term_name'];}

	$results = $conn->query("SELECT trainees.adm, trainees.name, results_entry.sn, results_entry.course_code, classes.class_name, units.unit_name, results_entry.cat, results_entry.exam, results_entry.term, results_entry.exam_year FROM ((trainees inner join results_entry on trainees.adm=results_entry.adm) INNER JOIN classes ON classes.class_name = trainees.class inner join units on units.unit_code=results_entry.unit_code and units.courses_code=results_entry.course_code) where results_entry.course_code='$course_code' AND classes.class_name = '$class' and results_entry.unit_code='$unit' and results_entry.exam_year='$year' and results_entry.term='$term' AND trainees.status= '1'");

	if($results->num_rows<1){
		echo '<div class="alert alert-warning"><strong>Error!</strong> No marks entered</div>';
		exit();
	}

	echo '
	<form id=service_form method=post>	
	<div class=table-responsive >
	   <div id="marks-table">
	       <table class="table-striped text-nowrap" id="loaded_marks">
		        <thead>
		            <tr>
		                <th>SN</th>
		                <th>ADM NO</th>
		                <th>NAME</th>
		                <th>COURSE</th>
		                <th>CLASS</th>
		                <th>UNIT</th>
		                <th>CAT</th>
		                <th>EXAM</th>
		                <th>TERM</th>
		                <th>ACTION</th>
					</tr>
				</thead>	
				<tbody id="marks-table"';
	            if($results->num_rows>0){
					$count=1;
		            while($row = mysqli_fetch_assoc($results)) { 
						echo '<tr>
					
						<td>'.$count.'</td>
						<td>'.strtoupper($row['adm']).'</td>
						<td>'.strtoupper($row['name']).'</td>
						<td>'.$row['course_code'].'</td>
						<td>'.$row['class_name'].'</td>
						<td>'.strtoupper($row['unit_name']).'</td>
						<td contentEditable="true" class="cats" id="'.$row['sn'].'">'.$row['cat'].'</td>
						<td contentEditable="true" class="exams" id="'.$row['sn'].'">'.$row['exam'].'</td>
						<td>'.$row['term'].'</td>
						
						<td><a href="#" class="delete" id='.$row['sn'].' style="color:red">Delete</a></td>
					
		             	</tr>'; 
						$count++; 
					} 
				}
				else{
					echo '<tr><td colspan=9><div class="alert alert-warning"><strong>Error!</strong> No marks entered</div></td></tr>';
				}
			} 
else {
	echo '<div class="alert alert-warning"><strong>Error!</strong> No units assigned to the selected course</div>';} ?>
				
				</tbody>
        	</table>
		</div>
	</div>
	</form>
  
	<script src="js/jquery-3.3.1.js"></script>
	<script src="../../jquery-ui.min.js"></script>
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
	var sn=$(this).attr('id');		
	
  	if(confirm('Are you sure you want to delete this record?'))
	{
	  $.ajax({url:'Delete/delete_marks.php',
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

<script>	
	$(document).ready(function()
	{
		$(".cats").blur(function(){
		var id = this.id;
		var value = $(this).text();
		
	
		$.ajax({
		url: 'Update/update_cats.php',
		type: 'post',
		data: {value:value, id:id },
		success:function(response){
		console.log(response); 
		}
		})
		  
		})
	})
</script>

<script>	
	$(document).ready(function()
	{
		$(".exams").blur(function(){
		var id = this.id;
		var value = $(this).text();
		
	
		$.ajax({
		url: 'Update/update_exams.php',
		type: 'post',
		data: {value:value, id:id },
		success:function(response){
		console.log(response); 
		}
		})
		  
		})
	})
</script>

<style>
	#load-marks{
		width: 100%;
	}
	table{
		width: 100%;
		border-collapse: collapse;
	}
	thead tr th{
		background-color: cyan;
		border: 1px solid grey;
	}
	tbody tr td{
		border: 1px solid grey;
	}
	tbody tr:nth-child(even){
		background-color: white;
	}
</style>