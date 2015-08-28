<?php
include './includes/db.php';
$oldJobPosition = $_GET['oldJobPosition'];
$jobPosition = $_GET['jobPosition'];
$jobPositionUp = strtoupper($jobPosition);

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

$result = 0;

$checkPositionExistsQuery = "SELECT * FROM position WHERE name='$jobPositionUp'";
$checkPositionExists = mysql_query($checkPositionExistsQuery) or die(mysql_error());
$positionExistsRowNum = mysql_num_rows($checkPositionExists);

if($positionExistsRowNum === 0){
	$addJobPositionQuery = "INSERT INTO position (name, date_added) VALUES('$jobPositionUp', '$currDateTime')";
	$addJobPosition = mysql_query($addJobPositionQuery) or die(mysql_error());
	
	$newJobPositionId = getJobPositionID($jobPositionUp);
	
	$updateInterestRequestQuery = "UPDATE interest SET position_id='$newJobPositionId', status='0' WHERE position_id='$oldJobPosition' ";
	$updateInterestRequest = mysql_query($updateInterestRequestQuery) or die(mysql_error());
	$result = $newJobPositionId;
	
	$addLogQuery = "INSERT INTO activity_logs(date_made, username, action, reference_object, status) VALUES('$currDateTime', '$username', 'ADD JOB POSITION REQUEST', '$jobPositionUp', '1')";
}
else{
	$result = 0;
	$addLogQuery = "INSERT INTO activity_logs(date_made, username, action, reference_object, status) VALUES('$currDateTime', '$username', 'ADD JOB POSITION REQUEST', '$jobPositionUp', '0')";
}

$addLog = mysql_query($addLogQuery) OR die(mysql_error()); //add log activity
echo $result;

?>