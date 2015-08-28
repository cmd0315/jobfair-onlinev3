<?php
include "./includes/db.php";

$email = $_GET['user'];
$_SESSION['SRIUsername'] = $email;
$status = getAcctInfo($email, "status");

if($status === "Employee"){
	header("Location: browse-job.php");
}
else
	header("Location: add-job-post.php");


?>