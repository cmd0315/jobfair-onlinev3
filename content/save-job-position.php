<?php
include './includes/db.php';
$jobPositionID = $_GET['jobPositionID'];
$oldJobPositionName = getJobPositionName($jobPositionID);
$jobPosition = $_GET['jobPosition'];
$jobPosition = strtoupper($jobPosition);

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

$checkPositionExistsQuery = "SELECT * FROM position WHERE name='$jobPosition' AND id!='$jobPositionID' AND status='0'";
$checkPositionExists = mysql_query($checkPositionExistsQuery) or die(mysql_error());
$positionExistsRowNum = mysql_num_rows($checkPositionExists);

if($positionExistsRowNum === 0){
	$updateJobPositionQuery = "UPDATE position SET name='$jobPosition', date_added='$currDateTime' WHERE id='$jobPositionID'";
	$updateJobPosition = mysql_query($updateJobPositionQuery) or die(mysql_error());
	$result = 1;

	//add log activity
	$addLogQuery = "INSERT INTO activity_logs(date_made, username, action, reference_object) VALUES('$currDateTime', '$username', 'EDIT JOB POSITION', '$jobPosition')";
	$addLog = mysql_query($addLogQuery) OR die(mysql_error());
}
else{
	$result = 0;
}


echo $result . "-" . $oldJobPositionName;
?>