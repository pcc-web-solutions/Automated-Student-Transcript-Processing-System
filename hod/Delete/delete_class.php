<?php
	if(isset($_POST['record_no']))
	{
		$id = $_POST['record_no'];
		
		require('../../Database/config.php');
		
		$deleteclass = $conn->query("DELETE FROM classes WHERE class_id = $id");

		if($deleteclass)
		{
			echo "Class deleted";
		}	
	}
    else{
        echo "Problem submitting the class identity number";
    }
?>