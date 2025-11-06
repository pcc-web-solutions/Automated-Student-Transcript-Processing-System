<?php
	include('../../Database/config.php');

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

	$adms = array();
	$selected_adms = $conn->query("SELECT distinct(results_entry.adm) as adm, trainees.name, trainees.class, term, exam_year from results_entry INNER JOIN trainees ON trainees.adm = results_entry.adm where trainees.status = '1' and term='$term' and exam_year='$year' ORDER BY adm ASC");
	
?>

<!DOCTYPE />
<html>
	<head>
		<style>
			tr button{
				margin: 0px;
				width: 100%;
				padding: 2px;
			}
			
			.search:focus {
				width: 100%;
			}
			.search {
				width: 150px;
				transition: width 0.4s ease-in-out;
				margin-bottom:0px
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
				background-color: lightcyan;
			}
			button.form-control-sm{
				padding-top: 0px;
			}
		</style>
	</head>
	<body>
		<div class="card card-warning">
			<div class="card-header">
				<h4 class="card-title">INDIVIDUAL TRANSCRIPTS</h4>
				<!-- <button type="button" class="btn btn-info float-right" id="download_all"><i class="fa fa-download"></i>&nbsp Download All</button> -->
			</div>
			<div class="card-body">
				<div> <input  type="Text" id="search" class="form-control search" placeholder="Type to search..." ></input></div>
				<br>
				<form class="table-responsive">
					<table class="table-striped text-nowrap">
						<thead>
							<tr>
								<th>SN</th>
								<th>ADM NO</th>
								<th>TRAINEE NAME</th>
								<th>CLASS</th>
								<th colspan=2>PDF FILE</th>
							</tr>
						</thead>
						<tbody id=records>
						<?php
							if($selected_adms->num_rows>0){
								while($rows=mysqli_fetch_assoc($selected_adms)){
									$adms[] = $rows;
								} 
								$record_count = 0;
								FOREACH($adms AS $trainee){ ?>
									<tr>
										<td><?php echo ++$record_count; ?></td>
										<td><?php echo strtoupper($trainee['adm']); ?></td>
										<td><?php echo strtoupper($trainee['name']); ?></td>
										<td><?php echo strtoupper($trainee['class']); ?></td>
										<td><button name="view" class="btn btn-info form-control-sm" id=<?php echo $trainee['adm']; ?> ><i class="fa fa-eye"></i>&nbsp View</button></td>
										<td><button name="download" class="btn btn-primary form-control-sm" id=<?php echo $trainee['adm']; ?> ><i class="fa fa-download"></i>&nbsp Download</button></td>
									</tr>
									<?php
								}
							}
							else{?>
								<tr>
									<td colspan=5><h6><center><?php echo strtoupper("No mark entries for ".$year." ".$term);?></center></h6></td>
								</tr>
							<?php		
							}
							?>							
						</tbody>
					</table>
				</form>	
			</div>
			<div class="card-footer">
			</div>
		</div>
		
	</body>
</html>

<script type="text/javascript">
$(document).ready(function(){
	
	$("#search").on("keyup", function() {
		var value = $(this).val().toLowerCase();
		
		$("#records tr").filter(function() {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		})
	})
	
	$('#download_all').click(function(e){
		e.preventDefault();
		alert('This action will download all course transcripts');
	});

	$('button[name=view]').click(function(e){
		e.preventDefault();
		var adm_no = $(this).attr('id');
		var download_status = 'true';
		$.ajax({
			url: 'Sessions/sessions.php',
			method: 'post',
			data: {adm_no:adm_no,download_status:download_status},
			success: function(data){
				if(data == 'Success'){
					$('#container').html('<iframe id="ifrm" style="width: 100%; height: 100vh; border: 1px solid blue;"></iframe>');
					$("#ifrm").attr("src", "Reports/tcpdf-individual_transcript.php");
				}
				else{
					alert('Problem getting the trainee admission number');
				}
			}
		})
	});
	
	$('button[name=download]').click(function(e){
		e.preventDefault();
		var adm_no = $(this).attr('id');
		var download_status = 'true';
		$.ajax({
			url: 'Sessions/sessions.php',
			method: 'post',
			data: {adm_no:adm_no,download_status:download_status},
			success: function(data){
				if(data == 'Success'){
					location.replace("Reports/tcpdf-individual_transcript-d.php");
				}
				else{
					alert('Problem getting the trainee admission number');
				}
			}
		})
	});
	
});

</script>