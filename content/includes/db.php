<?php
/** FOR DB **/
include './includes/connect.inc.php';
$link_id = db_connect($dbname);
if(!$link_id) error_message(sql_error());
/** FOR DB**/

function getAcctInfo($username, $infoType) {
	$data = "";
	if (isset($username) && isset($infoType) ){	
		$accountDataQuery = "SELECT * FROM account WHERE username ='$username' ";
		$getAccountData = mysql_query($accountDataQuery); 
		$rowCount = mysql_num_rows($getAccountData);
		$accountData = mysql_fetch_assoc($getAccountData);
		
		if($infoType == "status") {
			$accountType = $accountData['acct_type'];
			$status = "";
			if($rowCount > 0){
				if ($accountType == 1) {
					$status = "Employer";
				}
				else if($accountType == 2){
					$status = "Employee";
				}
				else if($accountType == 3){
					$status = "SRI Branch Manager";
				}
				else if($accountType == 0){
					$status = "Web Admin";
				}
			}
			else{
				$status = "PUBLIC";
			}
			$data = $status;
		}
		elseif($infoType == "reg-status") {
			$data = $accountData['status'];
		}
		elseif($infoType == "password") {
			$data = $accountData['password'];
		}
		elseif($infoType == "date-joined") {
			$data = $accountData['date_joined'];
		}
		
	}
	else {
		$data = "Missing paramaters: username or info type";
	}
	return $data;
	mysql_close($link_id);
}

