<?php
	require('../../Database/config.php');

	//retrieve year from years table
	$sql="select year from years";
	$current_year=mysqli_query($conn, $sql) or die("Unable to retrieve year");
	while($row=mysqli_fetch_assoc($current_year))
	{$year=$row['year'];}

	//retrieve term from term table
	$sql="select term_name from terms";
	$current_term=mysqli_query($conn, $sql) or die("Unable to retrieve term");
	while($row=mysqli_fetch_assoc($current_term))
	{$term=$row['term_name'];}
	
	$active_session = $conn->query("SELECT start_date, end_date, (end_Date - start_date) AS duration from terms LIMIT 1");
	if ($active_session->num_rows>0) {
		while ($session = mysqli_fetch_assoc($active_session)) {
			$start_date = $session['start_date'];
			$end_date = $session['end_date'];
		}
	}
	else{
		$start_date = "";
		$end_date = "";
		$duration = "";
	}
	$other_sessions = $conn->query("SELECT * FROM mark_entry_dates ORDER BY year DESC, end_date DESC, status ASC");
	$count_open_sessions = $conn->query("SELECT COUNT(sn) AS open FROM mark_entry_dates WHERE status = 'Open'");
	while ($row = mysqli_fetch_assoc($count_open_sessions)) {
		$open_sessions = $row['open'];
	}

?>
<html>
<head>
	<style>
	.row{margin-bottom:10px}
		.card{
			width: 100%;
			margin: 0px 0px 0px 0px;
		}
		table{
		border-collapse: collapse;
		border-radius: 5px;
		width: 100%;
		border: 1px solid grey;
		font-size: 14px;
		margin-bottom: 10px;
	}
	tr td{
		line-height:25px;
		min-height:25px;
		height:25px;
	}
	.col-sm-6,.col-sm-6, .col-sm-4, .col-md-4{
		margin-bottom: 10px;
	}
	tbody tr,td{
		border: 1.5px solid silver;
	}
	tbody tr:nth-child(even){
		background-color: lightcyan;
	}
	tr th{
		line-height:25px;
		min-height:25px;
		height:25px;
		background-color: lightgray;
		border: 1px solid silver;
		color: black;
	}
	.action{
		text-align: center;
		align-items: center;
	}
	hr{
		margin: 0px 0px 3px 0px;
	}
	#dialog{
		margin: 0px 0px 0px 0px;
	}
	.search:focus {
	  width: 100%;
	}
	.search {
	 width: 150px;
	 transition: width 0.4s ease-in-out;
	 margin:0px 0px 5px 0px;
	 }
#text{
	font-size: 18px;
	font-weight: bold;
	color: darkgreen;
}	
#text1{
	font-size: 18px;
	font-weight: bold;
	color: brown;
}	
#start_date, #end_date, #days{
	font-size: 15px;
	font-stretch: expanded;
	color: gray;
	font-weight: bold;
}
</style> 
</head>
<body style="width: 100%;">
	<div class="card card-info card-outline">
	  <div class="card-header">
	  	<h2 class="card-title" id="text"><?php if($open_sessions == 1 ){echo "all closed";} ?></h2> 
	  </div>
	  <div class="card-body">

	  	<form id=dialog>
				<div class="row">
					<div class="col-sm-3">
						<div class="row">
	          	<div class=col-sm-5 style="margin-bottom: 10px;">
	              <label for="exampleInputEmail1">Start Date:</label>
	          	</div>
	          	<div class=col-sm-7 style="margin-bottom: 10px;">
	              <input type="date" class="form-control form-control-sm" name=start_date  value = <?php  echo $start_date;?> id="start_date" readonly></input>
	          	</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="row">
		 					<div class=col-sm-5 style="margin-bottom: 10px;">
	              <label for="exampleInputEmail1">End Date:</label>
	          	</div>
							<div class=col-sm-7 style="margin-bottom: 10px;">
								<input type="date" class="form-control form-control-sm" name=end_date id="end_date" value = <?php echo $end_date; ?> readonly></input>                       
				      </div>
	          </div>
					</div>
					<div class="col-sm-3">
						<div class="row">
		 					<div class=col-sm-7 style="margin-bottom: 10px;">
	              <label for="exampleInputEmail1">Number of days:</label>
	          	</div>
							<div class=col-sm-5 style="margin-bottom: 10px;">
								<input type="text" class="form-control form-control-sm" name="days" id="days" readonly></input>                       
				      </div>
	          </div>
					</div>
					<div class="col-sm-3">
							<button type="button" class="btn btn-secondary float-right" id="update" style=width:50%>Change</button>
					</div>
				</div>
			</form>
			<hr>
			<!-- <div> <input  type="text" id="search" class="form-control search" placeholder="Search..." ></div> -->
			<h3 id="text1">Find saved session logs</h3>
			<div class="table-responsive">
				<table class=" text-nowrap">
					<thead>
						<tr>
							<th>SN</th>
							<th>YEAR</th>
							<th>TERM</th>
							<th>START DATE</th>
							<th>END DATE</th>
							<th>STATUS</th>
							<th colspan="2" style="text-align: center;">ACTIONS</th>
						</tr>
					</thead>
					<tbody id=records>
						<?php 
						if($other_sessions->num_rows<1){
						?>
						<tr>
							<td colspan="7">No other mark entry sessions available</td>
						</tr>
					<?php } 
					else{ 
						$sn = 0;
						while($row = mysqli_fetch_assoc($other_sessions)){
							if($row['status']=="Open"){$icon = "lock-open"; $color = "orange";}else{$icon = "lock"; $color = "darkred";}
							?>
							<tr>
								<td><?php echo ++$sn; ?></td>
								<td><?php echo $row['year']; ?></td>
								<td><?php echo $row['term']; ?></td>
								<td><?php echo $row['start_date']; ?></td>
								<td><?php echo $row['end_date']; ?></td>
								<td><?php echo $row['status']; ?></td>
								<td><a href="#" class="action" id=<?php echo $row['sn']; ?> style="color:<?php echo $color; ?>">&nbsp&nbsp<i id="icon" class="fas fa-<?php echo $icon; ?> fa-lg"></i></a></td>
								<td><a href="#" class="delete" id=<?php echo $row['sn']; ?> style="color: red">&nbsp&nbsp<i id="icon" class="fas fa-trash fa-lg"></i></a></td>
							</tr>
						<?php }
					} ?>	
					</tbody>
				</table>
			</div>
		</div> 
	</div> 	
