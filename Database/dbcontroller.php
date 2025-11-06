<?php
class DBController {
	private $host = "127.0.0.1:3308";
	private $user = "root";
	private $password = "pccws.2024";
	private $database = "transcript_system";
	private $conn;
	
	function __construct() {
		$this->conn = $this->connectDB();
	}
	
	function connectDB() {
		$conn = mysqli_connect($this->host,$this->user,$this->password,$this->database);
		return $conn;
	}
	
	function readData($query) {
		$result = mysqli_query($this->conn,$query);
		while($row=mysqli_fetch_array($result)) {
			$resultset[] = $row;
		}		
		if(!empty($resultset))
			foreach($resultset as $resultsets)
			return $resultsets[0];
	}
	
	function readData_array($query) {
		$result = mysqli_query($this->conn,$query);
		while($row=mysqli_fetch_assoc($result)) {
			$resultset[] = $row;
		}		
		if(!empty($resultset))
			return $resultset;
	}
	
	
	function numRows($query) {
		$result  = mysqli_query($this->conn,$query);
		$rowcount = mysqli_num_rows($result);
		return $rowcount;	
	}
	
	function executeInsert($query) {
        $result = mysqli_query($this->conn,$query);
        $insert_id = mysqli_insert_id($this->conn);
		return $insert_id;		
    }
	
		
	function executeUpdate($query) {
        $result = mysqli_query($this->conn,$query);
        if(!$result)
            return FALSE;
        else    
		    return TRUE;		
    }
	
	function executeDelete($query) {
        $result = mysqli_query($this->conn,$query);
        if(!$result)
            return FALSE;
        else    
		    return TRUE;		
    }
	
	function cleanData($data) {
		$data = mysqli_real_escape_string($this->conn,strip_tags($data));
		return $data;
	}
}
?>