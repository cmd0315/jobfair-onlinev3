<?php
/** FOR DB **/
require './includes/connect.inc.php';
$link_id = db_connect($dbname);
if(!$link_id) error_message(sql_error());
/** FOR DB**/
require './includes/functions.php';

//GET SESSION VARIABLLES
session_start();
if(isset($_SESSION['SRIUsername']) && $_SESSION['SRIUsername'] != ""){
	$sessionUsername = $_SESSION['SRIUsername'];
}
else{
	header("Location: index.php");
}

$xhr = $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
$mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv'); //allowed csv format
$noZeroIdentifierTables = array('job_post', 'location_posts', 'job_location'); 

$content = "";
$errorMsg = "";
$successfulList = "";
$failedList = "";
$maxFileSize = 350;//in KB

if(isset($_POST['importBtn'])){
	// if(!$xhr){
	// 	$error += 1;
	// 	$errorMsg .= "<span>";
	// }
	$file = $_FILES['importedFile']['tmp_name'];
	$fileName = $_FILES['importedFile']['name'];
	$fileSize = $_FILES['importedFile']['size'];
	$fileSize = $fileSize/1000;
	$fileType = $_FILES['importedFile']['type'];

	if(in_array($fileType, $mimes) && $fileSize<=$maxFileSize){
		$content .= "<h4> File Uploaded:</h4> <strong>" . $fileName . "</strong> (" . $fileSize . " KB) </br>"; // print file name
		$fileHandler = fopen($file, "r"); //open file in read mode
		$queryCount = 0;

		while(($fileContent = fgetcsv($fileHandler, 10000, ",")) !== false){
			$numFields = count($fileContent); //count number of fields
			$identifier = $fileContent[0];
			$username =  mysql_real_escape_string($fileContent[1]);
			if($identifier[0] === "*" && (!is_numeric($identifier))){
				$identifier = substr($identifier, 1);
				$tableName = $identifier;
			}
			else{
				
				if(strlen($username)<11 && !in_array($tableName, $noZeroIdentifierTables)){ //add 0 to username if length is not valid
					$username = 0 . $username;	
				}

				//exclude duplicates from database
				if($tableName === 'employer' || $tableName === 'employee'){
					$rowLabel = "username";
					$deDup = mysql_query("Select * from $tableName WHERE username = '$username'") or die(mysql_error());
					if($tableName === 'employee'){
						$firstName = mysql_real_escape_string($fileContent[2]);
						$middleName = mysql_real_escape_string($fileContent[3]);
						$lastName = mysql_real_escape_string($fileContent[4]);
						$street1 = mysql_real_escape_string($fileContent[5]);
						$city1 = mysql_real_escape_string($fileContent[6]);
						$province1 = mysql_real_escape_string($fileContent[7]);
						$birthDate = mysql_real_escape_string($fileContent[8]);
						$birthDate = date('Y-m-d', strtotime($birthDate));
						$birthPlace = mysql_real_escape_string($fileContent[9]);
						$sex = mysql_real_escape_string($fileContent[10]);
						$height = mysql_real_escape_string($fileContent[11]);
						$weight = mysql_real_escape_string($fileContent[12]);
						$civilStatus = mysql_real_escape_string($fileContent[13]);
						$numChildren = mysql_real_escape_string($fileContent[14]);
						$religion = mysql_real_escape_string($fileContent[15]);
						$mobileNum = mysql_real_escape_string($fileContent[16]);
						if(strlen($mobileNum)<11){
							$mobileNum = 0 . $mobileNum;
						}
						
						$email = mysql_real_escape_string($fileContent[17]);
						$telNum = mysql_real_escape_string($fileContent[18]);
						$profilePic = mysql_real_escape_string($fileContent[19]);
						$hsName = mysql_real_escape_string($fileContent[20]);
						$hsEndYr = mysql_real_escape_string($fileContent[21]);
						$hsEduc = mysql_real_escape_string($fileContent[22]);
						$collegeName = mysql_real_escape_string($fileContent[23]);
						$collegeDegree = mysql_real_escape_string($fileContent[24]);
						$collegeEndYr = mysql_real_escape_string($fileContent[25]);
						$collegeEduc = mysql_real_escape_string($fileContent[26]);
						$vocSchoolName = mysql_real_escape_string($fileContent[27]);
						$vocCourse = mysql_real_escape_string($fileContent[28]);
						$vocEndYr = mysql_real_escape_string($fileContent[29]);
						$vocEduc = $fileContent[30];
						$validTotalFields = 31;
						$validMaxFields = 20;

						$insertQuery = "INSERT INTO employee(username, first_name, middle_name, last_name, street1, city1, province1, birth_date, birth_place, sex, height, weight, civil_status, num_children, religion, mobile_num, email, tel_num, profile_pic, hs_name, hs_end_yr, hs_educ, college_name, college_degree, college_end_yr, college_educ, voc_school_name, voc_course, voc_end_yr, voc_educ) VALUES ('$username', '$firstName', '$middleName', '$lastName', '$street1', '$city1', '$province1', '$birthDate', '$birthPlace', '$sex', '$height', '$weight', '$civilStatus', '$numChildren', '$religion', '$mobileNum', '$email', '$telNum', '$profilePic', '$hsName', '$hsEndYr', '$hsEduc', '$collegeName', '$collegeDegree', '$collegeEndYr', '$collegeEduc', '$vocSchoolName', '$vocCourse', '$vocEndYr', '$vocEduc')";
					}
					else{
						$companyName = mysql_real_escape_string($fileContent[2]);
						$companyDesc = mysql_real_escape_string($fileContent[3]);
						$street1 = mysql_real_escape_string($fileContent[4]);
						$city1 = mysql_real_escape_string($fileContent[5]);
						$province1 = mysql_real_escape_string($fileContent[6]);
						$profilePic = mysql_real_escape_string($fileContent[7]);
						$firstName = mysql_real_escape_string($fileContent[8]);
						$middleName = mysql_real_escape_string($fileContent[9]);
						$lastName = mysql_real_escape_string($fileContent[10]);
						$mobileNum = mysql_real_escape_string($fileContent[11]);
						if(strlen($mobileNum)<11){
							$mobileNum = 0 . $mobileNum;
						}
						$email = mysql_real_escape_string($fileContent[12]);
						$telNum = mysql_real_escape_string($fileContent[13]);
						$cpPosition = mysql_real_escape_string($fileContent[14]);
						$cpDept = mysql_real_escape_string($fileContent[15]);
						$validTotalFields = 16;
						$validMaxFields = 13;

						$insertQuery = "INSERT INTO $tableName(username, company_name, company_desc, street1, city1, province1, profile_pic, first_name, middle_name, last_name, mobile_num, email, tel_num, position, department) VALUES ('$username', '$companyName', '$companyDesc', '$street1', '$city1', '$province1', '$$profilePic', '$firstName', '$middleName', '$lastName', '$mobileNum', '$email', '$telNum', '$cpPosition', '$cpDept')";
					}
				}
				else if($tableName === 'account'){
					$rowLabel = "username";
					$acctPassword = mysql_real_escape_string($fileContent[2]);
					$acctType = mysql_real_escape_string($fileContent[3]);
					$acctStatus = mysql_real_escape_string($fileContent[4]);
					$dateJoined = mysql_real_escape_string($fileContent[5]);
					$dateJoined = date('Y-m-d H:i:s', strtotime($dateJoined));
					$validTotalFields = 6;
					$validMaxFields = 6;

					$deDup = mysql_query("Select * from $tableName WHERE username = '$username'") or die(mysql_error());
					$insertQuery = "INSERT INTO $tableName(username, password, acct_type, status, date_joined) VALUES('$username', '$acctPassword', '$acctType', '1', '$dateJoined')";
				}
				else if($tableName === 'interest'){
					$rowLabel = "employee_username";
					$positionID = mysql_real_escape_string($fileContent[2]);
					$interestStatus = mysql_real_escape_string($fileContent[3]);
					$interestDateAdded = mysql_real_escape_string($fileContent[4]);
					$interestDateAdded = date('Y-m-d H:i:s', strtotime($interestDateAdded));
					$validTotalFields = 5;
					$validMaxFields = 5;

					$deDup = mysql_query("Select * from $tableName WHERE (employee_username = '$username' AND position_id='$positionID')") or die(mysql_error());

					$insertQuery = "INSERT INTO $tableName(employee_username, position_id, status, date_added) VALUES('$username', '$positionID', '$interestStatus', '$interestDateAdded')";
				}
				else if($tableName === 'work_history'){
					$rowLabel = "employee_username";
					$companyName = mysql_real_escape_string($fileContent[2]);
					$positionName = mysql_real_escape_string($fileContent[3]);
					$workStart = mysql_real_escape_string($fileContent[4]);
					$workStart = date('Y-m-d', strtotime($workStart));
					$workEnd = mysql_real_escape_string($fileContent[5]);
					$workEnd = date('Y-m-d', strtotime($workEnd));
					$wHDateAdded = mysql_real_escape_string($fileContent[6]);
					$wHDateAdded = date('Y-m-d H:i:s', strtotime($wHDateAdded));
					$validTotalFields = 7;
					$validMaxFields = 7;

					$deDup = mysql_query("Select * from $tableName WHERE (employee_username = '$username' AND company_name = '$companyName' AND position = '$positionName' AND work_start = '$workStart' AND work_end = '$workEnd')") or die(mysql_error());

					$insertQuery = "INSERT INTO $tableName(employee_username, company_name, position, work_start, work_end, date_added) VALUES('$username', '$companyName', '$positionName', '$workStart', '$workEnd', '$wHDateAdded')";
				}
				else if($tableName === 'application_history'){
					$rowLabel = "employee_username";
					$jobCode = mysql_real_escape_string($fileContent[2]);
					$aHDateApplied = mysql_real_escape_string($fileContent[3]);
					$aHDateApplied = date('Y-m-d H:i:s', strtotime($aHDateApplied));
					$validTotalFields = 4;
					$validMaxFields = 4;

					$deDup = mysql_query("Select * from $tableName WHERE (employee_username = '$username' AND job_code = '$jobCode')") or die(mysql_error());

					$insertQuery = "INSERT INTO $tableName(employee_username, job_code, date_applied) VALUES('$username', '$jobCode', '$aHDateApplied')";
				}
				else if($tableName === 'job_post'){
					$jobPostCode = $username;
					$rowLabel = "code";
					$employerUsername = mysql_real_escape_string($fileContent[2]);
					$datePosted = mysql_real_escape_string($fileContent[3]);
					$datePosted = date('Y-m-d H:i:s', strtotime($datePosted));
					$locationId = mysql_real_escape_string($fileContent[4]);
					$street = mysql_real_escape_string($fileContent[5]);
					$jobPosition = mysql_real_escape_string($fileContent[6]);
					$numVacancies = mysql_real_escape_string($fileContent[7]);
					$jobDesc = mysql_real_escape_string($fileContent[8]);
					$eSex = mysql_real_escape_string($fileContent[9]);
					$eCivilStatus = mysql_real_escape_string($fileContent[10]);
					$eMinAge = mysql_real_escape_string($fileContent[11]);
					$eMaxAge = mysql_real_escape_string($fileContent[12]);
					$eHeight = mysql_real_escape_string($fileContent[13]);
					$eWeight = mysql_real_escape_string($fileContent[14]);
					$eEducAttainment = mysql_real_escape_string($fileContent[15]);
					$jobOpenDate = mysql_real_escape_string($fileContent[16]);
					$jobOpenDate = date('Y-m-d', strtotime($jobOpenDate));
					$jobCloseDate = mysql_real_escape_string($fileContent[17]);
					$jobCloseDate = date('Y-m-d', strtotime($jobCloseDate));
					$jobStatus1 = mysql_real_escape_string($fileContent[18]);
					$jobStatus2 = mysql_real_escape_string($fileContent[19]);
					$validTotalFields = 20;
					$validMaxFields = 17;

					$deDup = mysql_query("Select * from $tableName WHERE code = '$jobPostCode'") or die(mysql_error());

					$insertQuery = "INSERT INTO $tableName(code, employer_username, date_posted, location_id, street, job_position, num_vacancies, job_desc, e_sex, e_civil_status, e_min_age, e_max_age, e_height, e_weight, e_educ_attainment, job_opendate, job_closedate, status1, status2) VALUES ('$jobPostCode', '$employerUsername', '$datePosted', '$locationId', '$street', '$jobPosition', '$numVacancies', '$jobDesc', '$eSex', '$eCivilStatus', '$eMinAge', '$eMaxAge', '$eHeight', '$eWeight', '$eEducAttainment', '$jobOpenDate', '$jobCloseDate', '$jobStatus1', '$jobStatus2')";
				}
				else if($tableName === 'location_posts'){
					$locationId = $username;
					$rowLabel = "location_id";
					$postCode = mysql_real_escape_string($fileContent[2]);
					$dateAdded = mysql_real_escape_string($fileContent[3]);
					$dateAdded = date('Y-m-d H:i:s', strtotime($dateAdded));
					$locationPostStatus = mysql_real_escape_string($fileContent[4]);

					$deDup = mysql_query("Select * from $tableName WHERE (location_id = '$locationId' AND post_code = '$postCode') ") or die(mysql_error());

					$insertQuery = "INSERT INTO $tableName(location_id, post_code, date_added, status) VALUES ('$locationId', '$postCode', '$dateAdded', '$locationPostStatus')";
				}
				else if($tableName === 'job_location'){
					$locationId = $username;
					$rowLabel = "location_id";
					$dateChanged = mysql_real_escape_string($fileContent[2]);
					$dateChanged = date('Y-m-d H:i:s', strtotime($dateChanged));

					$deDup = mysql_query("Select * from $tableName WHERE location_id = '$locationId'") or die(mysql_error());

					$insertQuery = "INSERT INTO $tableName(location_id, date_changed) VALUES ('$locationId', '$dateChanged')";
				}
				else{
					$error +=1;
					$errorMsg .= "Invalid file!"; 
					printContent($fileHandler, $error, $errorMsg, $content, $successfulList, $failedList, $sessionUsername, $fileName);
				}

				//add table contents
				$numrows = mysql_num_rows($deDup);
				if($numrows === 0){
					$insert = mysql_query($insertQuery) or die(mysql_error());
					$successfulList .= "[$queryCount] Entry added with $rowLabel <strong>$username</strong> to table <strong>$tableName</strong></br>";
				}
				else{
					$failedList .= "[$queryCount] Duplicate entry of $rowLabel <strong>$username</strong> in table <strong>$tableName</strong></br>";
				}
			}
			$queryCount += 1;
		}
	}
	else{
		$error += 1;
		$errorMsg .= "Sorry, data import failed. Either file type of selected file is not allowed or its file size exceeded the maximum size allowed ($maxFileSize KB).</br>";
	}

	// if(!$xhr){
	// 	$errorMsg .= "</span>";
	// }
}
else{
	$error += 1;
	$errorMsg = "No file submitted. </br>";
}

