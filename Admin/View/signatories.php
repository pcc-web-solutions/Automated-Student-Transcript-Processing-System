<?php 
include "../../Database/config.php"; 
$trainers = "SELECT * FROM trainers WHERE trainer_id NOT IN (SELECT sname FROM signatory)";

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Set report signatories</title>
	<style type="text/css">
		td select, td select.form-control, td select.form-control:hover, td select.form-control:focus{
			width: 100%;
			border-radius: 0px;
			border: 0px solid transparent;
			background-color: inherit;
			color: inherit;
		}
		tr.designation:hover{
			pointer: pointer;
		}
	</style>
	<script type="text/javascript">

		function toggle_signatory(obj){
			var name = $(obj).children("option:selected");
			var value = $(obj).children("option:selected").val();
			if (value == "new") {
				showModal("#newSignatory")
			}
		}
		function count(){
			var count = 0; var maximum; var error = false;
			$('.chkdesignation').each(function(){
				maximum = $("input[name='r_maxnos']").val();
				if($(this).prop("checked") == true){
					count++;
					if(count>maximum){
						error = true;
						Toast.fire({icon:"warning", title:"You can only select up to "+maximum+" signatories"});
					}
				}
			})
			return error;
		}

		function setSignatory(){
			var form = "frmSetSignatory";
			if(count() == false){
				var designations = []; 
				var signatories = [];
				var rname = $("select[name='report']").children("option:selected").val()
				
				var value;
				var temp_designations = [];
				var temp_signatories = []; 
				var indices = [];

 				var cnt=0;

				// Holding all designations
				$('.chkdesignation').each(function(){
					value = $(this).val();
					temp_designations.push(value);
				});

 				// Holding all signatories
				$('.signfor').each(function(){
					value = $(this).children("option:selected").val();
					temp_signatories.push(value);
				})

				$('.chkdesignation').each(function(){
					if($(this).prop("checked") == true){
						indices.push(cnt);
						designations.push(temp_designations[cnt]);
						signatories.push(temp_signatories[cnt]);
					}
					cnt++;
				});

				var datavalues = {
					request:"setSignatory",
					report:rname,
					signatories:signatories,
					designations:designations
				}
				// Toast.fire({icon:"info", title:indices});
				$.ajax({
		        url: "Insertdata/insert.php",
		        method: "POST",
		        data: datavalues,
		        dataType: 'json',
		        success: function(response){
		          if(response.status=='success'){
		            Toast.fire({icon: 'success', title: response.message})
		            $(form).trigger('reset');
		          }
		          else{
		            Toast.fire({icon: 'error', title: response.message})
		          }
		        },
		        error: function(){
		          Toast.fire({icon: 'error', title: response.message})
		        }
		    })
			}
			else{
				maximum = $("input[name='r_maxnos']").val();
				Toast.fire({icon:"warning", title:"You can only select up to "+maximum+" signatories"});
			}
		}
		document.querySelector("button[name='setSignatory']").addEventListener("click", function(){
			setSignatory();
		})
	</script>
