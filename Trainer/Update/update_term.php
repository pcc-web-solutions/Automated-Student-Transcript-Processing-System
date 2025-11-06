<?php

require('../../Database/config.php');
	
?>
<html>
<head>
<style>
.row{margin-bottom:10px}

input['type=text']{height:10px}
</style>
</head>
<body>
<div class=row>
<div class="mx-auto">
<div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Update session</h3>
              </div>
              <form class="form-horizontal" id=dialog>
                <div class="card-body">
				
	<div class=row>            
				 
				 
             <div class=col-12>
                    <select class="form-control" name=year id=year placeholder="Year" >
					<option value="">--Choose Year--</option>
					<?php
					$date=date('Y');
					for($i=$date-5; $i<=$date-1; $i++){
						$p = $i+1;
						echo "<option value=".$i.'/'.$p.">$i/$p</option>";
					}
					?>
					</select>
              </div>
                		 
				 
			</div>
		 <div class=row>
				 		
				 <div class=col-12>
				<select class="form-control" name=term id=term placeholder="Term" >
				<option value="">--Choose Term--</option>
				<option value="I">Term 1</option>
				<option value="II">Term 2</option>
				<option value="III">Term 3</option>
				</select>
                                     
				       </div>
                </div>
			
				 </div>
       
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="button" class="btn btn-info float-right" id=ok style=width:50%>OK</button>
                </div>
                <!-- /.card-footer -->
              </form>
            </div>
			</div>
			</div>
			  	
		</body>
		
			<script>
			
			$(document).ready(function()
			{
			$('#ok').click(function()
			{
			$.ajax({
			url:'Sessions/change_session.php',
			method:'post',
			data:$('#dialog').serialize(),
			
			success:function(data)
			{
			alert (data);	
				}
			})
			})
			})
		
		</script>
			</html>