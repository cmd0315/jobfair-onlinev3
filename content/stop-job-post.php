<?php
require "./includes/db.php";
$jobPostCode = $_GET['code'];

//GET SESSION VARIABLLES
session_start();
if(isset($_SESSION['SRIUsername']) && $_SESSION['SRIUsername'] != ""){
	$sessionUsername = $_SESSION['SRIUsername'];
}
else{
	header("Location: index.php");
}

//get currentdate
date_default_timezone_set('Singapore');
$currDateTime = date("Y-m-d H:i:s");

//queries
$updateLocJobPostQuery = "UPDATE location_posts SET status='1' WHERE post_code='$jobPostCode' ";
$updateLocJobPost = mysql_query($updateLocJobPostQuery) or die(mysql_error());

$updateJobPostQuery = "UPDATE job_post SET status1='1' WHERE code='$jobPostCode' ";
$updateJobPost = mysql_query($updateJobPostQuery) or die(mysql_error());


//check job location for empty association
$jobLocationIdQuery = "SELECT * FROM job_location";
$getJobLocationId = mysql_query($jobLocationIdQuery) or die(mysql_query());
while($jobLocationIdData = mysql_fetch_assoc($getJobLocationId)){
	$jL = $jobLocationIdData['location_id'];
	$locPosts = getAvailableLocPosts();
	if(!in_array($jL, $locPosts)){
		$deleteJobLocationQuery = "DELETE FROM job_location WHERE location_id='$jL' ";
		$deleteJob = mysql_query($deleteJobLocationQuery) or die(mysql_error());
	}
}

//add log activity
$addLogQuery = "INSERT INTO activity_logs(date_made, username, action, reference_object) VALUES('$currDateTime', '$sessionUsername', 'CLOSE JOB POST', '$jobPostCode')";
$addLog = mysql_query($addLogQuery) OR die(mysql_error());


function getAvailableLocPosts(){
	$getLocationPostsQuery = "SELECT * FROM location_posts WHERE status='0' ";
	$getLocationPosts = mysql_query($getLocationPostsQuery) or die(mysql_error());
	$lPArray = array();

	while($locationPostsData = mysql_fetch_assoc($getLocationPosts)){
		$lP = $locationPostsData['location_id'];
		array_push($lPArray, $lP);
	}
	return $lPArray;
}

?>