function getEmployeeData($username, $infoType) {
	$data = "";
	if (isset($username) && isset($infoType) ){	
		$employeeDataQuery = "SELECT * FROM employee WHERE username ='$username' OR mobile_num='$username' OR email='$username' ";
		$getEmployeeData = mysql_query($employeeDataQuery) or die(mysql_error()); 
		$employeeData = mysql_fetch_assoc($getEmployeeData);
		$dataRow = mysql_num_rows($getEmployeeData);
		if($dataRow > 0){
			if($infoType == "full-name") {
				$fullName = $employeeData['first_name'] . " " . $employeeData['middle_name'] . " " . $employeeData['last_name'];
				$fullName = ucwords(strtolower($fullName));
				$data = $fullName;
			}
			elseif($infoType == "first-name") {
				$firstName = $employeeData['first_name'];
				$firstName = ucwords(strtolower($firstName));
				$data = $firstName;
			}
			elseif($infoType == "middle-name") {
				$middleName = $employeeData['middle_name'];
				$middleName = ucwords(strtolower($middleName));
				$data = $middleName;
			}
			elseif($infoType == "last-name") {
				$lastName = $employeeData['last_name'];
				$lastName = ucwords(strtolower($lastName));
				$data = $lastName;
			}
			elseif($infoType == "age") {
				$birthDate = $employeeData['birth_date'];
				$birthDate = explode("-", $birthDate);
				 //get age from date or birthdate
				$age = (date("md", date("U", mktime(0, 0, 0, $birthDate[2], $birthDate[1], $birthDate[0]))) > date("md") ? ((date("Y")-$birthDate[0])-1):(date("Y")-$birthDate[0]));
				if($age < 17 || $age > 60){
					$age = "-";
				}
				$data = $age;
			}
			elseif($infoType == "sex") {
				$sex = $employeeData['sex'];
				$data = $sex;
			}
			elseif($infoType == "address") {
				$address = $employeeData['city1'] . ", " . $employeeData['province1'];
				$data = $address;
			}
			elseif($infoType == "complete_address") {
				$address = $employeeData['street1'] . ", " . $employeeData['city1'] . ", " . $employeeData['province1'];
				$data = $address;
			}
			elseif($infoType == "profile_pic") {
				$profilePic = $employeeData['profile_pic'];
				$picSrc = "";
				if($profilePic == "" || strcasecmp ($profilePic , "Null") == 0 ){
					$picSrc = "./img/id.png";
				}
				else{
					$picSrc = "./uploads/profile_pictures/" . $profilePic;
				}
				$data = $picSrc;
			}
			elseif($infoType == "birth_date") {
				$birthDate = $employeeData['birth_date'];
				$data = $birthDate;
			}
			elseif($infoType == "birth_place") {
				$birthPlace = $employeeData['birth_place'];
				$birthPlace = ucwords(strtolower($birthPlace));
				$data = $birthPlace;
			}
			elseif($infoType == "street1") {
				$street1 = $employeeData['street1'];
				$data = $street1;
			}
			elseif($infoType == "city1") {
				$city1 = $employeeData['city1'];
				$city1 = ucwords(strtolower($city1));
				$data = $city1;
			}
			elseif($infoType == "province1") {
				$province1 = $employeeData['province1'];
				$province1 = ucwords(strtolower($province1));
				$data = $province1;
			}
			elseif($infoType == "height") {
				$height = $employeeData['height'];
				$data = $height;
			}
			elseif($infoType == "weight") {
				$weight = $employeeData['weight'];
				$data = $weight;
			}
			elseif($infoType == "civil_status") {
				$civilStatus = $employeeData['civil_status'];
				$data = $civilStatus;
			}
			elseif($infoType == "num_children") {
				$numChildren = $employeeData['num_children'];
				$data = $numChildren;
			}
			elseif($infoType == "religion") {
				$religion = $employeeData['religion'];
				$religion = ucwords(strtolower($religion));
				$data = $religion;
			}
			elseif($infoType == "mobile") {
				$mobile = $employeeData['mobile_num'];
				$data = $mobile;
			}
			elseif($infoType == "email") {
				$email = $employeeData['email'];
				$email = strtolower($email);
				$data = $email;
			}
			elseif($infoType == "landline") {
				$landline = $employeeData['tel_num'];
				$data = $landline;
			}
			elseif($infoType == "hs_name") {
				$hsName = $employeeData['hs_name'];
				//$hsName = ucwords(strtolower($hsName));
				$data = $hsName;
			}
			elseif($infoType == "hs_start_yr") {
				$hsStartYr = $employeeData['hs_start_yr'];
				$data = $hsStartYr;
			}
			elseif($infoType == "hs_end_yr") {
				$hsEndYr = $employeeData['hs_end_yr'];
				$data = $hsEndYr;
			}
			elseif($infoType == "hs_educ") {
				$hsEduc= $employeeData['hs_educ'];
				$data = $hsEduc;
			}
			elseif($infoType == "hs_duration") {
				$hsStartYr = $employeeData['hs_start_yr'];
				$hsEndYr = $employeeData['hs_end_yr'];
				$duration = $hsEndYr - $hsStartYr;
				if($duration > 0){
					$duration = $duration . ' years';
				}
				else if($duration == 1){
					$duration = $duration . ' year';
				}
				else{
					$duration = 'Less than a year';
				}
				$data = $duration;
			}
			elseif($infoType == "college_name") {
				$collegeName = $employeeData['college_name'];
				//$collegeName = ucwords(strtolower($collegeName));
				$data = $collegeName;
			}
			elseif($infoType == "college_degree") {
				$collegeDegree = $employeeData['college_degree'];
				$data = $collegeDegree;
			}
			elseif($infoType == "college_start_yr") {
				$collegeStartYr = $employeeData['college_start_yr'];
				$data = $collegeStartYr;
			}
			elseif($infoType == "college_end_yr") {
				$collegeEndYr = $employeeData['college_end_yr'];
				$data = $collegeEndYr;
			}
			elseif($infoType == "college_educ") {
				$collegeEduc = $employeeData['college_educ'];
				$data = $collegeEduc;
			}
			elseif($infoType == "college_duration") {
				$collegeStartYr = $employeeData['college_start_yr'];
				$collegeEndYr = $employeeData['college_end_yr'];
				$duration = $collegeEndYr - $collegeStartYr;
				if($duration > 0){
					$duration = $duration . ' years';
				}
				else if($duration == 1){
					$duration = $duration . ' year';
				}
				else{
					$duration = 'Less than a year';
				}
				$data = $duration;
			}
			elseif($infoType == "voc_school_name") {
				$vocSchoolName = $employeeData['voc_school_name'];
				//$vocSchoolName = ucwords(strtolower($vocSchoolName));
				$data = $vocSchoolName;
			}
			elseif($infoType == "voc_course") {
				$vocCourse = $employeeData['voc_course'];
				$data = $vocCourse;
			}
			elseif($infoType == "voc_start_yr") {
				$vocStartYr = $employeeData['voc_start_yr'];
				$data = $vocStartYr;
			}
			elseif($infoType == "voc_end_yr") {
				$vocEndYr = $employeeData['voc_end_yr'];
				$data = $vocEndYr;
			}
			elseif($infoType == "voc_educ") {
				$vocEduc = $employeeData['voc_educ'];
				$data = $vocEduc;
			}
			elseif($infoType == "voc_school_duration") {
				$vocStartYr = $employeeData['voc_start_yr'];
				$vocEndYr = $employeeData['voc_end_yr'];
				$duration = $vocEndYr - $vocStartYr;
				if($duration > 0){
					$duration = $duration . ' years';
				}
				else if($duration == 1){
					$duration = $duration . ' year';
				}
				else{
					$duration = 'Less than a year';
				}
				$data = $duration;
			}
			elseif($infoType == "educational_attainment") {
				$highestEduc = "";
				$applicantHSEduc = $employeeData['hs_educ'];
				$applicantCollegeEduc = $employeeData['college_educ'];
				$applicantVocEduc = $employeeData['voc_educ'];
				
				if($applicantCollegeEduc === ""){
					if($applicantVocEduc !== ""){
						$highestEduc = "Vocational School" . " " .  $applicantVocEduc;
					}
					else
						$highestEduc = "High School" . " " .  $applicantHSEduc;
				}
				else{
						if($applicantCollegeEduc === "Undergraduate"){
							$applicantCollegeEduc = "Level";
						}
						$highestEduc = "College" . " " . $applicantCollegeEduc;
					}
				$data = $highestEduc;
			}
		}
		else{
			$data = "Doesn't exist!";
		}
	}
	else{
		$data = "Missing paramaters: username or info type";
	}
	return $data;
}

