<?php
require './includes/db.php';
include './includes/paginate.php';

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

$locationId = $_GET['locationId'];
$jobPositionId = $_GET['jobPositionId'];
$logReference = $locationId . "-" . $jobPositionId;
$pageNum = (int) (!isset($_GET['jobPageNum']) ? 1 :$_GET['jobPageNum']);
$pageNum = ($pageNum == 0 ? 1 : $pageNum);
$prev = $pageNum - 1;
$next = $pageNum + 1;
$content = "";
$location = "";
$position = "";
$errors = 0;
$errorMsg = "<div class=\"span1\"><a class=\"btn btn-mini\" onclick=\"hideJobPostsDiv();\"><i class=\"icon-remove\"></i></a></div>
				<div class=\"span11\" id=\"zoomed-map-error-caption\"><p class=\"alert alert-danger\">";
$searchJobQuery="";
$displayStatMsg = "";
$jobPostsCount = 0;
$resultpPageMax = 12;
$adjacents = 4;
$startPage = ($pageNum-1) * $resultpPageMax;

//set query and display/error messages
if($locationId === "" && $jobPositionId === ""){
	$errors += 1;
	$errorMsg .= "Please select a job location or a job position.";
}
else{
	$searchJobQuery = "SELECT * FROM job_post WHERE status1='0' AND status2='0'";
	$displayStatMsg = "<p class=\"alert alert-info\">You are looking at job availabilities";
	
	if($locationId !== ""){
		if($locationId !== "0"){
			$searchJobQuery .= " AND location_id='$locationId'";
		}
		$location = strtoupper(getJobLocation($locationId));
		$displayStatMsg .= " in <span id=\"zoomed-location\" name=\"zoomed-location\">". $location . "</span>";
		$errorMsgContent .= " in <span id=\"offered-position\" name=\"offered-position\">" . $location . "</span>";
	}
	if($jobPositionId !== ""){
		$position = strtoupper(getJobPositionName($jobPositionId));
		$positionText = "";
		if($jobPositionId !== "0"){
			$searchJobQuery .= " AND job_position='$jobPositionId'";
			$positionText = " position"; 
		}
		$displayStatMsg .= " for <span id=\"offered-position\" name=\"offered-position\">" . $position . "</span>" . $positionText;
		$errorMsgContent .= " for <span id=\"offered-position\" name=\"offered-position\">" . $position . "</span>";
	}
	$origSearchJob = mysql_query($searchJobQuery) or die(mysql_error());
	$origJobPostsCount = mysql_num_rows($origSearchJob);
	$origLastPageNum = ceil($origJobPostsCount/$resultpPageMax);

	//set start limit depending on number of pages
	$searchJobQuery .= " ORDER BY date_posted DESC LIMIT " . $startPage . ", " . $resultpPageMax; 
	
	//with limit
	$searchJob = mysql_query($searchJobQuery) or die(mysql_error());
	$jobPostsCount  = mysql_num_rows($searchJob);
	$lastPageNum = ceil($jobPostsCount/$resultpPageMax);
	
	/*set pagination values*/
	if($lastPageNum<1){ //make sure that last page number is not less than 1
		$lastPageNum = 1;
		$origLastPageNum = 1;
	}

	$displayStatMsg .= ".</p>";
	//error message if no results
	if($jobPostsCount === 0){
		$errors += 1;
		$errorMsg .= "Sorry no job position" . $errorMsgContent . " is available.";
	}
}

/*set content print value*/
$content .="
			<div class=\"span12\">
				<div class=\"row-fluid\">
					<div class=\"span1\">
						<a class=\"btn btn-mini\" onclick=\"hideJobPostsDiv();\" style=\"border:none;\"><i class=\"icon-remove\"></i></a>
					</div>
					<div class=\"span11\">
						<div class=\"span12\" id=\"zoomed-map-caption\">" .
							$displayStatMsg .
						"</div>
					</div>
				</div><br/>
				<div class=\"row-fluid\">
					<div class=\"span10 offset1\">
						<div class=\"accordion\" id=\"accordion2\">";
						while($jobPostDataRow = mysql_fetch_assoc($searchJob)){
							$i += 1;
							$jobPostId = $jobPostDataRow['id'];
							$jpId = "jobId" . $i;
							$jpDId = "jobId" . $i;
							$checkJobBtn = "checkJobBtn" . $i;
							$postCode = $jobPostDataRow['code'];
							$jobPostPosition = $jobPostDataRow['job_position'];
							$jobPostPosition = getJobPositionName($jobPostPosition);
							$companyUsername = $jobPostDataRow['employer_username'];
							$companyName = getEmployerData($companyUsername, 'company-name');
							$jobSite = $jobPostDataRow['location_id'];
							$jobSite = getJobLocation($jobSite);

							if($i == 1){
								$content.="<ul class=\"thumbnails jobThumbnails\">";
							}
								$content.= "<li class=\"span3\">
										<div class=\"thumbnail jobThumbnail\" id=\"$jpDId\" onclick=\"checkJob('$postCode');\" onmouseover=\"selectJobPostDiv('$jpDId');\" onmouseout=\"deselectJobPostDiv('$jpDId');\">
											<div class=\"row-fluid\">
												<div class=\"span11\">
													<div class=\"row-fluid\">
														<div class=\"span12\" style=\"min-height:100px;\">
															<span class=\"company-name\">$companyName</span>
															<p class=\"job-post-desc text-info\"><i class=\"icon-briefcase\"></i> $jobPostPosition</p>
															<p class=\"job-post-location\"><i class=\"icon-road\"></i> $jobSite</p>
														</div>
													</div>
												</div>
												<div class=\"span1\">
													<span class=\"job-post-num\" id=\"$jpId\" name=\'$jpId\'><i class=\"icon-chevron-right\" onclick=\"checkJob('$postCode');\"></i></span>
												</div>
											</div>
										</div></li>";
							if(($i>1) && ($i%4 === 0)){
								$content .="</ul><ul class=\"thumbnails\">";
							}
						}
$content.="				</div>";

	//pagination for filter jobs
	if($origLastPageNum > 1){
		$content .= "<ul class=\"pager\">";
		if ($pageNum > 1){
			$content .= " <li class=\"previous\">
						    <a href=\"#\" class=\"shortPagination\" onclick=\"displayJobs($prev);\">&larr; Newer</a>
						  </li>";
		}
		if($pageNum < $origLastPageNum){
			$content .= "<li class=\"next\">
						    <a href=\"#\" class=\"shortPagination\" onclick=\"displayJobs($next);\">Older &rarr;</a>
						  </li>";
		}
		$content .= "</ul>";

	}

//closing tags
$content.="			</div>
				</div>
			</div>";


//display errorMsg or display results
if($errors>0){
	$errorMsg .= "</p></div>";
	echo $errorMsg;
}
else{
	echo $content;
}

//add log activity
$addLogQuery = "INSERT INTO activity_logs(date_made, username, action, reference_object) VALUES('$currDateTime', '$username', 'SEARCH JOB', '$logReference')";
$addLog = mysql_query($addLogQuery) OR die(mysql_error());
?>