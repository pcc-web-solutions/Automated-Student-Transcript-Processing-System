<?php
	session_start();

	include('../../Database/config.php');

	$get_departments = $conn->query("SELECT DISTINCT departments.department_code, departments.department_name FROM departments INNER JOIN courses ON courses.department_code = departments.department_code INNER JOIN trainees ON trainees.course_code = courses.code ORDER BY department_name ASC");
	$sql="SELECT * from years order by  year";
	$years_result=mysqli_query($conn, $sql);
	while($row=mysqli_fetch_assoc($years_result)) {$year = $row['year'];}
	
	$sql="SELECT * from terms order by  term_name";
	$terms_result=mysqli_query($conn, $sql);
	while($row=mysqli_fetch_assoc($terms_result)) {$term = $row['term_name'];}
	$getCatMax = $conn->query("SELECT max FROM mark_entry_limits WHERE exam = 'cat' AND term = '$term' and year = '$year' ");
	$getExamMax = $conn->query("SELECT max FROM mark_entry_limits WHERE exam = 'exam' AND term = '$term' and year = '$year' ");
	
	$catMax = ""; $examMax = ""; $btnId="save"; $limits = array();
	if($getCatMax->num_rows>0 && $getExamMax->num_rows>0){
		while ($row1=$getCatMax->fetch_array()) { $catMax = $row1['max']; }
		while ($row2=$getExamMax->fetch_array()) { $examMax = $row2['max']; }
		$btnId = "update";
	}
?>

<html>
<head>
<script src="../../Assets/plugins/jquery/jquery.min.js"></script>
<script src="../../Assets/jquery-ui.min.js"></script>
<script>

	function showModal(){$("#setMarkLimits").modal({backdrop: 'static', keyboard: false})}
	function hideModal(){$("#setMarkLimits").modal("hide");}
	
	$("body").click(function(){
		if(("body *").hasClass("disabled")){toastr.info("Mark entry has been locked")}
	})
	
	if($("#catmax").val() == "" || $("#exammax").val() == ""){showModal();}
	else{
		/*if(confirm("Would you mind changing the mark entry limits?")){showModal();}
		else{hideModal()}*/
		hideModal()
	}
	$("#closeModal").click(function(e){
		if($("#catmax").val() == "" || $("#exammax").val() == ""){
			if(confirm("Limits must be set for mark entries. Are you sure you want to discard the changes?")){
				$('body *').prop('disabled', true); hideModal(); toastr.info("Mark entry has been locked")
			}
			else{e.preventDefault()}
		}
		else{hideModal();}
	})
	$("button.limits").click(function(e){
		var action = $(this).attr("id");
		var catMax = $("#catmax").val(); var examMax = $("#exammax").val();
		if(catMax == "" || examMax == ""){toastr.error("Cannot save blanks")}
		else if(!Number.isInteger(Number(catMax)) || !Number.isInteger(Number(examMax)) ){toastr.error("Only integer values are allowed!")}
		else if((Number(catMax) + Number(examMax)) != 100){ toastr.error("Mark limits should add up to 100!");}
		else{
			$.ajax({
				url: 'Insertdata/mark_limits.php', method: 'post', data: {request:action,catmax:catMax, exammax:examMax},
				success: function(data){
					if(data == "save success"){
					toastr.success("Mark limits set successfully"); hideModal();
				}
				else if(data == "update success"){
					toastr.info("Mark limits updated successfully"); hideModal();}
				else{toastr.error(data); e.preventDefault() }
				}
			})
		}
	})
	function submitThis(button){
	  	var row = button.parentNode.parentNode;
	  	var action = button.innerHTML.replace(/<\/?[^>]+(>|$)|&nbsp;/g, "");
	  	var snInput = row.querySelector('.sn')
	  	var courseInput = row.querySelector('.course')
	  	var unitInput = row.querySelector('.unit')
	  	var termInput = row.querySelector('.term')
	  	var yearInput = row.querySelector('.year')

	  	var admInput = row.querySelector('.adm')
	  	var catInput = row.querySelector('.cat');
	  	var examInput = row.querySelector('.exam');
	  	
	  	var context = "single";

	  	if(admInput){var adm = admInput.innerText; }
	  	if(catInput){var cat = catInput.innerText; }
	  	if(examInput){var exam = examInput.innerText; }
	  	if(termInput){var term = termInput.innerText; }
	  	if(yearInput){var year = yearInput.innerText; }
	  	if(unitInput){var unit = unitInput.innerText; }
	  	if(snInput){var sn = snInput.innerText; }
	  	if(courseInput){var course = courseInput.innerText; }
	  	var catErrors = row.querySelector(".cat").classList.contains("error")
	  	var examErrors = row.querySelector(".exam").classList.contains("error")
	  	
	  	if(catErrors === true || examErrors === true || cat=="" || exam==""){toastr.error("Cannot "+action)}
	  	else{
	  		var dataValues = {action:action,context:context,adm:adm,cat:cat,exam:exam,term:term,year:year,unit:unit,course:course}
		  	$.ajax({
		  		url: 'Insertdata/individual_marks.php',
		  		method: 'post',
		  		data: dataValues,
		  		success: function(data){
		  			if(data != "success"){toastr.error(data)}
		  			else{
		  				toastr.info(action+" successful")
		  			}
		  		}
		  	})
	  	}
	  	
	}

	function changeBackground(obj){
    	$(obj).style.borderColor = "transparent";
	}
	function check(obj, max){
		var value = obj.innerHTML;
		if(!Number.isInteger(Number(value)) || value > max){
			$(obj).addClass("error");
		}
		else{
			$(obj).removeClass("error");
		}
	}
