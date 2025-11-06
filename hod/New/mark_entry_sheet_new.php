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
		$sql="SELECT * from years order by  year";
		$years_result=mysqli_query($conn, $sql);
		while($row=mysqli_fetch_assoc($years_result)) {$year = $row['year'];}
		
		$sql="SELECT * from terms order by  term_name";
		$terms_result=mysqli_query($conn, $sql);
		while($row=mysqli_fetch_assoc($terms_result)) {$term = $row['term_name'];}

		$sql1 = $conn->query("SELECT max FROM mark_entry_limits WHERE exam ='exam' AND term = '$term' AND year = '$year'");
		$sql2 = $conn->query("SELECT max FROM mark_entry_limits WHERE exam ='cat' AND term = '$term' AND year = '$year'");
		if($sql1->num_rows>0 && $sql2->num_rows>0){
			while ($row1=$sql1->fetch_array()) {$exammax = $row1['max'];}
			while ($row2=$sql2->fetch_array()) {$catmax = $row2['max'];}
		}
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
			<table class="table table-bordered table-striped text-nowrap" id="myTable">
			<tr class="bg-info">
			<th style="width:10%"> ADM NO</th>
			<th style="width:30%"> NAME</th>
			<th style="width:30%"> UNIT</th>
			<th style="width:10%"> CAT</th>
			<th style="width:10%"> EXAM</th>
			<th style="width:10%"> ACTION</th>
			</tr>';
			
			$i=0;
			$records_to_update = 0;
			$records_to_save = 0;
			while ($row=mysqli_fetch_assoc($trainees))
			{
				$adm = $row['adm'];
				$unit = $row['unit_code'];
				$term = $row['term_name'];
				$year = $row['year'];
				$cat = "";
				$exam = "";
				$action = "";
				$sql = $conn->query("SELECT cat, exam FROM results_entry WHERE adm = '$adm'AND unit_code = '$unit' AND term = '$term' AND exam_year = '$year' ");
				if($sql->num_rows>0){
					while ($my=$sql->fetch_array()) {
						$cat = $my['cat'];
						$exam = $my['exam'];
						$action = "<i class='fa fa-refresh'></i>&nbspupdate"; $class = "update";
					}
					$records_to_update++;
				}
				else{
					$action = "<i class='fa fa-save'></i>&nbspsave"; $class = "save";
					$records_to_save++;
				}
				echo '<tr>';
				echo '
				<td style="display: none" contentEditable="false" class="sn">'.$row['sn'].' </td>
				<td style="display: none" contentEditable="false" class="course">'.$row['code'].' </td>
				<td style="display: none" contentEditable="false" class="unit">'.$row['unit_code'].' </td>
				<td style="display: none" contentEditable="false" class="term">'.$row['term_name'].' </td>
				<td style="display: none" contentEditable="false" class="year">'.$row['year'].' </td>
				<td contentEditable="false" class="adm">'.strtoupper($row['adm']).'</td>
				<td contentEditable="false" class="name">'.strtoupper($row['name']).'</td>
				<td contentEditable="false" class="unit_name">'.strtoupper($row['unit_name']).'</td>
				<td contentEditable="true" class="cat" onfocus="changeBackground(this);" onblur="check(this, '.$catmax.')">'.$cat.'</td>
				<td contentEditable="true" class="exam" onfocus="changeBackground(this);" onblur="check(this, '.$exammax.')">'.$exam.'</td>
				<td style="padding:0px;"><button type="button" class="form-control '.$class.'" onclick="submitThis(this)" name="action[]" style="padding: 0px; width:100%; height:100%; padding-bottom: 0px" >'.$action.'</button></td>
				</tr>';

				$i++;
			}
			echo '</table>
			</div>
			<div style="padding-left: 10px; margin-bottom: 10px;">
			<button type="button" class="form-control-sm btn-primary float-right" id="save"><i class="fa fa-upload">&nbspUpload</i></button>
		</div>
			</form>';
		}
	}
}
?>
<style>	
	td.error{
		border-color: red;
		color: red;
		border-radius: 1px;
	}
	tr{
	line-height:5px;
	min-height:5px;
	height:5px; 
	
	}
	tr th{
	line-height:6px;
	min-height:6px;
	height:6px;
	
	}

	td{white-space:nowrap;}
	
	input[type=text]{height:100%}

	td[contentEditable=true]{
		height: inherit; margin-top: 2px; 
		padding-bottom: 0px;
	}
	td.cat:focus, td.exam:focus{
		border: 1px solid white;
	}
	button.save{
		background-color: darkgoldenrod;
		color: white;
		border: 2px solid darkgoldenrod;
		font-family: sans-serif;
	}
	button.save:hover{
		background-color: inherit;
		color: darkgoldenrod;
	}
	button.save:focus{
		background-color: inherit;
		color: darkgoldenrod;
	}

	button.update{
		background-color: lightseagreen;
		color: white;
		border: 2px solid lightseagreen;
		font-family: sans-serif;
	}
	button.update:hover{
		background-color: inherit;
		color: lightseagreen;
	}
	button.update:focus{
		background-color: inherit;
		color: lightseagreen;
	}
</style>
	
<script>
	$(document).ready(function(){
		
	  $("#search").on("keyup", function() {
	    var value = $(this).val().toLowerCase();
	    $("#records tr").filter(function() {
	      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
	    })
	  })

	  $('#save').click(function(){
		var ids = [];
		var adm = [];
		var course = [];
		var unit = [];
		var cat = [];
		var exam = [];
		var term = [];
		var year = [];
		var action = [];
		var context = "all";
		var errors = 0; var blanks = 0;
		document.querySelectorAll('td.sn[contenteditable="false"]').forEach(td=>{
			ids.push(td.textContent.trim());
		});

		document.querySelectorAll('button[name="action[]"]').forEach(button=>{
			action.push(button.innerText.replace(/<\/?[^>]+(>|$)|&nbsp;/g, ""));
		});

		document.querySelectorAll('td.adm[contenteditable="false"]').forEach(td=>{
			adm.push(td.textContent.trim());
		});
		
		document.querySelectorAll('td.course[contenteditable="false"]').forEach(td=>{
			course.push(td.textContent.trim());
		});
		
		document.querySelectorAll('td.unit[contenteditable="false"]').forEach(td=>{
			unit.push(td.textContent.trim());
		});
		
		document.querySelectorAll('td.cat[contenteditable="true"]').forEach(td=>{
			cat.push(td.textContent.trim());
			if (td.classList.contains("error")) {errors++}
			if (td.textContent.trim()=="") {blanks++}
		});

		document.querySelectorAll('td.exam[contenteditable="true"]').forEach(td=>{
			exam.push(td.textContent.trim());
			if (td.classList.contains("error")) {errors++}
			if (td.textContent.trim()=="") {blanks++}
		});
		
		document.querySelectorAll('td.term[contenteditable="false"]').forEach(td=>{
			term.push(td.textContent.trim());
		});
		
		document.querySelectorAll('td.year[contenteditable="false"]').forEach(td=>{
			year.push(td.textContent.trim());
		});

		if(blanks==0 && errors==0){
			var dataValues = {context:context,action:action,ids:ids,adm:adm,cat:cat,exa:exam,term:term,year:year,unit_code:unit,code:course};
			$.ajax({
				url:'Insertdata/individual_marks.php',
				method:'post',
				data:dataValues,	  
				success:function(data){
					if(data=="error"){toastr.error("Server error");}	
					else{toastr.success(data);}				
				}
			})
		}
		else{
			toastr.error(errors+" Error(s) highlighted! <br>"+blanks+" Blank(s) found")
		}
	})
})
</script>