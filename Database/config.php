<?php

$server='127.0.0.1:3308';
$database='transcript_system';
$user='root';
$pw='pccws.2024';

$conn=mysqli_connect($server, $user, $pw, $database);
$classes=$conn->query("SET GLOBAL sql_mode = REPLACE(@@GLOBAL.sql_mode, 'ONLY_FULL_GROUP_BY', '')");
?>