</head>
<body>
<div class="modal fade" id="newReport">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title">New Report</h6>
				<span class="card-tools"><a href="#" data-dismiss="modal" class="text-danger">&times</a></span>
			</div>
			<div class="modal-body">
				<form id="frmNewReport">
					<div class="form-group">
						<label>Report Name:</label>
						<input type="text" name="rname" class="form-control form-control-sm form-control-border border-width-1" placeholder="e.g. Passlist" >
					</div>
					<div class="form-group">
						<label>Minimum Signatories:</label>
						<input type="number" name="minnos" class="form-control form-control-sm form-control-border border-width-1" placeholder="e.g 1" >
					</div>
					<div class="form-group">
						<label>Maximum Signatories:</label>
						<input type="number" name="maxnos" class="form-control form-control-sm form-control-border border-width-1" placeholder="e.g 2" >
					</div>
					<button type="submit" class="btn-sm btn-info btn-flat float-right" name="saveReport">Save</button> 
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="SelectSignatory">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title">Change Signatory</h6>
				<span class="card-tools"><a href="#" data-dismiss="modal" class="text-danger">&times</a></span>
			</div>
			<div class="modal-body">
				<form id="frmSelectSignatory">
					<div class="form-group">
						<label>Signatory Name:</label>
						<select name="chosensignatory" class="form-control select2" data-placeholder="select signatory">
							<option value="">--select--</option>
							<?php
                  $run = mysqli_query($conn, $trainers);
                  while($result=mysqli_fetch_assoc($run)) {
                  	$id = $result['trainer_id'];
                  	$lastname = $result['last_name'];
                  	$firstname = $result['first_name'];
                  	$phone = $result['phone_no'];
                    echo "<option value='".$result['trainer_id']."'>".$lastname." ".$firstname."</option>";
                  } 
              ?>
						</select>
					</div>
					<button type="submit" class="btn-sm btn-info btn-flat float-right" name="saveChosenSignatory">Save</button> 
				</form>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="newDesignation">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title">Select Signatory</h6>
				<span class="card-tools"><a href="#" data-dismiss="modal" class="text-danger">&times</a></span>
			</div>
			<div class="modal-body">
				<form id="frmNewDesignation">
					<div class="form-group">
						<label>Designation Name:</label>
						<input type="text" name="rptdesignation" class="form-control form-control-sm form-control-border border-width-1" placeholder="e.g. H.O.D Examinations" >
					</div>
					<button type="submit" class="btn-sm btn-info btn-flat float-right" name="saveDesignation">Save</button> 
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="newSignatory">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title">New Signatory</h6>
				<span class="card-tools"><a href="#" data-dismiss="modal" class="text-danger">&times</a></span>
			</div>
			<div class="modal-body">
				<form id="frmNewSignatory">
					<div class="form-group">
						<label>Designation:</label>
						<select name="sigdesignation" class="form-control select2" style="width: 100%;">
		                <option value="">--Select--</option>
		                <?php
		                    $sql = "SELECT * FROM designation";
		                    $run = mysqli_query($conn, $sql);
		                    while($result=mysqli_fetch_assoc($run)) {
		                        echo "<option value='".$result['code']."'>".$result['description']."</option>";
		                    } 
		                ?>
		            </select>
					</div>
					<div class="form-group">
						<label>Signatory:</label>
						<select name="sigtitle" class="form-control select2" style="width: 100%;">
							<option value="">--select--</option>
							<?php
                $run = mysqli_query($conn, $trainers);
                while($result=mysqli_fetch_assoc($run)) {
                	$id = $result['trainer_id'];
                	$lastname = $result['last_name'];
                	$firstname = $result['first_name'];
                	$phone = $result['phone_no'];
                  echo "<option value='".$result['trainer_id']."'>".$lastname." ".$firstname."</option>";
                } 
              ?>
						</select>
					</div>
					<button type="submit" class="btn-sm btn-info btn-flat float-right" name="saveSignatory">Save</button> 
				</form>
			</div>
		</div>
	</div>
</div>


