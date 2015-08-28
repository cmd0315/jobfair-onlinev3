<?php
include './includes/db.php';

//GET JOB POST CODE
$jobPostCode = $_GET['code'];
$status = $_GET['status'];
$checkStatusQuery = "";
$response = "";
//get job post position
// $getPositionQuery = "SELECT job_position FROM job_post WHERE code='$jobPostCode' ";
// $getPosition = mysql_query($getPositionQuery) or die(mysql_error());
// $jobPosition = mysql_result($getPosition, 0);
$jobPosition = getJobPost($jobPostCode, "job-pos-name");
//get job post status
if($status == "stopped"){
	$checkStatusQuery  = "SELECT status FROM location_posts WHERE post_code='$jobPostCode' ";
}
else if($status == "removed"){
	$checkStatusQuery  = "SELECT status2 FROM job_post WHERE code='$jobPostCode' ";
}

$checkStatus = mysql_query($checkStatusQuery) or die(mysql_error());
$statResponse = mysql_result($checkStatus, 0);

$response = $jobPosition . " ," . $statResponse;

echo $response;
?>