function getEmployerData($username, $infoType) {
	$data = "";
	if (isset($username) && isset($infoType) ){	
		$employerDataQuery = "SELECT * FROM employer WHERE username ='$username' OR mobile_num='$username' OR email='$username' ";
		$getEmployerData = mysql_query($employerDataQuery); 
		$employerData = mysql_fetch_assoc($getEmployerData);
		
		if($infoType == "company-name") {
			$companyName = $employerData['company_name'];
			//$companyName = ucwords(strtolower($companyName));
			$data = $companyName;
		}
		elseif($infoType == "address") {
			$address = $employerData['city1'] . ", " . $employerData['province1'];
			$data = $address;
		}
		elseif($infoType == "profile_pic") {
			$profilePic = $employerData['profile_pic'];
			$picSrc = "";
			if($profilePic == "" || strcasecmp ($profilePic , "Null") == 0 ){
				$picSrc = "./img/id.png";
			}
			else{
				$picSrc = "./uploads/company_logos/" . $profilePic;
			}
			$data = $picSrc;
		}
		elseif($infoType == "company_desc") {
			$companyDesc = $employerData['company_desc'];
			$data = $companyDesc;
		}
		elseif($infoType == "street1") {
			$street1 = $employerData['street1'];
			$data = $street1;
		}
		elseif($infoType == "city1") {
			$city1 = $employerData['city1'];
			$city1 = ucwords(strtolower($city1));
			$data = $city1;
		}
		elseif($infoType == "province1") {
			$province1 = $employerData['province1'];
			$province1 = ucwords(strtolower($province1));
			$data = $province1;
		}
		elseif($infoType == "contact-person-name") {
			$cPersonName = $employerData['first_name'] . " " . $employerData['middle_name'] . " " . $employerData['last_name'];
			$cPersonName = ucwords(strtolower($cPersonName));
			$data = $cPersonName;
		}
		elseif($infoType == "first_name") {
			$firstName = $employerData['first_name'];
			$firstName = ucwords(strtolower($firstName));
			$data = $firstName;
		}
		elseif($infoType == "middle_name") {
			$middleName = $employerData['middle_name'];
			$middleName = ucwords(strtolower($middleName));
			$data = $middleName;
		}
		elseif($infoType == "last_name") {
			$lastName = $employerData['last_name'];
			$lastName = ucwords(strtolower($lastName));
			$data = $lastName;
		}
		elseif($infoType == "mobile") {
			$mobile = $employerData['mobile_num'];
			$data = $mobile;
		}
		elseif($infoType == "email") {
			$email = $employerData['email'];
			$data = $email;
		}
		elseif($infoType == "landline") {
			$landline = $employerData['tel_num'];
			$data = $landline;
		}
		elseif($infoType == "position") {
			$position = $employerData['position'];
			$data = $position;
		}
		elseif($infoType == "department") {
			$department = $employerData['department'];
			$data = $department;
		}
	}
	else{
		$data = "Missing paramaters: username or info type";
	}
	return $data;
	mysql_close($link_id);
}