<div class="card card-primary card-outline card-outline-tabs">
  <div class="card-header p-0 border-bottom-0">
    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Reports</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false"> Designations</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="custom-tabs-four-messages-tab" data-toggle="pill" href="#custom-tabs-four-messages" role="tab" aria-controls="custom-tabs-four-messages" aria-selected="false">Signatories</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="custom-tabs-four-settings-tab" data-toggle="pill" href="#custom-tabs-four-settings" role="tab" aria-controls="custom-tabs-four-settings" aria-selected="false">Set Signatories</a>
      </li>
    </ul>
  </div>
  <div class="card-body">
    <div class="tab-content" id="custom-tabs-four-tabContent">
      <div class="tab-pane fade show active" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
         <!-- Available reports -->
        <div class="form-group" style="margin-bottom: 5px;">
			<button type="button" class="btn-info form-control-sm float-left" data-toggle="modal" onclick="showModal('#newReport')" data-target="newReport"><i class="fa fa-plus"></i>&nbspNew Report</button>
		</div>
      	<div class="form-group">
			<input type="text" class="form-control form-control-sm border-width-1 search" placeholder="Type to search..." onkeyup="search_table('#report_records')">
		</div>
		<div class=table-responsive>
			<table class="table-bordered table-striped text-nowrap text-muted" style="width: 100%;">
				<thead>
					<tr>
						<th rowspan="2">#</th>
						<th rowspan="2">Report Name</th>
						<th colspan="2"><center>Number of Signatories</center></th>
						<th rowspan="2">Action</th>
					</tr>
					<tr>
						<th>Minimum</th>
						<th>Maximum</th>
					</tr>
				</thead>
				<tbody id="report_records"></tbody>
			</table>
		</div>
      </div>
      <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
         	<!-- Set Designations -->
         	<div class="form-group" style="margin-bottom: 5px;">
				<button type="button" class="btn-info form-control-sm float-left" data-toggle="modal" onclick="showModal('#newDesignation')" data-target="newDesignation"><i class="fa fa-plus"></i>&nbspNew Designation</button>
			</div>
	      	<div class="form-group">
				<input type="text" class="form-control form-control-sm border-width-1 search" placeholder="Type to search..." onkeyup="search_table('#designation_records')">
			</div>
			<div class=table-responsive>
				<table class="table-bordered table-striped text-nowrap text-muted" style="width: 100%;">
					<thead>
						<tr>
							<th>#</th>
							<th>Code</th>
							<th>Description</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody id="designation_records"></tbody>
				</table>
			</div>
      </div>
      <div class="tab-pane fade" id="custom-tabs-four-messages" role="tabpanel" aria-labelledby="custom-tabs-four-messages-tab">
         	<!-- Manage Signatories -->
			<button type="button" class="btn-info form-control-sm float-left" data-toggle="modal" onclick="showModal('#newSignatory')" data-target="#newSignatory"><i class="fa fa-plus"></i>&nbspNew Signatory</button>
			<input type="text" class="form-control form-control-sm float-right search" placeholder="Type to search..." onkeyup="search_table('#signatory_records')">

			<div class=table-responsive>
				<table class="table-bordered table-striped text-nowrap text-muted" style="width: 100%;">
					<thead>
						<tr>
							<th>#</th>
							<th>Designation</th>
							<th>Signatory</th>
							<th>Phone No</th>
							<th>Rank Pos.</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody id="signatory_records"></tbody>
				</table>
			</div>
      </div>
      <div class="tab-pane fade" id="custom-tabs-four-settings" role="tabpanel" aria-labelledby="custom-tabs-four-settings-tab">
        	<!-- Set Report Signatories -->
        	<form id="frmSetSignatory">
				<div class="form-group">
					<label>Select Report:</label>
					<select class="custom-select form-control-sm form-control-border border-width-1" style="width: 100%;" name=report>
		                <option value="">--Choose a report--</option>
		                <?php
		                    $sql = "SELECT * FROM reports";
		                    $run = mysqli_query($conn, $sql);
		                    while($result=mysqli_fetch_assoc($run)) {
		                        echo "<option value='".$result['description']."'>".$result['description']."</option>";
		                    } 
		                ?>
		            </select>
				</div>
				<input type="hidden" name="r_minnos"><input type="hidden" name="r_maxnos">
				<div class="table-responsive">
					<table class="table-bordered table-striped text-nowrap text-muted" style="width: 100%;">
						<thead>
							<tr>
								<th colspan="3" id="maxnos"></th>
							</tr>
							<tr>
								<th>#</th>
								<th>Designation</th>
								<th>Signatory</th>
							</tr>
						</thead>
						<tbody id="set_report_signatory_records"></tbody>
					</table>
				</div>
				<hr>
				<button type="button" class="btn-sm btn-info btn-flat float-left" name="setSignatory"><i class="fa fa-check"></i>&nbspSet</button> 
				<button type="button" class="btn-sm btn-default btn-flat float-right" name="cancel"><i class="fa fa-cancel"></i>&nbspCancel</button>
			</form>
	    </div>
	</div>
</div>
</div>
<!-- /.card -->


</body>
</html>

<script type="text/javascript">
$(function(){
  //Initialize Select2 Elements
  $('.select2').select2()

  //Initialize Select2 Elements
  $('.select2bs4').select2({
    theme: 'bootstrap4'
  })
})

var Toast = Swal.mixin({
  toast: true,
  position: 'top-right',
  showConfirmButton: false,
  timer: 3000
});

