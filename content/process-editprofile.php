<?php
  /** START FOR DB **/
include './includes/connect.inc.php';
$link_id = db_connect($dbname);
if(!$link_id) error_message(sql_error());
/** END FOR DB**/

//get currentdate
date_default_timezone_set('Singapore');
$currDate = date("Y-m-d");
$currDateTime = date("Y-m-d H:i:s");

 //extract form element values
extract($_POST);

$area = explode(",", $area1);
$city1 = trim($area[0]);
$province1 = trim($area[1]);
$mobile = $mobile1. $mobile2. $mobile3;
$landline = $landline1 . $landline2 . $landline3;

 //change profile picture path 
if($status == "Employee"){
	$acctType = 2;
	$path = "uploads/profile_pictures/";
 }
 else if($status == "Employer" || $status == "SRI Branch Manager"){
	$acctType = 1;
	$path = "uploads/company_logos/";
 }
 else
	$acctType = 0;
 
 /* CLEAN INPUTS */
$firstName = trim(mysql_real_escape_string($firstName));
$middleName = trim(mysql_real_escape_string($middleName));
$lastName = trim(mysql_real_escape_string($lastName));
$street1 = trim(mysql_real_escape_string($street1));
$mobile1 = trim(mysql_real_escape_string($mobile1));
$mobile2 = trim(mysql_real_escape_string($mobile2));
$mobile3 = trim(mysql_real_escape_string($mobile3));
$mobile = $mobile1. $mobile2. $mobile3;
$email = trim($email);
$landline1 = trim(mysql_real_escape_string($landline1));
$landline2 = trim(mysql_real_escape_string($landline2));
$landline3 = trim(mysql_real_escape_string($landline3));
$landline = $landline1 . $landline2 . $landline3;

if($acctType == 2){//for employee
	$birthPlace = trim(mysql_real_escape_string($birthPlace));
	$weight = trim(mysql_real_escape_string($weight));
	$numChildren = trim(mysql_real_escape_string($numChildren));
	$hs = trim(mysql_real_escape_string($hs));
	$college =  trim(mysql_real_escape_string($college));
	$collegeDegree =  trim(mysql_real_escape_string($collegeDegree));
	$vocationalSchool =  trim(mysql_real_escape_string($vocationalSchool));
	$vocationalDegree =  trim(mysql_real_escape_string($vocationalDegree));
}
else if($acctType == 1){//for employer
	$companyName =  trim(mysql_real_escape_string($companyName));
	$companyDesc =  trim(mysql_real_escape_string($companyDesc));
	$cpPosition =  trim(mysql_real_escape_string($cpPosition));
	$cpDept =  trim(mysql_real_escape_string($cpDept));
}

//FORMAT HEIGHT OPTIONS
if($heightFt != ""){
 $totalHeight = $heightFt . "'" . $heightIn . '"';
 $totalHeight = mysql_real_escape_string($totalHeight);
}
else{
	$totalHeight = "";
}

//FORMAT WEIGHT OPTIONS
define('POUND_CONVERSION', '2.2046'); // constant for lbs to kg. conversion
if($weightType === "kgs."){
	$weight = $weight*POUND_CONVERSION;
}

//FORMAT WITH HONORS OPTION
if ($hsWithHonors == ""){
	$hsWithHonors = 0;
}

if ($collegeWithHonors == ""){
	$collegeWithHonors = 0;
}

if ($vocationalWithHonors == ""){
	$vocationalWithHonors = 0;
}

/*START edit row to employee/employer/web_admin table */
if($acctType == 2){
	//edit row to employee table
	$userDataQuery = "UPDATE employee SET first_name='$firstName', middle_name='$middleName', last_name='$lastName', street1='$street1', city1='$city1', province1='$province1', birth_date='$birthDate', birth_place='$birthPlace', height='$totalHeight', weight='$weight', civil_status='$civilStatus', num_children='$numChildren', religion='$religion', mobile_num='$mobile', email='$email', tel_num='$landline', hs_name='$hs', hs_end_yr='$hsEndYr', hs_educ='$hsEduc', college_name='$college', college_degree='$collegeDegree', college_end_yr='$collegeEndYr', college_educ='$collegeEduc', voc_school_name='$vocationalSchool', voc_course='$vocationalDegree', voc_end_yr='$vocationalEndYr', voc_educ='$vocationalEduc'  WHERE username='$username' "; 
}
 else if($acctType == 1){
	 //edit row to employer table
	$userDataQuery = "UPDATE employer SET company_name='$companyName', company_desc='$companyDesc', street1='$street1', city1='$city1', province1='$province1', first_name='$firstName', middle_name='$middleName', last_name='$lastName', mobile_num='$mobile', email='$email', tel_num='$landline', position='$cpPosition', department='$cpDept' WHERE username='$username' "; 
 }
 else{
 	 //edit row to web_admin table
	$userDataQuery = "UPDATE web_admin SET first_name='$firstName', middle_name='$middleName', last_name='$lastName', street='$street1', city='$city1', province='$province1', mobile_num='$mobile', email='$email' WHERE username='$username' ";
 }
 
 $updateUserData = mysql_query($userDataQuery) or die(mysql_error()); 
 /* END ADD USER DATA*/
 