function getWebAdminData($username, $infoType) {
	$data = "";
	if (isset($username) && isset($infoType) ){	
		$webAdminDataQuery = "SELECT * FROM web_admin WHERE username ='$username' ";
		$getWebAdminData = mysql_query($webAdminDataQuery); 
		$webAdminData = mysql_fetch_assoc($getWebAdminData);
		
		if($infoType == "full-name") {
			$fullName = $webAdminData['first_name'] . " " . $webAdminData['middle_name'] . " " . $webAdminData['last_name'];
			$fullName = ucwords(strtolower($fullName));
			$data = $fullName;
		}
		elseif($infoType == "address") {
			$address = $webAdminData['city'] . ", " . $webAdminData['province'];
			$data = $address;
		}
		elseif($infoType == "first_name") {
			$firstName = $webAdminData['first_name'];
			$firstName = ucwords(strtolower($firstName));
			$data = $firstName;
		}
		elseif($infoType == "middle_name") {
			$middleName = $webAdminData['middle_name'];
			$middleName = ucwords(strtolower($middleName));
			$data = $middleName;
		}
		elseif($infoType == "last_name") {
			$lastName = $webAdminData['last_name'];
			$lastName = ucwords(strtolower($lastName));
			$data = $lastName;
		}
		elseif($infoType == "street1") {
			$street1 = $webAdminData['street'];
			$data = $street1;
		}
		elseif($infoType == "city1") {
			$city1 = $webAdminData['city'];
			$data = $city1;
		}
		elseif($infoType == "province1") {
			$street1 = $webAdminData['province'];
			$data = $street1;
		}
		elseif($infoType == "mobile") {
			$mobile = $webAdminData['mobile_num'];
			$data = $mobile;
		}
		elseif($infoType == "email") {
			$email = $webAdminData['email'];
			$data = $email;
		}
	}
	else{
		$data = "Missing paramaters: username or info type";
	}
	return $data;
	mysql_close($link_id);
}

function getWorkHistory($username, $infoType) {
	$data = "";
	$workHistoryQuery = "SELECT * FROM work_history WHERE employee_username = '$username' ";
	$getWorkHistory = mysql_query($workHistoryQuery);
	$numrows = mysql_num_rows($getWorkHistory);
	$companyNames = array();
	$jobPositions = array();
	$workDurations = array();
	while($workHistory = mysql_fetch_assoc($getWorkHistory)){
		$companyName = $workHistory['company_name'];
		$jobPosition = $workHistory['position'];
		$workStart = $workHistory['work_start'];
		$workEnd = $workHistory['work_end'];
		$workStart = new DateTime("$workStart");
		$workEnd = new DateTime("$workEnd");
		
		$diff = $workEnd->diff($workStart);
		$numYears =  $diff->y;
		$numMonths =  $diff->m;
		$numDays =  $diff->d;
		$workDuration .= "";
		if($numYears > 0){
			if($numYears > 1){
				$years = " years";
			}
			else{
				$years = " year";
			}
			$workDuration = $numYears . $years;
		}
		if($numMonths > 0){
			if($numMonths > 1){
				$months = " months";
			}
			else{
				$months = " month";
			}
			if($numYears >0){
				$workDuration .= ", " . $numMonths . $months;
			}
			else{
				$workDuration .= $numMonths . $months;
			}
		}
		if($numDays > 0){
			if($numDays > 1){
				$days = " days";
			}
			else{
				$days = " day";
			}
			
			if($numYears >0 || $numMonths > 0){
				$workDuration .= ", " . $numDays . $days;
			}
			else{
				$workDuration .= $numDays . $days;
			}
		}
		array_push($companyNames, $companyName);
		array_push($jobPositions, $jobPosition);
		array_push($workDurations, $workDuration);
	}
	
	if($infoType == "count"){
		$data = $numrows;
	}
	else if($infoType == "num_rows"){
		$data = getWHRowNums($username);
	}
	else if($infoType == "work_experience1"){
		$data = $companyNames[0];
	}
	else if($infoType == "work_experience2"){
		$data = $companyNames[3];
	}
	else if($infoType == "work_experience3"){
		$data = $companyNames[2];
	}
	else if($infoType == "position1"){
		$data = $jobPositions[0];
	}
	else if($infoType == "position2"){
		$data = $jobPositions[1];
	}
	else if($infoType == "position3"){
		$data = $jobPositions[2];
	}
	else if($infoType == "work_duration1"){
		$data = $workDurations[0];
	}
	else if($infoType == "work_duration2"){
		$data = $workDurations[1];
	}
	else if($infoType == "work_duration3"){
		$data = $workDurations[2];
	}
	return $data;
	mysql_close($link_id);
}

