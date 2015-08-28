<?php
 /** START FOR DB **/
include './includes/db.php';
/** END FOR DB**/

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

if($openDate == ""){
	$openDate = $currDate;
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

//FORMAT HEIGHT OPTIONS
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
	$eMaxAge = 18;
}



//FOR JOB LOCATION
$location = explode(",", $location);
$city = trim($location[0]);
$areaName = trim($location[1]);
$getLocationIdQuery = "SELECT location_id FROM location WHERE city='$city' AND area_name='$areaName' ";
$getLocationId = mysql_query($getLocationIdQuery) or die(mysql_error());
$locationId = mysql_result($getLocationId, 0);

//FOR JOB POSITION
$position = getJobPositionID($position);


//CLEAN JOB POST DESCRIPTIOM
$jobDesc = mysql_real_escape_string($jobDesc);

/*-- START ADD ROW to JOB_POST table --*/
$addJobPostQuery = "INSERT INTO `job_post`(code, employer_username, date_posted, location_id, street, job_position, num_vacancies, job_desc, e_sex, e_civil_status, e_min_age, e_max_age, e_height, e_weight, e_educ_attainment, job_opendate, job_closedate) VALUES ('$jobPostCode', '$username', '$currDateTime', '$locationId', '$street', '$position', '$numVacancies', '$jobDesc', '$eSex', '$eCivilStatus', '$eMinAge', '$eMaxAge', '$eTotalHeight', '$eWeight', '$eEducAttainment', '$openDate', '$closeDate')";
$insertJobPost = mysql_query($addJobPostQuery) or die(mysql_error());

/*-- END ADD ROW to JOB_POST table --*/

//for  OTHER REQUIREMENTS
for ($i=1; $i<50; $i++) {
	$otherReq = $_POST['otherReq' . $i];
	$otherReq = mysql_real_escape_string($otherReq); //clean other requirements

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
$addLocationPostQuery = "INSERT INTO location_posts(location_id, post_code, date_added) VALUES('$locationId', '$jobPostCode', '$currDateTime')";
$addLocationPost = mysql_query($addLocationPostQuery) or die(mysql_query());

//add log activity
$addLogQuery = "INSERT INTO activity_logs(date_made, username, action, reference_object) VALUES('$currDateTime', '$username', 'ADD JOB POST', '$jobPostCode')";
$addLog = mysql_query($addLogQuery) OR die(mysql_error());

mysql_close($link_id);
?>