<?php
include './includes/functions.php';
include './includes/db.php';
include './includes/paginate.php';

$error = 0;
/**START get applicant list queries**/
$getTotalNewJobsQuery = "SELECT * FROM job_post WHERE (status1='0' AND status2='0')";
$linkVariables = array();
/**END get applicant list queries**/

$getTotalNewJobs = mysql_query($getTotalNewJobsQuery) or die(mysql_error());
$totalNewJobs = mysql_num_rows($getTotalNewJobs);

/**START pagination**/
$page = (int) (!isset($_GET['pageNum']) ? 1 :$_GET['pageNum']);
$page = ($page == 0 ? 1 : $page);
$resultpPageMax = 9; //set number of applicant rows to be displayed
$startPage = ($page-1) * $resultpPageMax;
$adjacents = "4"; //determines how many pages for navogation will be displayed
$pagination = paginateResults($page, $resultpPageMax, $adjacents, $totalNewJobs);   
/**END pagination**/


/**START results**/
$getTotalNewJobsQuery .= " ORDER BY date_posted DESC LIMIT " . $startPage . " ," . $resultpPageMax;
$getLimitedNewJobs = mysql_query($getTotalNewJobsQuery) or die(mysql_error());
$totalLimitedNewJobs = mysql_num_rows($getLimitedNewJobs);
$content = "";

$content .= "<div class=\"row-fluid resultsDataTableDiv\">
				<div class=\"span12\">";
if($totalLimitedNewJobs > 0){
	$newJPColumn = 3;
	$newJPContent = "";
	while($newJobPostRow = mysql_fetch_assoc($getLimitedNewJobs)){
		$newJobPostsCounter += 1;
		$newJPCode = $newJobPostRow['code'];
		$checkJobBtn = "checkJobBtn" . $newJobPostsCounter;
		$newJPPosition = $newJobPostRow['job_position'];
		$newJPPosition = getJobPositionName($newJPPosition);
		$newJPCompanyUsername = $newJobPostRow['employer_username'];
		$newJPCompanyName = getEmployerData($newJPCompanyUsername, 'company-name');
		$newJPJobSite = $newJobPostRow['location_id'];
		$newJPJobSite = getJobLocation($newJPJobSite);
		//format job description
		$newJPDesc = $newJobPostRow['job_desc'];
		$newJPDesc = htmlspecialchars($newJPDesc);
		$newJPDesc = str_replace("\\n","<br />",$newJPDesc);
		$newJPDesc = str_replace("\\r"," ",$newJPDesc); 
		$newJPDesc = str_replace("\\"," ",$newJPDesc); 
		$newJPDesc = str_replace("<p>"," ",$newJPDesc); 
		
		$newJPDateTimeStamp = $newJobPostRow['date_posted'];
		$newJPDateTimeStamp = strtotime($newJPDateTimeStamp);
		$newJPDatePosted = date('M., d Y', $newJPDateTimeStamp);
		$newJPTimePosted = date('g:i a', $newJPDateTimeStamp);
		if($newJobPostsCounter == 1){
			$newJPContent .= "<div class=\"span4\">";
		}
		$newJPContent .= "<div class=\"row-fluid newJPItemDiv\">
							<div class=\"span12\">
								<div class=\"row-fluid newJPItemUpperDiv\">
			          				<h5 class=\"newJPPositionTitle\" onclick=\"checkJob('$newJPCode');\" style=\"cursor:pointer;\">$newJPPosition</h5>
			          				<p><span class=\"newJPCompanyName\">$newJPCompanyName</span>, $newJPJobSite</p>
			          				<p>$newJPDesc</p>
			          			</div>
		          				<div class=\"row-fluid newJPItemBottomDiv\">
		          					<div class=\"span6\">
		          						<p>Posted:</p>
		          						<p>$newJPDatePosted - $newJPTimePosted</p>
		          					</div>
		          					<div class=\"span6\">
		          						<button type=\"submit\" class=\"btn btn-primary span10 offset1 applyNowBtn\" id=\"$checkJobBtn\" name=\"$checkJobBtn\" onclick=\"checkJob('$newJPCode');\">APPLY NOW!</button>
		          					</div>
		          				</div>
					        </div>
					      </div>";
		if(($newJobPostsCounter>1) && ($newJobPostsCounter%$newJPColumn === 0)){
			$newJPContent .="</div><div class=\"span4\">";
		}
	}
	$content .= $newJPContent;
}
else{
	$error += 1;
	$content .= "<span class=\"alert alert-error\">ERROR: NO RESULTS FOUND!</span>";
}
	
$content.="</div></div>";

/**END results**/

/*START print outputs*/
if($error===0)
	echo $content . $pagination;
else
	echo $content;
/*END print outputs*/
?>