function getWHRowNums($username){
	$data = "";
	$workHistoryQuery = "SELECT * FROM work_history WHERE employee_username = '$username' ";
	$getWorkHistory = mysql_query($workHistoryQuery);
	while ($workHistoryRow = mysql_fetch_assoc($getWorkHistory)){
		$data .= $workHistoryRow['id'] . "-";
	}
	return $data;
	mysql_close($link_id);
}

function getWHRowData($username, $rowNum, $infoType){
	$data = "";
	$workHistoryQuery = "SELECT * FROM work_history WHERE employee_username = '$username' AND id = '$rowNum' ";
	$getWorkHistory = mysql_query($workHistoryQuery);
	$workHistoryRow = mysql_fetch_assoc($getWorkHistory);
	if($infoType == "company_name"){
		$coName = $workHistoryRow['company_name'];
		$data = $coName;
	}
	else if($infoType == "position-id"){
		$position = $workHistoryRow['position'];
		$data = $position;
	}
	else if($infoType == "work_start"){
		$workStart = $workHistoryRow['work_start'];
		$data = $workStart;
	}
	else if($infoType == "work_end"){
		$workEnd = $workHistoryRow['work_end'];
		$data = $workEnd;
	}
	return $data;
	mysql_close($link_id);
}

function getInterests($username, $infoType) {
	$data = "";
	$interestsQuery = "SELECT * FROM interest WHERE employee_username = '$username' ";
	$getInterests = mysql_query($interestsQuery);
	$numrows = mysql_num_rows($getInterests);
	if($infoType == "count"){
		$data = $numrows;
	}
	else if($infoType == "num_rows"){
		$data = getInterestNums($username);
	}
	else if($infoType == "rows"){
		$data = getInterestNums($username);
	}
	return $data;
	mysql_close($link_id);
}

function getInterestNums($username){
	$data = "";
	$interestsQuery = "SELECT * FROM interest WHERE employee_username = '$username' ";
	$getInterests = mysql_query($interestsQuery);
	while ($interestRow = mysql_fetch_assoc($getInterests)){
		$data .= $interestRow['position_id'] . "-";
	}
	return $data;
	mysql_close($link_id);
}

function getInterestRows($username){
	$data = "";
	$interestsQuery = "SELECT * FROM interest WHERE employee_username = '$username' ";
	$getInterests = mysql_query($interestsQuery);
	while ($interestRow = mysql_fetch_assoc($getInterests)){
		$data .= $interestRow['name'] . "-";
	}
	return $data;
	mysql_close($link_id);
}

function getInterestRowData($username, $rowNum, $infoType){
	$data = "";
	$interestsQuery = "SELECT * FROM interest WHERE employee_username = '$username' AND id = '$rowNum' ";
	$getInterests = mysql_query($interestsQuery);
	$interestRow = mysql_fetch_assoc($getInterests);
	if($infoType == "position-id"){
		$positionId = $interestRow['position_id'];
		$data = $positionId;
	}
	else if($infoType == "date_added"){
		$date_added = $interestRow['date_added'];
		$data = $date_added;
	}
	return $data;
	mysql_close($link_id);
}

function getClassifications(){
	$data = "";
	$classificationsQuery = "SELECT * FROM classification";
	$getClassifications = mysql_query($classificationsQuery);
	while ($classificationRow = mysql_fetch_assoc($getClassifications)){
		$data .= $classificationRow['name'] . "-";
	}
	return $data;
	mysql_close($link_id);
}

function getAvailableClassifications($username){
	$data = "";
	$classifications = getJobPositions();
	$interests = getInterestRows($username);
	$interests = explode("-", $interests);
	for($i=0; $i<count($classifications); $i++) {
		$c = $classifications[$i];
		if(!in_array($c, $interests)){
			$data.= $c . "-";
		}
	}
	return $data;
	mysql_close($link_id);
}

