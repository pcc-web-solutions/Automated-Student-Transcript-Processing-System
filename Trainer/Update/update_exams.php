<?php
require('../../Database/config.php');

$id = $_POST['id'];
$value = $_POST['value'];

$query = "UPDATE results_entry SET exam='$value' WHERE sn=$id";
mysqli_query($conn,$query) or die ('Bad query');

echo $id;
?>