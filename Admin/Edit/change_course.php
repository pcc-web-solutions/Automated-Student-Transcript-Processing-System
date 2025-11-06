<?php
require('../../Database/config.php');


$sql = "UPDATE courses SET " . $_POST["column"] . "='" . strip_tags($_POST["value"]) . "' WHERE sn = " . $_POST["sn"];
if (mysqli_query($conn, $sql))
    echo "true";
else
    echo "false";
?>
 