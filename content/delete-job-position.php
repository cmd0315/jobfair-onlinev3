<?php
include './includes/db.php';
$jobPositionName = $_GET['jobPositionName'];
$jobPositionID = getJobPositionID($jobPositionName);

/* GET SESSION DATA*/
session_start();
if(isset($_SESSION['SRIUsername']) && $_SESSION['SRIUsername'] != ""){
	$username = $_SESSION['SRIUsername'];
}
else{
	header("Location: index.php");
}


//get currentdate
date_default_timezone_set('Singapore');
$currDateTime = date("Y-m-d H:i:s");

$updateJobPositionQuery = "UPDATE position SET status='1', date_added='$currDateTime' WHERE id='$jobPositionID'";
$updateJobPosition = mysql_query($updateJobPositionQuery) or die(mysql_error());

$updateJobPostQuery = "UPDATE job_post SET status1='1', job_closedate='$currDateTime' WHERE job_position='$jobPositionID'"; 
$updateJobPost = mysql_query($updateJobPostQuery) or die(mysql_error());

//add log activity
$addLogQuery = "INSERT INTO activity_logs(date_made, username, action, reference_object) VALUES('$currDateTime', '$username', 'DELETE JOB POSITION', '$jobPositionName')";
$addLog = mysql_query($addLogQuery) OR die(mysql_error());

echo "successful";
?>