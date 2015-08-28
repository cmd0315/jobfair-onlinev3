<?php
include './includes/db.php';

$jobPostCode= $_GET['jobPostCode'];
$content = "";
$position = "Position";
$location = "Location";
//get job post info
$location = getJobPost($jobPostCode, "location");
$street = getJobPost($jobPostCode, "street");
//$position = getJobPost($jobPostCode, "job-pos");
$position = getJobPost($jobPostCode, "job-pos-name");
$numVacancies = getJobPost($jobPostCode, "num-vacancies");
if($numVacancies > 1){
	$slots = "slots";
}
else{
	$slots = "slot";
}
$description = getJobPost($jobPostCode, "job-desc");
$description = str_replace("\\n","<br />",$description);
$description = str_replace("\\r"," ",$description); 
$sex = getJobPost($jobPostCode, "e-sex");
if($sex == "M"){
	$sex = "Male";
}
else if($sex == "F"){
	$sex = "Female";
}
else if($sex == "NR"){
	$sex = "";
}

$civilStatus = getJobPost($jobPostCode, "e-civil-stat");
$age = getJobPost($jobPostCode, "e-req-age");
$weight = getJobPost($jobPostCode, "e-weight");
$educAttainment = getJobPost($jobPostCode, "e-educ-attainment");
$status1 = getJobPost($jobPostCode, "status1");
if($status1 == "1"){
	$status = "<span class='text-error'>CLOSED</span>";
}
else{
	$status = "<span class='text-success'>LISTED</span>";
}
$jobOpenDate = getJobPost($jobPostCode, "job-open-date");
$jobCloseDate = getJobPost($jobPostCode, "job-close-date");

$getJobPostRequirementsQuery = "SELECT * FROM requirements WHERE post_code='$jobPostCode' ";
$getJobPostRequirements = mysql_query($getJobPostRequirementsQuery) or die(mysql_error());
$content .="<div class=\"modal-header\">
						<h4 class=\"content-heading3\">Preview Job Post</h4>
					</div>
					<div class=\"modal-body\">
						<div class=\"row-fluid\">
							<div class=\"span7\">
								<p>Location: <span style=\"font-weight:bold; color:#089DFF;\">$street, $location</span></p>
								<p>Position: <span style=\"font-weight:bold; color:#089DFF;\">$position</span></p>
								<p>Vacancies: <span style=\"font-weight:bold; color:#089DFF;\">$numVacancies $slots</span></p>
								<p>Work Description: <br/><em>$description</em></p>
									<p>JOB REQUIREMENTS:</p>
									<ul>";
									if($sex != ""){
										$content.= "<li>Gender: $sex</li>";
									}
									if($civilStatus != ""){
										$content.= "<li>Civil Status: $civilStatus</li>";
									}
									if($age != ""){
										$content.= "<li>Age Bracket: $age yrs. old</li>";
									}
									if($weight != ""){
										$content.= "<li>Weight: $weight lbs.</li>";
									}
									if($educAttainment != ""){
										$content.= "<li>Educ. Attainment: $educAttainment</li>";
									}
									while($jobPostRequirementData = mysql_fetch_assoc($getJobPostRequirements)){
										$jPRequirement = $jobPostRequirementData['req_name'];
										$content.= "<li>$jPRequirement</li>";
									}
$content .="				</ul>";
									
$content .="			</div>
							<div class=\"span3 offset2\">
								<p>Status: $status</p>
								<p>Date Opened: $jobOpenDate</p>
								<p>Expiration Date: $jobCloseDate</p>
							</div>
						</div>
					</div>
					<div class=\"modal-footer\">
						<div class=\"row-fluid\">
							<div class=\"span4 offset4\">
								<button class=\"btn btn-primary span12\" data-dismiss=\"modal\" style=\"font-size:12px;\">OK</button>
							</div>
						</div>
					</div>";
echo $content;
?>