<?php 
	switch (connection_status()){
		case CONNECTION_NORMAL:
			$response = "Connection in a normal state";
			break;
			
		case CONNECTION_ABORTED:
			$response = "Connection aborted";
			break;
			
		case CONNECTION_TIMEOUT:
			$response = "Connection timeout";
			break;
		case (CONNECTION_ABORTED && CONNECTION_TIMEOUT):
			$response = "Connection aborted and timeout";
			break;
			
		default:
			$response = "Undefined error connection";
			break;
	}
	echo $response;
?>