</script>
<style>
	td.error{
		border:2px solid red;
	}
	.row{margin-bottom:5px}
	.card-body{
		padding-left:9px;
		padding-right:9px;
	}
	.disabledContent{pointer-events: none; opacity: 0.4;}
</style>
</head>

<body id="thisform" class="disabledContent">
	<div class="modal fade" id="setMarkLimits">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<h6 class="modal-title">Set mark entry limits</h6>
				</div>
				<div class="modal-body">
					<div class="brand-text">Maximum Values</div>
					<label>Cat:</label>
					<input type="text" class="form-control form-control-sm" max="100" id="catmax" value="<?php echo $catMax; ?>"> </input>
					<label>Exam:</label>
					<input type="text" class="form-control form-control-sm" max="100" id="exammax" value="<?php echo $examMax; ?>"> </input>
				</div>
				<div class="card-footer">
					<button type="button" class="btn btn-danger float-left" id="closeModal">Close</button>
					<button type="button" class="btn btn-success float-right limits" id="<?php echo $btnId; ?>"><?php echo $btnId; ?></button>
				</div>
			</div>
		</div>
	</div>
	<div class="card card-info card-outline">
		<div class="card-header">
			<h5 class="card-title">Mark entry</h5>
			<span><button type="button" onclick="showModal()" class="form-control-sm btn-secondary float-right">Set mark limits</button></span>
		</div>
		<div class="card-body">
			<form class="form-horizontal" id=dialog>
				<div class=row>
					<div class=col-sm-3  style="margin-bottom: 10px;">
						<select class="form-control form-control-sm" name=dept id=dept>
							<option value="">--Select Department--</option>
							<?php
							while($row=mysqli_fetch_assoc($get_departments)) { 
								$dept_code = $row['department_code'];
								$dept_name = $row['department_name'];
								?>
							<option value="<?php echo $dept_code;?>"><?php echo $dept_name;?></option>
							<?php } ?>
						</select>
					</div>

					<div class=col-sm-3  style="margin-bottom: 10px;">
						<select class="form-control form-control-sm" name="course" id="course">
							<option value="">--Select Course--</option>
						</select>
					</div>

					<div class=col-sm-2  style="margin-bottom: 10px;">
						<select class="form-control form-control-sm" name="unit_name"  id="unit_name">
							<option value="">--Select Unit--</option>
						</select>
					</div>

					<div class=col-sm-2  style="margin-bottom: 10px;">
						<select class="form-control form-control-sm" name="class_name"  id="class_name">
							<option value="">--Select Class--</option>
						</select>
					</div>
					<div class="col-md-2"  style="margin-bottom: 10px;">
						<button type="button" class="form-control-sm btn-primary float-right" id="load-trainees"><i class="fa fa-search"></i>&nbsp Get Trainees</button>
					</div>
				</div>
			</form>
			<hr style="margin-top: 0px; margin-bottom: 0px;">
			<div class=tbl></div>
		</div>
	</div>