//for PROFILE PIC Upload
$valid_formats = array("jpg", "JPG", "png", "PNG", "gif", "GIF", "bmp", "BMP","jpeg", "JPEG");
if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
	$name = $_FILES['photoimg']['name'];
	$size = $_FILES['photoimg']['size'];
	if(strlen($name)) {
		list($txt, $ext) = explode(".", $name);
		if(in_array($ext,$valid_formats)) {
			// Image size max 5 MB
			if($size<(5024*5024)) {
				$actual_image_name = time()."-".$username.".".$ext;
				$tmp = $_FILES['photoimg']['tmp_name'];
				if(move_uploaded_file($tmp, $path.$actual_image_name)) {
					if($acctType == 2){ //if employee
						$updatePicQuery = "UPDATE employee SET profile_pic='$actual_image_name' WHERE username='$username' ";
					}
					else if($acctType == 1){ //if employer
						$updatePicQuery = "UPDATE employer SET profile_pic='$actual_image_name' WHERE username='$username' ";
					}
					$updatePIc = mysql_query($updatePicQuery);
					//echo "<img src='uploads/profile_pictures/".$actual_image_name."' class='preview' id='prev-picture' name='prev-picture' style='border:1px dashed gray; width:105px; height:104px; margin-right:5px;'>";
				}
				else
					$errorMsg = "failed";
			}
			else
				$errorMsg = "Image file size max is 5 MB"; 
		}
		else
			$errorMsg = "Invalid file format.."; 
	}
}

//for OLD WORK HISTORY TABLE OPERATIONS
for ($i=0; $i<$historyNum; $i++) {
	$coName = $_POST['coNameOld' . $i];
	$position = $_POST['positionOld' . $i];
	$startDM = $_POST['startMonthOld' . $i];
	$endDM = $_POST['endMonthOld' . $i];
	
	$startDM = explode("-", $startDM);
	$startYear = $startDM[0];
	$startMonth = $startDM[1];
	
	$endDM = explode("-", $endDM);
	$endYear = $endDM[0];
	$endMonth = $endDM[1];
	
	$startDM = date("Y-m-d", mktime(0, 0, 0, $startMonth, 1, $startYear));
	$endDM = date("Y-m-d", mktime(0, 0, 0, $endMonth, 1, $endYear));
	
	$rowExistsQuery = "SELECT id FROM work_history WHERE company_name='$coName' AND position='$position' AND employee_username='$username' ";
	$checkIfRowExists = mysql_query($rowExistsQuery);
	$rowExistingNum = mysql_num_rows($checkIfRowExists);
	if($rowExistingNum > 0){
		// $workHistoryQuery = "UPDATE work_history SET work_start='$startDM', work_end='$endDM', date_added='$currDateTime' WHERE company_name='$coName' AND position='$position' ";
		// $updateWorkHistory = mysql_query($workHistoryQuery);
		$workHistoryQuery = "DELETE FROM work_history WHERE company_name='$coName' AND position='$position' AND employee_username='$username'";
		$deleteWorkHistory = mysql_query($workHistoryQuery);
	}
	if($coName != "" && $position !="" && $startMonth != "" && $endMonth != "") {
		$workHistoryQuery2 = "INSERT INTO work_history(employee_username, company_name, position, work_start, work_end, date_added) VALUES('$username', '$coName', '$position', '$startDM', '$endDM', '$currDateTime')";
		$insertWorkHistory = mysql_query($workHistoryQuery2);
	}
}

//for WORK HISTORY TABLE OPERATIONS
for ($i=1; $i<50; $i++) {
	$coName = $_POST['coName' . $i];
	$position = $_POST['position' . $i];
	$startDM = $_POST['startMonth' . $i];
	$endDM = $_POST['endMonth' . $i];
	
	$startDM = explode("-", $startDM);
	$startYear = $startDM[0];
	$startMonth = $startDM[1];
	
	$endDM = explode("-", $endDM);
	$endYear = $endDM[0];
	$endMonth = $endDM[1];
	
	$startDM = date("Y-m-d", mktime(0, 0, 0, $startMonth, 1, $startYear));
	$endDM = date("Y-m-d", mktime(0, 0, 0, $endMonth, 1, $endYear));

	if($coName != "" && $position !="" && $startMonth != "" && $endMonth != "") {
		$workHistoryQuery = "INSERT INTO work_history(employee_username, company_name, position, work_start, work_end, date_added) VALUES('$username', '$coName', '$position', '$startDM', '$endDM', '$currDateTime')";
		$insertWorkHistory = mysql_query($workHistoryQuery);
	}
}

/** FOR INTEREST table **/
$employeeInterestQuery = "SELECT * FROM interest WHERE employee_username='$username'";
$getEmployeeInterests = mysql_query($employeeInterestQuery) or die(mysql_error());
$interestArray = array();
while($interestRows = mysql_fetch_assoc($getEmployeeInterests)){
	$employeeInterest = $interestRows['position_id'];
	array_push($interestArray, $employeeInterest);
}
//delete interest not anymore on the list
foreach($interestArray as $empInterest){
	if(in_array($empInterest, $_POST['classification']) === false){
		$deleteInterestQuery = "DELETE FROM interest WHERE position_id='$empInterest' AND employee_username='$username'";
		$deleteInterest = mysql_query($deleteInterestQuery) or die(mysql_error());
	}
}
//add new interests
foreach ($_POST['classification'] as $classifications) {
	if(in_array($classifications, $interestArray) === false){
		$addInterestQuery = "INSERT INTO interest(employee_username, position_id, date_added) VALUES('$username', '$classifications', '$currDateTime')";
		$addInterest = mysql_query($addInterestQuery) or die(mysql_error());
	}
}

//add log activity
$addLogQuery = "INSERT INTO activity_logs(date_made, username, action) VALUES('$currDateTime', '$username', 'EDIT PROFILE')";
$addLog = mysql_query($addLogQuery) OR die(mysql_error());

mysql_close($link_id);

if($acctType == 2){
	header("Location: employee-edit-profile.php?saved=1");
}
else if($acctType == 1 || $acctType == 3){
	header("Location: employer-edit-profile.php?saved=1");
}
else{
	header("Location: admin-edit-profile.php?saved=1");
}
?>
 