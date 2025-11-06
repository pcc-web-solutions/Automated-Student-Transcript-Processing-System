<?php
session_start();
				
$_SESSION['nop'] = $_POST['entries'];
if(empty($_SESSION['nop'])){echo "Please specify number of trainees."; exit();}
else{echo "Session success";}
?>