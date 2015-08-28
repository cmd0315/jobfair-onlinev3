<?php
include './includes/functions.php';
include './includes/db.php';
	
//extract variables in link
extract($_GET);
$content = "";

$jobPosition = getJobPost($jobPostCode, "job-pos");
$jobPosition = getJobPost($jobPostCode, "job-pos-name");
$applicantsQuery = "SELECT employee.username FROM employee INNER JOIN account ON employee.username = account.username  ORDER BY account.date_joined DESC";
$getApplicants = mysql_query($applicantsQuery) or die(mysql_error());

$applicantArray = ""; // container for exporting of reports
$applicantArr = array();

while($applicantData = mysql_fetch_assoc($getApplicants)){
	$username = $applicantData['username'];
	$employeeInterestsQuery = "SELECT * FROM interest WHERE employee_username='$username' ";
	$getEmployeeInterests = mysql_query($employeeInterestsQuery) or die(mysql_error());
	$interests = array();
	while($employeeInterests = mysql_fetch_assoc($getEmployeeInterests)){
		$empInterestID = $employeeInterests['position_id'];
		$empInterest  = getJobPositionName($empInterestID);
		array_push($interests, $empInterest);
	}
	if(in_array($jobPosition, $interests)){
		array_push($applicantArr, $username);
	}
}
$applicantArr = array_unique($applicantArr);

if($minAge != 0 || $maxAge != 0){
	$applicantArr = filterByAge($applicantArr, $minAge, $maxAge);
	//echo count($applicantArr);
}

if($gender != ""){
	$applicantArr = filterByGender($applicantArr, $gender);
//	echo count($applicantArr);
}

if($heightFt != "" || $heightIn != ""){
	$applicantArr = filterByHeight($applicantArr, $heightFt, $heightIn);
//	echo count($applicantArr);
}

if($civilStatus !== ""){
	$applicantArr = filterByCivilStatus($applicantArr, $civilStatus);
//	echo count($applicantArr);
}

if($educAttainment != ""){
	$applicantArr = filterByEducAttainment($applicantArr, $educAttainment);
	//echo count($applicantArr);
}

if($location != ""){
	$applicantArr = filterByLocation($applicantArr, $location);
	//echo count($applicantArr);
}
$arrayCount = count($applicantArr);

$content .=<<<EOT
<div class="row-fluid">
	<div class="span12">
		<p><strong>Total Applicants: <span style="color:#00c2ff;">$arrayCount</span></strong></p>
	</div>
</div>
<div class="row-fluid">
	<div class="span12">
		<table class="display table-striped table-hover table-condensed" width="100%"  id="record_table" style="text-align:center; font-size:11px;">
			<thead>
				<th>#</th>
				<th>Applicant Name</th>
				<th>Age</th>
				<th>Sex</th>
				<th>Education</th>
				<th>Location</th>
				<th>Date Registered</th>
			</thead>
			<tbody>
EOT;
						foreach($applicantArr as $username){
							$fullName = getEmployeeData($username, "full-name");
							$age = getEmployeeData($username, "age");
							$sex = getEmployeeData($username, "sex");
							$address = getEmployeeData($username, "address");
							$dateRegistered = getAcctInfo($username, "date-joined");
							$dateRegistered = date('Y-m-d', strtotime($dateRegistered));
							$applicantHSEduc = getEmployeeData($username, "hs_educ");
							$applicantCollegeEduc = getEmployeeData($username, "college_educ");
							$applicantVocEduc = getEmployeeData($username, "voc_educ");
							$highestEduc = "";
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
							$content.="<tr>
							<td><input type=\"radio\" name=\"applicant\" id=\"applicant\" value=\"$username\"></td>
							<td onclick=\"viewSummary('$username');\" style=\"text-align:left; cursor:pointer;\"><span class=\"extra-label3\">$fullName</span></td>
							<td onclick=\"viewSummary('$username');\" style=\"cursor:pointer;\">$age</td>
							<td onclick=\"viewSummary('$username');\" style=\"cursor:pointer;\">$sex</td>
							<td onclick=\"viewSummary('$username');\" style=\"cursor:pointer;\">$highestEduc</td>
							<td onclick=\"viewSummary('$username');\" style=\"cursor:pointer;\">$address</td>
							<td onclick=\"viewSummary('$username');\" style=\"cursor:pointer;\">$dateRegistered</td>
							</tr>";
								$applicantArray .= $username . "-";
						}
			$content .=<<<EOT
			</tbody>
		</table>
	</div>
</div>
EOT;
$arr = array($content, $applicantArray);
echo json_encode($arr);
?>