<?php
include './includes/db.php';
$username = $_GET['username'];
$status = getAcctInfo($username, "status");

//start session
session_start();
$_SESSION['SRIUsername'] = $username;
//set which landing page to go				
if($status == "Employee"){
	header("location: ./applicant-sitemap.php");

}
else{
	header("location: ./employer-sitemap.php");

}
?>