function saveSignatory(){
	var form; var rules; var messages; var url; var method; var datavalues; var modalID;
	form = document.querySelector('#frmNewSignatory');
	rules = {
	  sigdesignation: {required: true},
	  sigtitle: {required: true}
	};
	messages =  {
	  sigdesignation: {required: "This field is required"},
	  sigtitle: {required: "This field is required"}
	};

	var sigdesignation = $("select[name='sigdesignation']").children("option:selected").val();
	var sigtitle = $("select[name='sigtitle']").children("option:selected").val();

	datavalues = {
		request:'saveSignatory',
		sigdesignation:sigdesignation,
		sigtitle:sigtitle
	}
	submitData(form, rules, messages, 'Insertdata/insert.php', 'POST', datavalues, '#newSignatory');
}

function saveDesignation(){
	var form; var rules; var messages; var url; var method; var datavalues; var modalID;
	form = document.querySelector('#frmNewDesignation');
	rules = {
	  rptdesignation: {required: true}
	};
	messages =  {
	  rptdesignation: {required: "This field is required"}
	};

	var rptdesignation = $("input[name='rptdesignation']").val();

	datavalues = {
		request:'saveDesignation',
		rptdesignation:rptdesignation
	}
	submitData(form, rules, messages, 'Insertdata/insert.php', 'POST', datavalues, '#NewDesignation');
}

function saveReport(){
	var form; var rules; var messages; var url; var method; var datavalues; var modalID;
	form = document.querySelector('#frmNewReport');
	rules = {
	  rname: {required: true},
	  minnos: {required: true},
	  maxnos: {required: true}
	};
	messages =  {
	  rname: {required: "This field is required"},
	  minnos: {required: "This field is required"},
	  maxnos: {required: "This field is required"}
	};

	var rname = $("input[name='rname']").val();
	var minnos = $("input[name='minnos']").val();
	var maxnos = $("input[name='maxnos']").val();

	datavalues = {
		request:'saveReport',
		rname:rname,
		minnos:minnos,
		maxnos:maxnos
	}
	submitData(form, rules, messages, 'Insertdata/insert.php', 'POST', datavalues, '#newReport');
}

function loadReportSetDesignations(option){
	$.ajax({
		url: 'View/select.php',
		method: 'post',
		data: {request:'report_set_designations', rptoption:option},
		dataType: 'json',
		success: function(response){
			if(response.status == "success"){
				var data = response.data;
				var len = data.length;
				var min = response.minnos; var max = response.maxnos;
				var btnName; var btncolor; var icon; var modal;
				
				document.getElementById("maxnos").innerText = "Select between "+min+" and "+max+" designations only";
				$("input[name='r_minnos']").val(min);
				$("input[name='r_maxnos']").val(max);

				$("#set_report_signatory_records").empty();

				for(var i=0; i<len; i++){
					var description = data[i]['description'];
					var signatory = data[i]['signatory'];
					var code = data[i]['code'];
					var status=data[i]['status'];
					$("#set_report_signatory_records").append("<tr><td>&nbsp<input type='checkbox' class='chkdesignation' value='"+code+"' id='"+signatory+"' onclick='count()' "+status+"></input></td><td contentEditable='false'>"+description+"</td><td>"+signatory+"</td></tr>");
				}
			}
			else if(response.message == "no data"){
				$("#set_report_signatory_records").empty();
				$("#set_report_signatory_records").append("<tr><td colspan=3>No designation created</td></tr>")
			} 
			else{
				$("#set_report_signatory_records").empty();
				Toast.fire({icon:"error", title: response.message})
			}
		},
		error: function(){
			$("#set_report_signatory_records").empty();
			Toast.fire({icon:"error",title:"Error sending request"})
		}
	})
}

document.querySelector("button[name='saveReport']").addEventListener("click", function(){
	saveReport();
})

document.querySelector("button[name='saveDesignation']").addEventListener("click", function(){
	saveDesignation();
})

document.querySelector("button[name='saveSignatory']").addEventListener("click", function(){
	saveSignatory();
})

document.querySelector("select[name=report]").addEventListener("change", function(){
	var rptoption = $(this).val();
	// Toast.fire({icon:"info",title:rptoption})
	if(rptoption != ""){
		loadReportSetDesignations(rptoption);
	}
	else{
		$("#maxnos").empty();
		$("#set_report_signatory_records").empty();
	}
})
</script>