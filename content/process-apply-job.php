<?php
include './includes/db.php';
date_default_timezone_set('Singapore');
$currDateTime = date("Y-m-d H:i:s");


/* GET SESSION DATA*/
session_start();
if(isset($_SESSION['SRIUsername']) && $_SESSION['SRIUsername'] != ""){
	$username = $_SESSION['SRIUsername'];
}
else{
	header("location: index.php");
}
extract($_POST);

$checkApplicationHistoryQuery = "SELECT * FROM application_history WHERE employee_username='$username' AND job_code='$jobPostCode'";
$checkApplicationHistory = mysql_query($checkApplicationHistoryQuery) or die(mysql_error()); 
$applicationCount = mysql_num_rows($checkApplicationHistory);

if($applicationCount === 0){
	$applicationHistoryQuery = "INSERT INTO application_history(employee_username, job_code, date_applied) VALUES('$username', '$jobPostCode', '$currDateTime')";
	$insertApplicationHistory = mysql_query($applicationHistoryQuery) or die(mysql_error());

	//add log activity
	$addLogQuery = "INSERT INTO activity_logs(date_made, username, action, reference_object) VALUES('$currDateTime', '$username', 'APPLY TO JOB POST', '$jobPostCode')";
	$addLog = mysql_query($addLogQuery) OR die(mysql_error());
}

?>