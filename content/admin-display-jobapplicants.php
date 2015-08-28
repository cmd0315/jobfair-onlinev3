<?php
include './includes/db.php';

$jobPostCode= $_GET['jobPostCode'];
$content = "";
$position = "Position";
$location = "Location";
//get job post info
$location = getJobPost($jobPostCode, "location");
$street = getJobPost($jobPostCode, "street");
$companyName = getJobPost($jobPostCode, "company-name");
$position = getJobPost($jobPostCode, "job-pos-name");
$numVacancies = getJobPost($jobPostCode, "num-vacancies");
if($numVacancies > 1){
	$slots = "slots";
}
else{
	$slots = "slot";
}
$description = getJobPost($jobPostCode, "job-desc");
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

$getJobApplicantsQuery = "SELECT application_history.*, employee.* FROM application_history, employee WHERE application_history.employee_username=employee.username AND job_code='$jobPostCode' ";
$getJobApplicants = mysql_query($getJobApplicantsQuery) or die(mysql_error());
$content .="<div class=\"modal-header\">
						<h4 class=\"content-heading3\">List of Job Applicants</h4>
					</div>
					<div class=\"modal-body\">
						<div class=\"row-fluid\">
							<div class=\"span6\">
								<p>Company Name: <span style=\"font-weight:bold; color:#089DFF;\">$companyName</span></p>
								<p>Vacancies: <span style=\"font-weight:bold; color:#089DFF;\">$numVacancies $slots</span></p>
							</div>
							<div class=\"span6\">
								<p>Position: <span style=\"font-weight:bold; color:#089DFF;\">$position</span></p>
							</div>
						</div>
						<div class=\"row-fluid\">
							<div class=\"span12\">
									<p style=\"font-weight:bold;\">JOB APPLICANTS:</p>
									<table class=\"table table-bordered table-condensed applicantsListTable\">
										<thead>
											<tr>
												<th>Name</th>
												<th>Address</th>
												<th>Mobile Num</th>
												<th>Email Address</th>
											</tr>
										</thead>
										<tbody>";
									while($jobApplicantsData = mysql_fetch_assoc($getJobApplicants)){
										$applicantUsername = $jobApplicantsData['employee_username'];
										$applicantName = $jobApplicantsData['first_name'] . " " . $jobApplicantsData['middle_name'] . " " . $jobApplicantsData['last_name']; 
										$applicantAddress = $jobApplicantsData['city1'] . ", " . $jobApplicantsData['province1'];
										$applicantEmail = strtolower($jobApplicantsData['email']);
										$content.= "<tr>";
										$content.= "<td>$applicantName</td>";
										$content.= "<td>$applicantAddress</td>";
										$content.= "<td>$applicantUsername</td>";
										$content.= "<td>$applicantEmail</td>";
										$content.= "</tr>";
									}
$content .="							</tbody>
								</table>
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