</body>	
<script type="text/javascript">
$(document).ready(function(){
	function button_status(){
		var btn = document.getElementById("update");
		btn_caption = btn.innerText;
		
		return btn_caption;
	}

	button_status()

	function myfunction(){
		var heading = document.getElementById("text");
		
		var start_date = new Date($("#start_date").val());
		var end_date = new Date($("#end_date").val());
		var millsecs = end_date.getTime() - start_date.getTime();
		var days = Math.round(Math.abs(millsecs/(1000*3600*24)));
		
		if(end_date < start_date){
			toastr.error("End date cannot be behind the start date.")
			heading.innerHTML="Mark entry session was closed "+days+" ago";
			$("#days").val("-"+days);
		}	
		else if(days == 0){
			toastr.error("Invalid date range")
			$("#days").val(days);
		}
		else{
			// toastr.info("Deadline set successfully.")
			heading.innerHTML="Mark entry session is set to end in "+days+" days time.";
			$("#days").val("+"+days);
		}

		var value = $("#days").val();
		return value;
	}
	myfunction();

	$("#search").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#records tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    })
  })

	function update_dates(){
		var duration = myfunction();
		if(duration > 0){
			var start_date = $("#start_date").val();
			var end_date = $("#end_date").val();
			$.ajax({
				url:'Update/update_mark_entry_deadline.php',
				method:'post',
				data:{start_date:start_date, end_date:end_date},
				success:function(data)
				{
					if(data == 'success'){
						toastr.info("Deadline set successfully");
						// $("#container").load("Modals/mark_entry_dates.php");
						location.reload();
					}
					else{
						toastr.error(data)
					}
				}
			})
		}
		else{	}
	}


	$("#update").click(function(){
		btn_caption = button_status()
		if(btn_caption == "Change"){
			$("#start_date").removeAttr("readonly");
			$("#end_date").removeAttr("readonly");
			var btn = document.getElementById("update");
			$("#update").addClass("btn-success");
			btn.innerText = "Update";
		}
		else{
			update_dates()
			location.reload()
		}
	})

	$("#start_date").change(function(){
		myfunction()
	})
	$("#end_date").change(function(){
		myfunction()
	})
	$('.action').click(function(){
		var sn = $(this).attr('id')
		$.ajax({
			url:'Requests/req_mark_entry_mode.php',
			method:'post',
			data:{sn:sn},
			success:function(data)
			{
				if(data == 'lock_success'){
					toastr.info("Locked successfully");
					// $("#container").load("Modals/mark_entry_dates.php");
					location.reload()
				}
				else if(data == 'unlock_success'){
					toastr.success("Unlocked successfully");
					// $("#container").load("Modals/mark_entry_dates.php");
					location.reload()
				}
				else{
					toastr.error(data)
				}
			}
		})
	})

	$('.delete').click(function(){
		var sn = $(this).attr('id')
		if(confirm('Are you sure you want to remove this class?')){
		$.ajax({
			url:'Delete/delete_mark_entry_session_log.php',
			method:'post',
			data:{sn:sn},
			success:function(data)
			{
				if(data == 'success'){
					toastr.info("Session log deleted successfully");
				}
				else{
					toastr.error(data)
				}
			}
		})
		$(this).parents('tr').remove();
	}
	})

})
</script>
</html>