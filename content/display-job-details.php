<?php
require './includes/db.php';

//GET CURRENT DATE
date_default_timezone_set('Singapore');
$currDateTime = date("Y-m-d H:i:s");

/* GET SESSION DATA*/
session_start();
if(isset($_SESSION['SRIUsername']) && $_SESSION['SRIUsername'] != ""){
	$username = $_SESSION['SRIUsername'];
}
else{
	$username = "PUBLIC"; //for log entry
}

$jobPostCode= $_GET['jobPostCode'];
$shareLink= $_GET['shareLink'];
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
$description = htmlspecialchars($description);
$description = str_replace("\\n","<br />",$description);
$description = str_replace("\\r"," ",$description); 
$description = str_replace("\\"," ",$description); 
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

$civilStatus =  getJobPost($jobPostCode, "e-civil-stat");
$age = getJobPost($jobPostCode, "e-req-age");
$weight = getJobPost($jobPostCode, "e-weight");
$educAttainment = getJobPost($jobPostCode, "e-educ-attainment");


$getJobPostRequirementsQuery = "SELECT * FROM requirements WHERE post_code='$jobPostCode' ";
$getJobPostRequirements = mysql_query($getJobPostRequirementsQuery) or die(mysql_error());
$content .="<div class=\"modal-header\">
						<h4 class=\"content-heading3\">Are you what they are looking for?</h4>
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
									if($civilStatus != "" && $civilStatus != "Not Required"){
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
$content .="				</ul>
							</div>
							<div class=\"span5\">
								<span style=\"color:#089DFF;\">Let others know about this job!</span>
								<!-- AddThis Button BEGIN -->
									<div class=\"addthis_toolbox addthis_default_style addthis_32x32_style\" addthis:url=\"http://jobfair-online.net/content/$shareLink\">
									<a class=\"addthis_button_facebook\"></a>
									<a class=\"addthis_button_twitter\"></a>
									<a class=\"addthis_button_google_plusone_share\"></a>
									<a class=\"addthis_button_email\"></a>
									</div>
									<script type=\"text/javascript\">var addthis_config = {\"data_track_addressbar\":true};</script>
									<script type=\"text/javascript\" src=\"//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-51e2c4c131144687\"></script>
								<!-- AddThis Button END -->
							</div>";
$content.="	</div>
					</div>
					<div class=\"modal-footer\">
						<div class=\"row-fluid\">
							<div class=\"span8 offset2\">
								<button class=\"btn btn-primary span7\" data-dismiss=\"modal\" aria-hidden=\"true\">CONTINUE BROWSING</button>
								<button class=\"btn btn-primary span5\" onclick='applyToJob($jobPostCode);'>APPLY!</button>
							</div>
						</div>
					</div>";

//add log activity
$addLogQuery = "INSERT INTO activity_logs(date_made, username, action, reference_object) VALUES('$currDateTime', '$username', 'VIEW JOB POST', '$jobPostCode')";
$addLog = mysql_query($addLogQuery) OR die(mysql_error());

echo $content;
?>