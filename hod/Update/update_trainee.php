<?php
require('../../Database/config.php');

if(isset($_POST['service']) && isset($_POST['service_date']))
{
	$service=$_POST['service'];
	$date=$_POST['service_date'];
	
$sql = "update tithe set service='$service', date='$date' where service is null";
$results=mysqli_query($conn, $sql);
}

if ($results)
{
    echo "Record saved";
}
else
{
    echo "Error";
}


?>
