<?php
include './includes/db.php';

/* GET SESSION DATA*/
session_start();
$username = $_SESSION['SRIUsername'];
extract($_POST);

$answer = changePassword($username, md5($newPassword));
?>