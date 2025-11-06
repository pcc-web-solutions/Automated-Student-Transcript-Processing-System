<?php
	require_once("../../Database/dbcontroller.php");
	$db_handle = new DBController();
	include("../../Database/config.php");
	
	$courses = $conn->query("SELECT course_name, code FROM courses ORDER BY course_name ASC");
	// $classes = $conn->query("SELECT class_name, class_id FROM classes ORDER BY class_name ASC");
?>

<html>
<head>
<link rel="stylesheet" href="../Libraries/dist/css/adminlte.min.css">
<script src="../Libraries/plugins/jquery/jquery.min.js"></script>
<script src="../jquery-ui.min.js"></script>

<style>
.txt-heading{    
	padding: 10px 10px;
    border-radius: 2px;
    color: #333;
    background: #d1e6d6;
	margin:2px 0px 5px;
}

.txt-heading2{    
	padding: 10px 10px;
    border-radius: 2px;
    color: #333;
    background: #d1e6d6;
	margin:20px 0px 5px;
}

button:hover{opacity:0.9}

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
	
.form-control{
	border-color:transparent;
	background-color:transparent;
}

.form-control:focus{
	border-color:transparent;
	background-color:transparent;
	
}
</style>

</head>

<body>
<div class="card card-success  card-outline">
   <div class="card-header">
   <h4 class="card-title">Trainee Registration Form</h4>
   <!-- <button type="button" class="btn btn-primary float-right" id="batchregistration"><i class="fa fa-users"></i>&nbsp Multiple Registration </button> -->
   </div>
   <div class="card-body">

<div>
<div class="txt-heading">Trainee info.</div>
<div class="table-responsive">
	<table class ="table table-bordered text-nowrap">
		<tbody>
			<tr>
				<th>Course</th>
				<th>Class</th>
				<th>ADM No</th>
				<th>Name</th>
				<th>Gender</th>
			</tr>	
			<tr>
				<td style="padding:2px; padding-left:2px">
					<div class="col-md-12">
						<select id=course class="form-control select2" >
							<option value="">--Select course--</option>
							<?php
								while($data=mysqli_fetch_assoc($courses)){
									echo "<option value=".$data['code'].">".strtoupper($data['course_name'])."</option>";
								}
							?>
						</select>
					</div>
				</td>
				<td style="padding:2px; padding-left:2px">
					<div class="col-md-12">
						<select id=class class="form-control form-control-sm" >
							<option value="">--Select class--</option>
						</select>
					</div>
				</td>
				<td style="padding:2px; padding-left:2px">	
					<div class="col-md-12">
						<input class="form-control form-control-sm" type="text" id=adm data-id="adm"></input>
					</div>
				</td>
				<td style="padding:2px; padding-left:2px">
					<div class="col-md-12">
						<input class="form-control form-control-sm" type="text" id=name data-id="name"></input>
					</div>
				</td>
				<td style="padding:2px; padding-left:2px">
					<div class="col-md-12">
						<select id=gender class="form-control form-control-sm" >
							<option value="">--Select--</option>
							<option value="Male">Male</option>
							<option value="Female">Female</option>
						</select>
					</div>
				</td>
				
				
			</tr>
		</tbody>
	</table>	
	</div>

<div class=wrapper>
<button type=button class="btn btn-success " id="btnSaveAction"><i class="fa fa-save"></i>&nbspSave to Database</button>
</div>

<div id="list-product">
<div class="txt-heading2">Trainee</div>
	<table class ="table table-bordered table-responsive text-nowrap" cellpadding="10" cellspacing="1">
		<thead id="ajax-response">
			<tr>
				<th style="width:5%">SN</th>
				<th style="width:20%">Adm No</th>
				<th style="width:40%">Name</th>
				<th style="width:20%">Gender</th>
				<th style="width:15%">Class</th>
				<th style="width:15%">Course</th>
			</tr>
		</thead>	
		<tbody id=inserteddata>

		</tbody>
	</table>
	
</div>

</body>


<script>
$(function(){
  //Initialize Select2 Elements
  $('.select2').select2()

  //Initialize Select2 Elements
  $('.select2bs4').select2({
    theme: 'bootstrap4'
  })
})
$(document).ready(function(){
	$('#adm').focus(function(e){
		e.preventDefault();
		var course = $('#course').val();
		if(course == ''){
			alert('Please select a course first');
		}
	})
	$('#name').focus(function(e){
		e.preventDefault();
		var course = $('#course').val();
		if(course == ''){
			alert('Please select a course first');
		}
	})
	
	$('#course').change(function(e){
		e.preventDefault();
		var course = $('#course').val();
		$.ajax({
            url: 'New/select_class_for_registration.php',
            type: 'post',
            data: {selected_course:course},
            dataType: 'json',
            success:function(response){

                var len = response.length;

                $("#class").empty();

						for( var i = 0; i < len; i++){
                    var class_name = response[i]['class_name'];
                    
                    $("#class").append("<option value='"+class_name+"'>"+class_name+"</option>");
                }
            } 
        });
	});
	
	$('#batchregistration').click(function(){
		$("#container").load('./Modals/batchtrregistration-modal.php');
	});
	
	$('#btnSaveAction').click(function(e){
		e.preventDefault();
		var errors = '';
		var adm = $('#adm').val();
		var name = $('#name').val();
		var gender = $('#gender').val();
		var classname = $('#class').val();
		var course = $('#course').val();

		if(name == ""){
			alert('Please enter full name');
		}
		else if(adm == ""){
			alert('Please enter admission number');
		}
		else if(classname == ""){
			alert('Please select class');
		}
		else if(gender == ""){
			alert('Please select gender');
		}
		else if(course == ""){
			alert('Please select course');
		}
		else{
			$.ajax({
				url: "Insertdata/insert_row.php",
				type: "POST",
				data: {name:name, adm:adm, classname:classname, course:course, gender:gender},
				success: function(data){
					
					if(data == "Trainee already registered"){
						alert(data);
					}
					else{
						$("#inserteddata").prepend(data);	
					}
					
				}
			});
		}	
	});
});	
</script>
</html>