</body>

<script type="text/javascript">
$(document).ready(function(){

	function dept(){return $("#dept").children("option:selected").val();}
	function selected_course(){return $('#course').children("option:selected").val();}
	function selected_unit(){return $('#unit_name').children("option:selected").val();}
	function selected_class(){return $('#class_name').children("option:selected").val();}

	function load_courses(){
		$.ajax({
			url: 'Requests/req_courses_for_mark_entry.php',
			method: 'post',
			data: {dept_code:dept()},
			dataType: 'json',
			success: function(response){
				var len = response.length;
				if(len>0){
					$("#course").empty();
					$("#course").append("<option value=''>--Select Course--</option>");
					for(var i = 0; i<len; i++){
						var course_code = response[i]['course_code'];
						var course_name = response[i]['course_name'];
						$("#course").append("<option value="+course_code+">"+course_name+"</option>");
					}
				}
				else{
					$("#course").empty();
					$("#course").append("<option value=''>--No courses found--</option>");
				}
			}
		})
	}
    
    function load_units(){
    	$.ajax({
			url: 'New/select_units.php',
			type: 'post',
			data: {selected_course:selected_course()},
			dataType: 'json',
			success:function(response){

				var len = response.length;

				$("#unit_name").empty();
				$("#unit_name").append("<option value=''>--Select Unit--</option>");
				if(len<1){
					$("#unit_name").append("<option value=''>No units Found</option>");
					document.querySelector('#load-trainees').style.visibility="hidden";
				}
				else{
					document.querySelector('#load-trainees').style.visibility="visible";
					for( var i = 0; i < len; i++){
						var unit_code = response[i]['unit_code'];
						var unit_name = response[i]['unit_name'];
						
						$("#unit_name").append("<option value='"+unit_code+"'>"+unit_name+"</option>");
					}
				}
			} 
		});
    }

    function load_classes(){
    	$.ajax({
			url: 'New/select_class.php',
			type: 'post',
			data: {selected_course:selected_course()},
			dataType: 'json',
			success:function(response){

				var len = response.length;

				$("#class_name").empty();
				// $("#class_name").append("<option value=''>--Select Class--</option>");
				if(len<1){
					$("#class_name").append("<option value=''>No Classes Found</option>");
					document.querySelector('#load-trainees').style.visibility="hidden";
				}
				else{
					document.querySelector('#load-trainees').style.visibility="visible";
					for( var i = 0; i < len; i++){
						var class_name = response[i]['class_name'];
						
						$("#class_name").append("<option value='"+class_name+"'>"+class_name+"</option>");
					}
				}
			} 
		});
    }

    function load_entry_sheet(){
    	$.ajax({
			url:'New/mark_entry_sheet_new.php',
			method:'post',
			data:{selected_course: selected_course(), selected_unit:selected_unit(), selected_class:selected_class()},

			success:function(data)
			{
				$('.tbl').html(data);	
			}
		})
    }

    $("#dept").change(function(){
    	load_courses();
    })
    $('#load-trainees').click(function()
	{
		if (dept() == "") {
			toastr.error("Please select a department to proceed");
		}
		else if ( selected_course() == "" ){toastr.error('Please select course');}
		else if ( selected_unit() == "" ){toastr.error('Please select unit');}
		else if ( selected_class() == "" ){toastr.error('Please select class');}
		else{load_entry_sheet()}
	})

    $("#course").change(function(){
		if(selected_course() == ''){
			$('.tbl').html('<div class="alert alert-warning"><strong>Error!</strong> No classes registered for the selected course</div>');
		}
		else{
			load_units()
			load_classes()
			$('.tbl').empty();
		}
    });

    $('#unit_name').change(function(){
		load_entry_sheet();
	});
	
	$('#class_name').change(function(){
		load_entry_sheet();
	});
});


</script>

</html>