function checkAccountExists($username) {
	$data = "";
	$accountExistsQuery = "SELECT * FROM account WHERE username='$username' ";
	$getRowNum = mysql_query($accountExistsQuery);
	$numrows = mysql_num_rows($getRowNum);
	$acctDetails = mysql_fetch_assoc($getRowNum);
	$acctStatus = $acctDetails['status'];
	
	if($numrows > 0){
		if($acctStatus>0){
			$data = "Yes";
		}
		else{
			$data = "Not Verified";
		}
	}
	else {
		$checkEmployeeQuery = "SELECT * FROM employee WHERE mobile_num='$username' OR email='$username' ";
		$getRowNum2 = mysql_query($checkEmployeeQuery);
		$numrows2 = mysql_num_rows($getRowNum2);
		if($numrows2 > 0){
			if($acctStatus>0){
				$data = "Yes";
			}
			else{
				$data = "Not Verified";
			}
			
		}
		else{
			$checkEmployerQuery = "SELECT * FROM employer WHERE mobile_num='$username' OR email='$username' ";
			$getRowNum2 = mysql_query($checkEmployerQuery);
			$numrows3 = mysql_num_rows($getRowNum2);
			if($numrows3 > 0){
				if($acctStatus>0){
					$data = "Yes";
				}
				else{
					$data = "Not Verified";
				}
			}
			else {
				$data = "No";
			}
		}
	}
	return $data;
	mysql_close($link_id);
}

function changePassword($username, $newPassword) {
	$changePwdQuery = "UPDATE account SET password='$newPassword' WHERE  username='$username' ";
	$changePassword = mysql_query($changePwdQuery);
	mysql_close($link_id);
}

function getUserInfo($code, $infoType) {
	$data = "";
	$usernameQuery = "SELECT * FROM verification_code WHERE code='$code' ";
	$getUser = mysql_query($usernameQuery) or die(mysql_error());
	$userData = mysql_fetch_assoc($getUser);
	if($infoType == "username") {
		$username = $userData['username'];
		$data = $username;
	}
	return $data;
	mysql_close($link_id);
}

function getJobPositions() {
	$data = array();
	$positionQuery = "SELECT * FROM position WHERE status='0' ";
	$getPositions = mysql_query($positionQuery) or die(mysql_error());
	//$positionData = mysql_fetch_assoc($getPositions);
	while($row = mysql_fetch_assoc($getPositions)){
		$position = $row['name'];
		$data[] = $position;
	}
	return $data;
	mysql_close($link_id);
}

function getJobPositionsWithID() {
	$data = array();
	$positionQuery = "SELECT * FROM position WHERE status='0' ";
	$getPositions = mysql_query($positionQuery) or die(mysql_error());
	//$positionData = mysql_fetch_assoc($getPositions);
	$data[0] = "ALL JOB POSITIONS";
	while($row = mysql_fetch_assoc($getPositions)){
		$positionID = $row['id'];
		$position = $row['name'];
		$data[$positionID] = $position;
	}
	return $data;
	mysql_close($link_id);
} 

function getJobPositionID($position) {
	$data = "";
	$positionQuery = "SELECT id FROM position WHERE name='$position' ";
	$getPositions = mysql_query($positionQuery) or die(mysql_error());
	$numRow = mysql_num_rows($getPositions);

	if($numRow>0){
		while($row = mysql_fetch_assoc($getPositions)){
			$id = $row['id'];
			$data = $id;
		}
	}
	else{
		$data = "NO RESULTS FOUND";
	}

	return $data;
	mysql_close($link_id);
}

function getLocationID($location) {
	$data = "";
	$location = explode(",", $location);
	$city = $location[0];
	$province = trim($location[1]);
	$locationQuery = "SELECT location_id FROM location WHERE city = '$city' AND area_name='$province' ";
	$getLocations = mysql_query($locationQuery) or die(mysql_error());
	$numRow = mysql_num_rows($getLocations);
	
	if($numRow>0){
		while($row = mysql_fetch_assoc($getLocations)){
			$id = $row['location_id'];
			$data = $id;
		}
	}
	else{
		$data = "NO RESULTS FOUND";
	}
	return $data;
	mysql_close($link_id);
} 

function getJobPositionName($positionID) {
	$data = "";
	$positionQuery = "SELECT name FROM position WHERE id='$positionID' ";
	$getPositions = mysql_query($positionQuery) or die(mysql_error());
	//$positionData = mysql_fetch_assoc($getPositions);
	if($positionID !== ""){
		if($positionID !== "0"){
			while($row = mysql_fetch_assoc($getPositions)){
				$name = $row['name'];
				$data = $name;
			}
		}
		else{
			$data = "ALL JOB POSITIONS";
		}
	}
	else{
		$data = "Missing data: job position id";
	}
	return $data;
	mysql_close($link_id);
} 

