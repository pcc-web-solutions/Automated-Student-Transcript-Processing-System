<?php
require('config.php');


$sql = "update units set " . $_POST["column"] . "='" . $_POST["value"] . "' where sn=" . $_POST["sn"];
if (mysqli_query($conn, $sql))
    echo "true";
else
    echo "false";
?>
 