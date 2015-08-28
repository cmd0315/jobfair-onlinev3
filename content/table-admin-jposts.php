<?php
include './includes/functions.php';
include './includes/db.php';
	
//extract variables in link
extract($_GET);
$content = "";

$jobPostQuery = "SELECT code FROM job_post WHERE status2=0 ORDER BY date_posted DESC";
$getJobPosts = mysql_query($jobPostQuery) or die(mysql_error());
$jobPostArray = ""; // container for exporting of reports
$jobPostArr = array();

while($jobPostData = mysql_fetch_assoc($getJobPosts)){
	$jobPostCode = $jobPostData['code'];
	array_push($jobPostArr, $jobPostCode);
}

$jobPostArr = array_unique($jobPostArr);
if($position != ""){
	$jobPostArr = filterJobPostByPosition($jobPostArr, $position);
	//echo count($jobPostArr);
}

if($location != ""){
	$jobPostArr = filterJobPostByLocation($jobPostArr, $location);
	//echo count($jobPostArr);
}

if($dayView != ""){
	$jobPostArr = filterJobPostByDayView($jobPostArr, $dayView);
	//echo count($jobPostArr);
}

$arrayCount = count($jobPostArr);

$content .=<<<EOT
<div class="row-fluid">
	<div class="span12">
		<p><strong>Total Job Posts: <span style="color:#00c2ff;">$arrayCount</span></strong></p>
	</div>
</div>
<div class="row-fluid">
	<div class="span12">
		<table class="display table-striped table-hover table-condensed" width="100%"  id="record_table" style="text-align:center; font-size:11px;">
			<thead>
				<th>#</th>
				<th>Job Position</th>
				<th>Company Name</th>
				<th>Job Location</th>
				<th>Date Posted</th>
			</thead>
			<tbody>
EOT;
						$count = 0;
						foreach($jobPostArr as $jobPostCode){
							$count += 1;
							$companyName = getJobPost($jobPostCode, "company-name");
							//$jobPos = getJobPost($jobPostCode, "job-pos");
							$jobPos = getJobPost($jobPostCode, "job-pos-name");
							$jobLocation = getJobPost($jobPostCode, "location");
							$datePosted = getJobPost($jobPostCode, "date-posted");
							$datePosted = date('Y-m-d', strtotime($datePosted));
							$status1 = getJobPost($jobPostCode, "status1");
							
							$content.="<tr onclick=\"viewSummary('$jobPostCode');\">
												<td>$count</td>";
							if($status1 == 1){
								$content.="<td style=\"cursor:pointer; font-weight:bold; text-decoration:line-through;\" class=\"text-info\">$jobPos</td>
								<td style=\"cursor:pointer; font-weight:bold; text-decoration:line-through;\" class=\"text-info\"><strike>$companyName</td>
								<td style=\"cursor:pointer; font-weight:bold; text-decoration:line-through;\" class=\"text-info\"><strike>$jobLocation</td>
								<td style=\"cursor:pointer; font-weight:bold; text-decoration:line-through;\" class=\"text-info\">$datePosted</td>
								</tr>";
							}
							else{
								$content.="<td style=\"cursor:pointer; font-weight:bold;\">$jobPos</td>
								<td style=\"cursor:pointer;\">$companyName</td>
								<td style=\"cursor:pointer;\">$jobLocation</td>
								<td style=\"cursor:pointer;\">$datePosted</td>
								</tr>";
							}
							$jobPostArray .= $jobPostCode . "-";
						}
			$content .=<<<EOT
			</tbody>
		</table>
	</div>
</div>
EOT;
$arr = array($content, $jobPostArray);
echo json_encode($arr);
?>