function getJobPositionDateRegistered($positionID) {
	$data = "";
	$positionQuery = "SELECT date_added FROM position WHERE id='$positionID' ";
	$getPositions = mysql_query($positionQuery) or die(mysql_error());
	//$positionData = mysql_fetch_assoc($getPositions);
	while($row = mysql_fetch_assoc($getPositions)){
		$dateAdded = $row['date_added'];
		$data = $dateAdded;
	}
	return $data;
	mysql_close($link_id);
} 



function getJobLocations() {
	$data = array();
	$locationQuery = "SELECT * FROM location ORDER BY area_name ASC";
	$getLocations = mysql_query($locationQuery) or die(mysql_error());
	//$positionData = mysql_fetch_assoc($getPositions);
	while($row = mysql_fetch_assoc($getLocations)){
		$location = $row['city'] . ", " . $row['area_name'];
		$data[] = $location;
	}
	return $data;
	mysql_close($link_id);
}

function getJobLocationsWithID() {
	$data = array();
	$locationQuery = "SELECT * FROM location ORDER BY area_name ASC";
	$getLocations = mysql_query($locationQuery) or die(mysql_error());
	//$positionData = mysql_fetch_assoc($getPositions);
	$data[0] = "ALL JOB LOCATIONS";
	while($row = mysql_fetch_assoc($getLocations)){
		$locationId = $row['location_id'];
		$location = $row['city'] . ", " . $row['area_name'];
		$data[$locationId] = $location;
	}
	return $data;
	mysql_close($link_id);
}

function getCities() {
	$data = array();
	$locationQuery = "SELECT * FROM location ORDER BY area_name ASC";
	$getLocations = mysql_query($locationQuery) or die(mysql_error());
	while($row = mysql_fetch_assoc($getLocations)){
		$city = $row['city'];
		$data[] = $city;
	}
	$data = array_unique($data);
	return $data;
	mysql_close($link_id);
}

function getProvinces() {
	$data = array();
	$locationQuery = "SELECT * FROM location ORDER BY area_name ASC";
	$getLocations = mysql_query($locationQuery) or die(mysql_error());
	while($row = mysql_fetch_assoc($getLocations)){
		$province = $row['area_name'];
		$data[] = $province;
	}
	$data = array_unique($data);
	return $data;
	mysql_close($link_id);
}

function getAllEmployees(){
	$data = array();
	$employeeQuery = "SELECT * FROM employee";
	$getEmployees = mysql_query($employeeQuery) or die(mysql_error());
	while($row = mysql_fetch_assoc($getEmployees)){
		$name = $row['first_name'] . " " . $row['middle_name'] . " " . $row['last_name'];
		$data[] = $name;
	}
	return $data;
	mysql_close($link_id);
}

