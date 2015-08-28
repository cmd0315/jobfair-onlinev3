<?php
include './includes/db.php';
$username = $_GET['username'];

$userStatus = getAcctInfo($username, "status");
//START SESSION TO REDIRECT TO APPROPRIATE LANDING PAGE
session_start();
$_SESSION['SRIUsername'] = $username;
if($userStatus == "Employee"){
	header("Location: browse-jobs.php");
}
else if($userStatus == "Employer") {
	header("Location: add-job-post.php");
}
else{
	header("Location: index.php");
}

?>