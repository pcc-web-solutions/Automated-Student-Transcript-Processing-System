<?php 
session_start();
require('../../Database/config.php');

$status = $_SESSION['mark_entry_status'];
if(isset($_POST['selected_course']) && isset($_POST['selected_unit']) && isset($_POST['selected_class']))
{
	$course_code=$_POST['selected_course'];	
	$unit=$_POST['selected_unit'];
	$class=$_POST['selected_class'];

	if(empty($unit)){
		echo '<div class="alert alert-warning"><strong>Error!</strong> No units assigned to the selected course</div>';
	}
	elseif($class == "" ){
		echo '<div class="alert alert-warning"><strong>Error!</strong> No classes registered for the selected course</div>';
	}
	else{
		$trainees=$conn->query("SELECT trainees.sn,  trainees.adm, trainees.name, courses.code, courses.course_name, 
		units.unit_code, units.unit_name, terms.term_name, years.year
		from ((((trainees
				inner join courses
				on courses.code=trainees.course_code)
				inner join units
				on courses.code=units.courses_code)
				inner join terms)
				inner join years) where units.unit_code= '$unit' and courses.code='$course_code' and class='$class' AND trainees.status = '1'");

		
		if(mysqli_num_rows($trainees)<1){
			echo '<div class="alert alert-warning"><strong>Error!</strong> There are no registered pupils for that class</div>';
		}
		else{
			echo '<form method="post" id="myForm">
			<div class=table-responsive>
			<table class="table table-bordered table-striped text-nowrap">
			<tr class="bg-success">
			<th style="width:10%"> ADM NO</th>
			<th style="width:30%"> NAME</th>
			<th style="width:40%"> UNIT</th>
			<th style="width:10%"> CAT</th>
			<th style="width:10%"> EXAM</th>
			</tr>';
			
			
			
			$i=0;
			while ($row=mysqli_fetch_assoc($trainees))
			{
				echo '<tr > <input type="hidden" name="row_id[]" value="'.$row['sn'].'"/>
				<input type="hidden" name="course_code[]" value="'.$row['code'].'"/>
				<input type="hidden" name="unit_code[]" value="'.$row['unit_code'].'"/>
				<input type="hidden" name="adm[]" value="'.$row['adm'].'"/>

				<input type="hidden" name="term[]" value="'.$row['term_name'].'"/>
				<input type="hidden" name="year[]" value="'.$row['year'].'"/>

				<td>'.strtoupper($row['adm']).'</td>
				<td >'.strtoupper($row['name']).'</td>
				<td>'.strtoupper($row['unit_name']).'</td>
				<td style="padding:0px"><input type="text" class="form-control cat" name="cat[]"  style="border:none;width:100%; height:100%"/></td>
				<td style="padding:0px"><input type="text" class="form-control exam" name="exam[]" style="border:none;width:100%; height:100%"/></td>
				</tr>';

				$i++;
			}
			echo '<table class="table table-borderless">
			</table>
			</div>

			<button type="button" class="btn btn-info float-right" id="save" style=width:30%>SAVE</button>


			</form>';
		}
	}
}
?>
<style>	
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

	td{white-space:nowrap;}
	
	input[type=text]{heihgt:100%}
		
</style>
	
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
  
  $('#save').click(function(){
	 var ids = [];
	 var adm = [];
	 var code=[];
	 var unit_code=[];
	 var cat = [];
	 var exa = [];
	 var term = [];
	 var year = [];
	  
	  $("#myForm input[name='row_id[]']").each(function() {
			ids.push($(this).val());
		});
		
		$("#myForm input[name='adm[]']").each(function() {
			adm.push($(this).val());
		});
		
		$("#myForm input[name='course_code[]']").each(function() {
			code.push($(this).val());
		});
		
		$("#myForm input[name='unit_code[]']").each(function() {
			unit_code.push($(this).val());
		});
		
		$("#myForm input[name='cat[]']").each(function() {
			cat.push($(this).val());
		});
		$("#myForm input[name='exam[]']").each(function() {
			exa.push($(this).val());
		});
		
		
		$("#myForm input[name='term[]']").each(function() {
			term.push($(this).val());
		});
		
		$("#myForm input[name='year[]']").each(function() {
			year.push($(this).val());
		});
		
	  $.ajax({
		 url:'Insertdata/insert_data.php',
		method:'post',
		data:{ids : ids, adm:adm, cat:cat,exa:exa, code:code, unit_code:unit_code, term:term, year:year},	  
	  	success:function(data)
		{
			alert(data);					
		}
	  })
  })

	})
  </script>


