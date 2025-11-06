<?php

require('../../Database/config.php');
if(isset($_POST['selected_course']) && isset($_POST['selected_class']) && isset($_POST['selected_unit']))
{
$course_code=$_POST['selected_course'];	
$unit=$_POST['selected_unit'];

//retrieve year from years table
	$sql="select year from years";
	$current_year=mysqli_query($conn, $sql) or die("Unable to retrieve year");
	
	while($row=mysqli_fetch_assoc($current_year))
	{$year=$row['year'];}


	//retrieve term from term table
	$sql="select term_code from terms";
	$current_term=mysqli_query($conn, $sql) or die("Unable to retrieve term");
	
	while($row=mysqli_fetch_assoc($current_term))
	{$term=$row['term_code'];}
	
	$sql="SELECT trainees.adm, trainees.name, results_entry.sn, results_entry.course_code, units.unit_name, results_entry.cat, 
	results_entry.exam, results_entry.term, results_entry.exam_year
 	FROM ((trainees
	inner join results_entry
	on trainees.adm=results_entry.adm)
	inner join units 
	on units.unit_code=results_entry.unit_code and units.courses_code=results_entry.course_code) 
	where results_entry.course_code='$course_code' and results_entry.unit_code='$unit' 
	and results_entry.exam_year='$year' and results_entry.term='$term'"; 

	$results=mysqli_query($conn, $sql) or die("Problem running query");
	
	
echo '
<div class="card">
   <div class="card-header">
   <h3 class="card-title">Review marks</h3>
   </div>
   <div class="card-body">
<form id=service_form method=post>

<div> <input  type="Text" id="search" class="form-control search" placeholder="Search..." ></div> <br>
		
<div class=table-responsive >
   <div id="mytable">
       <table class="table table-bordered table-striped text-nowrap" id=mytable>
            <tr class="txt-heading table-secondary">
                <th>SN</th>
                <th>ADM NO</th>
                <th>NAME</th>
                <th>COURSE</th>
                <th>UNIT</th>
                <th>CAT</th>
                <th>EXAM</th>
                <th>TERM</th>
               <!--  <th>YEAR</th>-->
                <th>ACTION</th>
				 </tr>
			<tbody id=records>';
            
			$count=1;
            while($row = mysqli_fetch_assoc($results)) { 
				echo '<tr>
			
				<td>'.$count.'</td>
				<td>'.strtoupper($row['adm']).'</td>
				<td>'.strtoupper($row['name']).'</td>
				<td>'.$row['course_code'].'</td>
				<td>'.strtoupper($row['unit_name']).'</td>
				<td contentEditable="true" class="cats" id="'.$row['sn'].'">'.$row['cat'].'</td>
				<td contentEditable="true" class="exams" id="'.$row['sn'].'">'.$row['exam'].'</td>
				<td>'.$row['term'].'</td>
				
				<td><a href="#" class="delete" id='.$row['sn'].' style="color:red">Delete</a></td>
			
             	</tr>'; 
			
			
$count++; } } else {echo '<div class="alert alert-warning"><strong>Error!</strong> No units assigned to the selected course</div>';} ?>
				
				</tbody>
        </table>
    </div>
	</div>
	
	
	</form>
	</div>
	</div>
	
	    
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
	
.txt-heading{    
	padding: 10px 10px;
    border-radius: 2px;
    color: #333;
    background: #d1e6d6;
	margin:20px 0px 5px;
}

table th{border-bottom:#F0F0F0 2px solid;text-align:left;}
table th{border-bottom:#F0F0F0 2px solid;text-align:left;}
</style>
