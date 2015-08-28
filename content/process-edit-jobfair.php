<?php
 /** START FOR DB **/
include './includes/db.php';
/** END FOR DB**/

//GENERATE CURRENT DATE
date_default_timezone_set('Singapore');
$currDate = date("Y-m-d");
$currDateTime = date("Y-m-d H:i:s");

/* GET SESSION DATA*/
session_start();
$username = $_SESSION['SRIUsername'];

/* GET and FORMAT $_POST DATA*/
extract($_POST);
$response = 0;

//CLEAN POST VALUES
$title = trim(mysql_real_escape_string($title));
$establishmentName = trim(mysql_real_escape_string($establishmentName));
$street = trim(mysql_real_escape_string($street));
$jobFairDate = trim(mysql_real_escape_string($jobFairDate));
$numDays = trim(mysql_real_escape_string($numDays));
$timeStart = trim(mysql_real_escape_string($timeStart));
$timeStart = date("H:i", strtotime($timeStart));
$timeEnd = trim(mysql_real_escape_string($timeEnd));
$timeEnd = date("H:i", strtotime($timeEnd));

$numVacancies = trim(mysql_real_escape_string($numVacancies));
$websiteLink = trim(mysql_real_escape_string($websiteLink));

$firstName = trim(mysql_real_escape_string($firstName));
$middleName = trim(mysql_real_escape_string($middleName));
$lastName = trim(mysql_real_escape_string($lastName));

$mobile1 = trim(mysql_real_escape_string($mobile1));
$mobile2 = trim(mysql_real_escape_string($mobile2));
$mobile3 = trim(mysql_real_escape_string($mobile3));
$mobile = $mobile1. $mobile2. $mobile3;
$email = trim($email);
$landline1 = trim(mysql_real_escape_string($landline1));
$landline2 = trim(mysql_real_escape_string($landline2));
$landline3 = trim(mysql_real_escape_string($landline3));
$landline = $landline1 . $landline2 . $landline3;

//INSERT JOB FAIR
$selectJFairQuery = "SELECT id FROM job_fair WHERE code='$jobFairCode'";
$selectJFair = mysql_query($selectJFairQuery) or die(mysql_error());
$jFairCount = mysql_num_rows($selectJFair);

if(jFairCount == 0){
	$editJobFairQuery = "UPDATE job_fair SET title='$title', establishment_name='$establishmentName', street='$street', location_id='$location', date_scheduled='$jobFairDate', duration='$numDays', start_time='$timeStart', end_time='$timeEnd', num_vacancies='$numVacancies', website_link='$websiteLink', first_name='$firstName', middle_name='$middleName', last_name='$lastName', mobile='$mobile', email='$email', landline='$landline' WHERE code='$jobFairCode'";


	$editJobFair = mysql_query($editJobFairQuery) or die(mysql_error());
	$response = 1;
	//add log activity
	$addLogQuery = "INSERT INTO activity_logs(date_made, username, action, reference_object) VALUES('$currDateTime', '$username', 'EDIT JOB FAIR', '$jobFairCode')";
	$addLog = mysql_query($addLogQuery) OR die(mysql_error());
}

echo $response; //determines if successful or not

mysql_close($link_id);
?>