function getJobPost($jobPostCode, $infoType) {
	$data = "";
	if (isset($jobPostCode) && isset($infoType) ){	
		$jobPostDataQuery = "SELECT * FROM job_post WHERE code ='$jobPostCode' ";
		$getJobPostData = mysql_query($jobPostDataQuery); 
		$jobPostData  = mysql_fetch_assoc($getJobPostData );
		
		if($infoType == "employer-username") {
			$employerUsername = $jobPostData['employer_username'];
			$data = $employerUsername;
		}
		else if($infoType == "company-name") {
			$employerUsername = $jobPostData['employer_username'];
			$companyNameQuery = "SELECT company_name FROM employer WHERE username='$employerUsername' ";
			$getCompanyName = mysql_query($companyNameQuery) or die(mysql_error());
			$companyName = mysql_result($getCompanyName, 0);
			//$companyName = ucwords(strtolower($companyName));
			$data = $companyName;
		}
		elseif($infoType == "location") {
			$location = $jobPostData['location_id'];
			$location = getJobLocation($location);
			$data = $location;
		}
		elseif($infoType == "date-posted") {
			$datePosted = $jobPostData['date_posted'];
			$data = $datePosted;
		}
		elseif($infoType == "job-pos") {
			$jobPosition = $jobPostData['job_position'];
			$data = $jobPosition;
		}
		elseif($infoType == "job-pos-name") {
			$jobPositionID = $jobPostData['job_position'];
			$jobPositionQuery = "SELECT name FROM position WHERE id='$jobPositionID' ";
			$getJobPositionName = mysql_query($jobPositionQuery) or die(mysql_error());
			$jobPositionName = mysql_result($getJobPositionName, 0);
			$data = $jobPositionName;
		}
		elseif($infoType == "job-desc") {
			$jobPosition = $jobPostData['job_desc'];
			$data = $jobPosition;
		}
		elseif($infoType == "num-vacancies") {
			$numVacancies = $jobPostData['num_vacancies'];
			$data = $numVacancies;
		}
		elseif($infoType == "street") {
			$street = $jobPostData['street'];
			$data = $street;
		}
		elseif($infoType == "e-sex") {
			$eSex = $jobPostData['e_sex'];
			$data = $eSex;
		}
		elseif($infoType == "e-civil-stat") {
			$eCivilStatus = $jobPostData['e_civil_status'];
			$data = $eCivilStatus;
		}
		elseif($infoType == "e-min-age") {
			$minAge = $jobPostData['e_min_age'];
			$data = $minAge;
		}
		elseif($infoType == "e-max-age") {
			$maxAge = $jobPostData['e_max_age'];
			$data = $maxAge;
		}
		elseif($infoType == "e-req-age") {
			$minAge = $jobPostData['e_min_age'];
			if($minAge == 0){
				$minAge ="";
			}
			$maxAge = $jobPostData['e_max_age'];
			if($maxAge == 0){
				$maxAge ="";
			}
			if($minAge !="" && $maxAge != ""){

				if($minAge == $maxAge){
					$age = $minAge;
				}
				else{
					$age = $minAge . " to " . $maxAge;	
				}
			}
			else if($minAge == ""){
				$age = $maxAge;
			}
			else if($maxAge == ""){
				$age = $minAge;
			}
			$data = $age;
		}
		elseif($infoType == "e-height") {
			$eHeight = $jobPostData['e_height'];
			$data = $eHeight;
		}
		elseif($infoType == "e-weight") {
			$eWeight = $jobPostData['e_weight'];
			$data = $eWeight;
		}
		elseif($infoType == "e-educ-attainment") {
			$eEducAttainment = $jobPostData['e_educ_attainment'];
			$data = $eEducAttainment;
		}
		elseif($infoType == "job-open-date") {
			$jobOpenDate = $jobPostData['job_opendate'];
			$jobOpenDate = date('F d Y', strtotime($jobOpenDate));
			$data = $jobOpenDate;
		}
		elseif($infoType == "job-close-date") {
			$jobCloseDate = $jobPostData['job_closedate'];
			$jobCloseDate = date('F d Y', strtotime($jobCloseDate));
			$data = $jobCloseDate;
		}
		elseif($infoType == "status1") {
			$status1 = $jobPostData['status1'];
			$data = $status1;
		}
		elseif($infoType == "status2") {
			$status2 = $jobPostData['status2'];
			$data = $status2;
		}
		elseif($infoType == "num-applicants") {
			$getApplicantsQuery = "SELECT * FROM application_history WHERE job_code='$jobPostCode' ";
			$getApplicants = mysql_query($getApplicantsQuery) or die(mysql_error());
			$countApplicants = mysql_num_rows($getApplicants);
			$numApplicants = $countApplicants;
			$data = $numApplicants;
		}
	}
	else{
		$data = "Missing paramaters: username or info type";
	}
	return $data;
	mysql_close($link_id);
}

function getJobLocation($locationId){
	$data = "";
	$locationQuery = "SELECT * FROM location WHERE location_id='$locationId' ";
	$getLocation = mysql_query($locationQuery) or die(mysql_error());
	if($locationId !== ""){
		if($locationId !== "0"){
			while($location = mysql_fetch_assoc($getLocation)){
				$city = $location['city'];
				$province = $location['area_name'];
				$location = $city . ", " . $province;
				$data = $location;
			}
		}
		else{
			$data = "ALL JOB LOCATIONS";
		}
	}
	else{
		$data = "Missing paramaters: location id";
	}
	return $data;
	mysql_close($link_id);
}

function getJobRequirements($jobPostCode){
	$data = "";
	if($jobPostCode !== ""){
		$jobRequirementQuery = "SELECT * FROM requirements WHERE post_code='$jobPostCode' ";
		$getJobRequirements = mysql_query($jobRequirementQuery) or die(mysql_error());
		$numRequirements = mysql_num_rows($getJobRequirements);
		$count = 0;
		while($jobRequirements = mysql_fetch_assoc($getJobRequirements)){
			$requirement = $jobRequirements['req_name'];
			$count += 1;
			if($count < $numRequirements){
				$req .= $requirement . "/ ";
			}
			else if($numRequirements == 1 || $count >= $numRequirements){
				$req .= $requirement;
			}
			$data = $req;
		}
	}
	else{
		$data = "Missing paramaters: job post code";
	}
	return $data;
	mysql_close($link_id);
}
?>