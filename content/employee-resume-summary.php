<?php
include './includes/db.php';

$applicant= $_GET['username'];
$content = "";

//get applicant nfo
$applicantName = getEmployeeData($applicant, "full-name");
$applicantPicSrc = getEmployeeData($applicant, "profile_pic");
if($applicantPicSrc === Null || !(file_exists($applicantPicSrc))){
	$applicantPicSrc = "./img/id.png";
}
$applicantSex = getEmployeeData($applicant, "sex");
$applicantBirthDate= getEmployeeData($applicant, "birth_date");
$applicantBirthDate= date('F d Y', strtotime($applicantBirthDate));
$applicantBirthPlace= getEmployeeData($applicant, "birth_place");
$applicantAge = getEmployeeData($applicant, "age");
$applicantCivilStatus = getEmployeeData($applicant, "civil_status");
$applicantNumChildren = getEmployeeData($applicant, "num_children");
$applicantHSEduc = getEmployeeData($applicant, "hs_educ");
$applicantCollegeEduc = getEmployeeData($applicant, "college_educ");
$applicantVocEduc = getEmployeeData($applicant, "voc_educ");
$applicantCityAddress= getEmployeeData($applicant, "city1");
$applicantProvAddress= getEmployeeData($applicant, "province1");
$applicantCompleteAddress= getEmployeeData($applicant, "complete_address"); 
$applicantHeight = getEmployeeData($applicant, "height");
$applicantWeight = getEmployeeData($applicant, "weight");
$applicantMobile = getEmployeeData($applicant, "mobile");
$applicantEmail = getEmployeeData($applicant, "email");
$applicantLandline = getEmployeeData($applicant, "landline");
$highestEduc = "";
if($applicantCollegeEduc === ""){
	if($applicantVocEduc !== ""){
		$highestEduc = "Vocational Course" . " " .  $applicantVocEduc;
	}
	else
		$highestEduc = "High School" . " " .  $applicantHSEduc;
}
else
	$highestEduc = "College" . " " . $applicantCollegeEduc;
//GET EMPLOYEE INTERESTS
$employeeInterestQuery = "SELECT * FROM interest WHERE employee_username='$applicant' ";
$getEmployeeInterest = mysql_query($employeeInterestQuery) or die(mysql_error());
$interestArr = array();
$content .="<div class=\"modal-header\">
						<h4 class=\"content-heading3\">Preview Employee Resume</h4>
					</div>
					<div class=\"modal-body\">
						<div class=\"row-fluid\">
							<div class=\"span9\">
								<p><span style=\"font-weight:bold; color:#089DFF;\">Name:</span> $applicantName</p>
								<p><span style=\"font-weight:bold; color:#089DFF;\">Address:</span></br>$applicantCompleteAddress</p>
								<p><span style=\"padding-right:20px;\"><span style=\"font-weight:bold; color:#089DFF;\">Age:</span> $applicantAge years old</span><span style=\"font-weight:bold; color:#089DFF;\">Birth Place:</span> $applicantBirthPlace</p>
								<p><span style=\"padding-right:20px;\"><span style=\"font-weight:bold; color:#089DFF;\">Sex:</span> $applicantSex </span><span style=\"padding-right:20px;\"><span style=\"font-weight:bold; color:#089DFF;\">Civil Status:</span> $applicantCivilStatus</span><span style=\"font-weight:bold; color:#089DFF;\">Number of Children:</span> $applicantNumChildren</p>
								<p><span style=\"padding-right:100px;\"><span style=\"font-weight:bold; color:#089DFF;\">Height:</span> $applicantHeight</span><span style=\"font-weight:bold; color:#089DFF;\">Weight:</span> $applicantWeight lbs.</p>
								<p><span style=\"font-weight:bold; color:#089DFF;\">Highest Educational Attainment: </span>$highestEduc</p>
								<div class=\"row-fluid\">
									<div class=\"span6\">
										<p style=\"font-weight:bold; color:#089DFF;\">Interests:</p>
										<ul>";
										while($employeeInterest = mysql_fetch_assoc($getEmployeeInterest)){
											$positionID= $employeeInterest['position_id'];
											$interest = getJobPositionName($positionID);
											array_push($interestArr, $interest);
										}
										$interestArr = array_unique($interestArr);
										foreach($interestArr as $interest){
											$content .= "<li>$interest</li>";
										}
$content .="					</ul>
									</div>
									<div class=\"span6\">
										<p style=\"font-weight:bold; color:#089DFF;\">Contact Numbers:</p>
										<ul>";
										if($applicantEmail != ""){
											$content.= "<li>$applicantEmail</li>";
										}
										if($applicantMobile != ""){
											$applicantMobile = substr($applicantMobile, 0, -7) . "-" . substr($applicantMobile, 4, -4) . "-" . substr($applicantMobile, 7);
											$content.= "<li>$applicantMobile</li>";
										}
										if($applicantLandline != ""){
											$applicantLandline = substr($applicantLandline, 0, -4) . "-" . substr($applicantLandline, 3, -2) . "-" . substr($applicantLandline, 5);
											$content.= "<li>$applicantLandline</li>";
										}
$content .="				</ul>
								</div>
								</div>
							</div>
							<div class=\"span3\">
								<img src='$applicantPicSrc'/>
							</div>";
$content.="	</div>
					</div>
					<div class=\"modal-footer\">
						<div class=\"row-fluid\">
							<div class=\"span4 offset2\">
								<button class=\"btn btn-primary span12\" id=\"editBtn\" name=\"editBtn\" data-dismiss=\"modal\" style=\"font-size:12px;\">OK</button>
							</div>
							<div class=\"span4\">
								<button class=\"btn btn-primary span12\" id=\"deleteBtn\" name=\"deleteBtn\" onclick=\"viewResumeEmp('$applicant');\" style=\"font-size:12px;\">VIEW FULL RESUME</button>
							</div>
						</div>
					</div>";
echo $content;

?>