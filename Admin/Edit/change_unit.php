<?php
require('../../Database/config.php');


$sql = "update units set " . $_POST["column"] . "='" . strip_tags($_POST["value"]) . "' where sn=" . $_POST["sn"];
if (mysqli_query($conn, $sql))
    echo "true";
else
    echo "false";
?>
 