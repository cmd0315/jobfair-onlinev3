<?php
require './includes/db.php';

$username=$_GET['username'];
$status = getAcctInfo($username, "status");
$jobPostCode=$_GET['jobPostCode'];
$answer = "";

if(isset($jobPostCode)){
	session_start();
	$_SESSION['SRIJobPostCode'] = $jobPostCode;
	if($username=="none"){
		$answer = "Login";
	}
	else{
		if($status == "Employee"){
			$answer = "Apply Job";
		}
		else
			$answer = "Invalid";
	}
}
else{
	$answer = "Error";
}
echo $answer;
?>