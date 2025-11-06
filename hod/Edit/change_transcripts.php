<?php
require('../../Database/config.php');


$sql = "update results_entry set " . $_POST["column"] . "='" . $_POST["value"] . "' where sn=" . $_POST["sn"];
if (mysqli_query($conn, $sql))
    echo "true";
else
    echo "false";
?>
 