printContent($fileHandler, $error, $errorMsg, $content, $successfulList, $failedList, $sessionUsername, $fileName);
function printContent($fP, $errNum, $errMsg, $c, $sL, $fL, $sUser, $fName){ //print results
	$logStatus = 0; //unsuccessful
	
	//get currentdate
	date_default_timezone_set('Singapore');
	$currDateTime = date("Y-m-d H:i:s");

	if($errNum > 0){
		echo "<div class='alert alert-block alert-error span12'>" . "<h4>Uploading failed...</h4>" . $errMsg . "</div>"; //print error msg
	}
	else{
		echo "<div class='alert alert-info span12'> " . $c . "</div>";
		if($sL !== ""){ 
			echo "<div class='alert alert-block alert-success span11' id='importListDiv'> <h4>Successful:</h4>" . $sL . "</div>";
		}
		if($fL !== ""){
			echo "<div class='alert alert-block alert-error span11' id='importListDiv'>" . "<h4>Failed: </h4>" . $fL . "</div>";
		}
		$logStatus = 1; //successful
	}
	fclose($fP);

	//add log activity
	$addLogQuery = "INSERT INTO activity_logs(date_made, username, action, reference_object, status) VALUES('$currDateTime', '$sUser', 'IMPORT DATA', '$fName', '$logStatus')";
	$addLog = mysql_query($addLogQuery) OR die(mysql_error());
	exit;
}
mysql_close($link_id);
?>