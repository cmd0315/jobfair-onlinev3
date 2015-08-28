<?php
require './includes/db.php';

//GENERATE CURRENT DATE
date_default_timezone_set('Singapore');
$currDate = date("Y-m-d");
$currDateTime = date("Y-m-d H:i:s");
$tomorrow = strtotime('+1 days');
$tomorrow =  date('Y-m-d', $tomorrow);
$nextMonth = strtotime('+30 days');
$nextMonth =  date('Y-m-d', $nextMonth);
$next2Months = strtotime('+60 days');
$next2Months =  date('Y-m-d', $next2Months);
$next3Months = strtotime('+90 days');
$next3Months =  date('Y-m-d', $next3Months);

/* GET SESSION DATA*/
session_start();
$username = $_SESSION['SRIUsername'];

/* GET and FORMAT $_POST DATA*/
extract($_POST);
$street = trim(mysql_real_escape_string($street));
$jobDesc = trim(mysql_real_escape_string($jobDesc));
/* GET $_POST DATA*/

/*START FORMAT EXPIRATION DATES*/
if($openDate == "" || $openDate == "0000-00-00"){
	$openDate = $currDate;
}

if($expiration == ""){
	$closeDate = $next3Months;
}

if($expiration === "30"){
	$closeDate = $nextMonth;
}
else if($expiration === "60"){
	$closeDate = $next2Months;
}
else if($expiration === "90"){
	$closeDate = $next3Months;
}
else
	$closeDate = $nextMonth;
/*END FORMAT EXPIRATION DATES*/


/*START FORMAT HEIGHT OPTIONS*/
if($eHeightFt != ""){
 $eTotalHeight = $eHeightFt . "'" . $eHeightIn . '"';
 $eTotalHeight = mysql_real_escape_string($eTotalHeight);
}
else{
	$eTotalHeight = "";
}

if($eMinAge <= 0 || $eMinAge === ""){
	$eMinAge = 18;
}

if($eMaxAge <= 0 || $eMaxAge === ""){
	$eMaxAge =  60;
}
/*END FORMAT HEIGHT OPTIONS*/

$position = getJobPositionID($position); //FOR JOB POSITION

/*START FORMAT JOB LOCATION*/
$location = explode(",", $location);
$city = trim($location[0]);
$areaName = trim($location[1]);
$getLocationIdQuery = "SELECT location_id FROM location WHERE city='$city' AND area_name='$areaName' ";
$getLocationId = mysql_query($getLocationIdQuery) or die(mysql_error());
$locationId = mysql_result($getLocationId, 0);
/*END FORMAT JOB LOCATION*/


/*-- START ADD ROW to JOB_POST table --*/
$updateJobPostQuery = "UPDATE `job_post` SET date_posted='$currDateTime', location_id='$locationId', street='$street',  job_position='$position', num_vacancies='$numVacancies', job_desc='$jobDesc', e_sex= '$eSex', e_civil_status='$eCivilStatus', e_min_age='$eMinAge', e_max_age='$eMaxAge', e_height='$eTotalHeight', e_weight='$eWeight', e_educ_attainment='$eEducAttainment', job_opendate='$openDate', job_closedate='$closeDate' WHERE code='$jobPostCode' ";
$updateJobPost = mysql_query($updateJobPostQuery) or die(mysql_error());
/*-- END ADD ROW to JOB_POST table --*/

/*START FORMAT OTHER REQUIREMENTS*/
$deleteAllReqQuery = "DELETE FROM requirements WHERE post_code='$jobPostCode'";
$deleteAllReq = mysql_query($deleteAllReqQuery) or die(mysql_error());

for ($i=1; $i<50; $i++) {
	$otherReq = $_POST['otherReq' . $i];
	if($otherReq != "") {
		$requirementQuery = "INSERT INTO requirements(post_code, req_name, date_added) VALUES('$jobPostCode', '$otherReq', '$currDateTime')";
		$insertRequirement = mysql_query($requirementQuery) or die(mysql_error());
	}
}

$locationIdExistsQuery = "SELECT location_id FROM job_location WHERE location_id='$locationId' ";
$checkLocationIdExists = mysql_query($locationIdExistsQuery) or die(mysql_error());
$locationIdCount = mysql_num_rows($checkLocationIdExists);

if($locationIdCount == 0) {
	$addJobLocationQuery = "INSERT INTO job_location(location_id, date_changed) VALUES('$locationId', '$currDateTime')";
	$addJobLocation = mysql_query($addJobLocationQuery) or die(mysql_error());
}
/*END FORMAT OTHER REQUIREMENTS*/

//for location posts
$updateLocationPostQuery = "UPDATE location_posts SET location_id='$locationId', date_added='$currDateTime' WHERE post_code='$jobPostCode' ";
$updateLocationPost = mysql_query($updateLocationPostQuery) or die(mysql_query());

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

/*FUNCTION TO GET ALL AVAILABLE POSTS IN ALL LOCATIONS*/
function getAvailableLocPosts(){
	$getLocationPostsQuery = "SELECT * FROM location_posts";
	$getLocationPosts = mysql_query($getLocationPostsQuery) or die(mysql_error());
	$lPArray = array();

	while($locationPostsData = mysql_fetch_assoc($getLocationPosts)){
		$lP = $locationPostsData['location_id'];
		array_push($lPArray, $lP);
	}
	return $lPArray;
}

?>