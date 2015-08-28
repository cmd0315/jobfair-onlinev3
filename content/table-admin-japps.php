<?php
include './includes/functions.php';
include './includes/db.php';
	
//extract variables in link
extract($_GET);
$content = "";

$jobPostQuery = "SELECT job_post.*,  COUNT(application_history.id) as total_applications FROM job_post  LEFT JOIN application_history ON  job_post.code = application_history.job_code  WHERE status2 = '0' GROUP BY job_post.code ORDER BY COUNT(application_history.id) DESC, job_post.date_posted DESC";
$getJobPosts = mysql_query($jobPostQuery) or die(mysql_error());
$jobApplicationsArray = ""; // container for exporting of reports
$jobApplicationsArr = array();

$jobApplicationsArr = array_unique($jobApplicationsArr);
while($jobPostData = mysql_fetch_assoc($getJobPosts)){
	$jobPostCode = $jobPostData['code'];
	array_push($jobApplicationsArr, $jobPostCode);
}

if($position != ""){
	$jobApplicationsArr = filterJobPostByPosition($jobApplicationsArr, $position);
	//echo count($jobApplicationsArr);
}

if($location != ""){
	$jobApplicationsArr = filterJobPostByLocation($jobApplicationsArr, $location);
	//echo count($jobApplicationsArr);
}

if($dayView != ""){
	$jobApplicationsArr = filterJobPostByDayView($jobApplicationsArr, $dayView);
	//echo count($jobApplicationsArr);
}

if($status != ""){
	$jobApplicationsArr = filterJobPostByStatus($jobApplicationsArr, $status);
	//echo count($jobApplicationsArr);
}

$arrayCount = count($jobApplicationsArr);

$content .=<<<EOT
<div class="row-fluid">
	<div class="span12">
		<p><strong>Total Job Applications: <span style="color:#00c2ff;">$arrayCount</span></strong></p>
	</div>
</div>
<div class="row-fluid">
	<div class="span12">
		<table class="display table-striped table-hover table-condensed" width="100%"  id="record_table" style="text-align:center; font-size:11px;">
			<thead>
				<th>#</th>
				<th>Job Position</th>
				<th>Job Location</th>
				<th>Company Name</th>
				<th># of Vacancies</th>
				<th># of Applicants</th>
				<th>Date Posted</th>
			</thead>
			<tbody>
EOT;
						$count = 0;
						foreach($jobApplicationsArr as $jobPostCode){
							$count += 1;
							$companyName = getJobPost($jobPostCode, "company-name");
							//$jobPos = getJobPost($jobPostCode, "job-pos");
							$jobPos = getJobPost($jobPostCode, "job-pos-name");
							$jobLocation = getJobPost($jobPostCode, "location");
							$datePosted = getJobPost($jobPostCode, "date-posted");
							$datePosted = date('Y-m-d', strtotime($datePosted));
							$status1 = getJobPost($jobPostCode, "status1");
							$numVacancies = getJobPost($jobPostCode, "num-vacancies");
							$numApplicants = getJobPost($jobPostCode, "num-applicants");
							
							$content.="<tr onclick=\"viewSummary('$jobPostCode');\">
												<td>$count</td>";
							if($status1 == 1){
								$content.="<td style=\"cursor:pointer; font-weight:bold; text-decoration:line-through;\" class=\"text-info\">$jobPos</td>
								<td style=\"cursor:pointer; font-weight:bold; text-decoration:line-through;\" class=\"text-info\"><strike>$jobLocation</td>
								<td style=\"cursor:pointer; font-weight:bold; text-decoration:line-through;\" class=\"text-info\"><strike>$companyName</td>
								<td style=\"cursor:pointer; text-decoration:line-through;\" class=\"text-info\">$numVacancies</td>
								<td style=\"cursor:pointer; text-decoration:line-through;\" class=\"text-info\">$numApplicants</td>
								<td style=\"cursor:pointer; font-weight:bold; text-decoration:line-through;\" class=\"text-info\">$datePosted</td>
								</tr>";
							}
							else{
								$content.="<td style=\"cursor:pointer; font-weight:bold;\">$jobPos</td>
								<td style=\"cursor:pointer;\">$jobLocation</td>
								<td style=\"cursor:pointer;\">$companyName</td>
								<td style=\"cursor:pointer;\">$numVacancies</td>
								<td style=\"cursor:pointer;\">$numApplicants</td>
								<td style=\"cursor:pointer;\">$datePosted</td>
								</tr>";
							}
							$jobApplicationsArray .= $jobPostCode . "-";
						}
			$content .=<<<EOT
			</tbody>
		</table>
	</div>
</div>
EOT;
$arr = array($content, $jobApplicationsArray);
echo json_encode($arr);
?>