<?php
session_start();
$_SESSION['choice'] = $_POST['choice'];
$_SESSION['course'] = $_POST['course'];
$_SESSION['class'] = $_POST['class'];
$_SESSION['unit'] = $_POST['unit'];
$_SESSION['supervisor'] = $_POST['supervisor'];
$_SESSION['ed'] = $_POST['exam_date'];
$_SESSION['not'] = $_POST['no_of_trainees'];

?>