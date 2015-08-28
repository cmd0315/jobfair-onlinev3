<?php
include './includes/functions.php';
include './includes/db.php';
include './includes/paginate.php';

$error = 0;

if(isset($_GET['jobPostCode'])){
	$jobPostCode = (string)($_GET['jobPostCode']);
	$jobID = getJobPost($jobPostCode, "job-pos");
	$jobPositionName = getJobPositionName($jobID);
}

/**START get applicant list queries**/
if(isset($_GET['type'])){
	$applicationType = $_GET['type'];
	if($applicationType==="qualified"){
		//$getTotalApplicantsQuery = "SELECT * FROM (SELECT employee_username FROM INTEREST WHERE position_id='$jobID' UNION SELECT employee_username FROM WORK_HISTORY WHERE position LIKE '%$jobPositionName%') AS username INNER JOIN ACCOUNT ON username = account.username";
		$getTotalApplicantsQuery = "SELECT * FROM employee INNER JOIN account ON employee.username = account.username INNER JOIN interest ON employee.username = interest.employee_username WHERE interest.position_id='$jobID'";
	}
	else if($applicationType==="interested"){
		$getTotalApplicantsQuery = "SELECT * FROM employee INNER JOIN account ON employee.username = account.username INNER JOIN application_history ON employee.username = application_history.employee_username WHERE application_history.job_code='$jobPostCode'";
	}
	
	$linkVariables = array();
}
/**END get applicant list queries**/

if(isset($_GET['minAge'])){
	$minAge = $_GET['minAge'];
	$minYrVar = '-' . $minAge . " years";
	$minBDate = date('Y-m-d', strtotime($minYrVar));
	$linkString = "birth_date<='". $minBDate . "'";
	array_push($linkVariables, $linkString);
}

if(isset($_GET['maxAge'])){
	$maxAge = $_GET['maxAge'];
	$minYrVar = '-' . $maxAge . " years";
	$minBDate = date('Y-m-d', strtotime($minYrVar));
	$linkString = "birth_date>='". $minBDate . "'";
	array_push($linkVariables, $linkString);
}

if(isset($_GET['gender'])){
	$gender = $_GET['gender'];
	$linkString = "sex='". $gender . "'";
	array_push($linkVariables, $linkString);
}

if(isset($_GET['height'])){
	$height = $_GET['height'];
	$height = explode("'", $height);
	$heightFt = $height[0];
	$heightIn = $height[1];
	if($heightIn != ""){
		$linkString = "height REGEXP '^". $heightFt . "' AND height REGEXP '" . $heightIn . "$'";
	}
	else{
		$linkString = "height REGEXP '^". $heightFt . "'";
	}
	
	array_push($linkVariables, $linkString);
}

if(isset($_GET['weight'])){
	$weight = $_GET['weight'];
	$linkString = "weight='". $weight . "'";
	array_push($linkVariables, $linkString);
}

if(isset($_GET['civilStatus'])){
	$civilStatus = $_GET['civilStatus'];
	$linkString = "civil_status='". $civilStatus . "'";
	array_push($linkVariables, $linkString);
}

if(isset($_GET['location'])){
	$location = $_GET['location'];
	$location = explode(",", $location);
	$city = trim($location[0]);
	$province = trim($location[1]);
	$linkString = "city1='". $city . "' AND province1='" . $province . "'";
	array_push($linkVariables, $linkString);
}

if(isset($_GET['educationalAttainment'])){
	$educationalAttainment = $_GET['educationalAttainment'];
	$undergraduate = "Undergraduate";
	$graduate = "Graduate";
	$graduateWithHonors = "Graduate with Honors";
	switch($educationalAttainment){
		case "High School Undergraduate":
			$linkString = "hs_educ='" . $undergraduate . "'";
			break;
		case "High School Graduate":
			$linkString = "hs_educ='" . $graduate . "'";
			break;
		case "High School Graduate with Honors":
			$linkString = "hs_educ='" . $graduateWithHonors . "'";
			break;
		case "College Level":
			$linkString = "college_educ='" . $undergraduate . "'";
			break;
		case "College Graduate":
			$linkString = "college_educ='" . $graduate . "'";
			break;
		case "College Graduate with Honors":
			$linkString = "college_educ='" . $graduateWithHonors . "'";
			break;
		case "Vocational School Undergraduate":
			$linkString = "voc_educ='" . $undergraduate . "'";
			break;
		case "Vocational School Graduate":
			$linkString = "voc_educ='" . $graduate . "'";
			break;
		case "Vocational School Graduate with Honors":
			$linkString = "voc_educ='" . $graduateWithHonors . "'";
			break;
		default:
			$linkString = "";
	}
	array_push($linkVariables, $linkString);
}

if(isset($_GET['startDate'])){
	$startDate = $_GET['startDate'];
	$linkString = "account.date_joined>='". $startDate . "'";
	array_push($linkVariables, $linkString);
}

if(isset($_GET['endDate'])){
	$endDate = $_GET['endDate'];
	$linkString = "account.date_joined<='". $endDate . "'";
	array_push($linkVariables, $linkString);
}

/*END get link values*/

/*START create query filter values*/
	$lvCount = 0;
	foreach($linkVariables as $lV){
		$getTotalApplicantsQuery .= " AND " . $lV;
		$lvCount += 1;
	}
/*END create query filter values*/


$getTotalApplicants = mysql_query($getTotalApplicantsQuery) or die(mysql_error());
$totalApplicants = mysql_num_rows($getTotalApplicants);

/**START pagination**/
$page = (int) (!isset($_GET['pageNum']) ? 1 :$_GET['pageNum']);
$page = ($page == 0 ? 1 : $page);
$resultpPageMax = 30; //set number of applicant rows to be displayed
$startPage = ($page-1) * $resultpPageMax;
$adjacents = 4; //determines how many pages for navigation will be displayed
$pagination = paginateResults($page, $resultpPageMax, $adjacents, $totalApplicants);
/**END pagination**/

/**START results**/
$getTotalApplicantsQuery .= " ORDER BY account.date_joined DESC LIMIT " . $startPage . " ," . $resultpPageMax;
$getLimitedApplicants = mysql_query($getTotalApplicantsQuery) or die(mysql_error());
$totalLimitedApplicants = mysql_num_rows($getLimitedApplicants);
//$totalLimitedLastPageNum = ceil($totalLimitedApplicants/$resultpPageMax);
$content = "";

$content .= "<div class=\"row-fluid resultsDataTableDiv\">
				<div class=\"span12\">";
if($totalLimitedApplicants > 0){
	$content .= "<table class=\"table table-striped table-hover table-condensed resultsDataTable\">
					<tr>
						<th>#</th>
						<th></th>
						<th>Applicant Name</th>
						<th>Age</th>
						<th>Sex</th>
						<th>Civil Status</th>
						<th>Height</th>
						<th>Weight</th>
						<th>Education</th>
						<th>Location</th>
						<th>Date Registered</th>
					</tr>";

	$count = 0;
	while($applicantRow = mysql_fetch_assoc($getLimitedApplicants)){
		$count += 1;
		$countDisplay = $startPage + $count;
		$applicantUsername = $applicantRow['username'];
		$applicantName = $applicantRow['first_name'] . " " . $applicantRow['middle_name'] . " " . $applicantRow['last_name'];
		$applicantName = ucwords((strtolower($applicantName)));
		$applicantBirthDate = $applicantRow['birth_date'];
		$applicantBirthDate = explode("-", $applicantBirthDate);
		 //get age from date or birthdate
		$applicantAge = (date("md", date("U", mktime(0, 0, 0, $applicantBirthDate[2], $applicantBirthDate[1], $applicantBirthDate[0]))) > date("md") ? ((date("Y")-$applicantBirthDate[0])-1):(date("Y")-$applicantBirthDate[0]));
		if($applicantAge < 17 || $applicantAge > 60){
			$applicantAge = "-";
		}
		$applicantGender = $applicantRow['sex'];
		$applicantCivilStatus= $applicantRow['civil_status'];
		$applicantHeight = $applicantRow['height'];
		$applicantWeight = $applicantRow['weight'];
		$applicantEducationalAttainment = getEmployeeData($applicantUsername, "educational_attainment");
		$applicantLocation = $applicantRow['city1'] . ", " . $applicantRow['province1'];
		$dateRegistered = getAcctInfo($applicantUsername, "date-joined");
		$dateRegistered = date('Y-m-d', strtotime($dateRegistered));
		$content .= "<tr>";
		$content .= "<td>" . $countDisplay . "</td>";
		$content .= "<td>" . "<input type=\"checkbox\" class=\"checkBoxes\" name=\"checkBoxes\" id='" . $applicantUsername. "' val='1'></td>";
		$content .= "<td><a onclick=\"viewSummary('$applicantUsername');\" style=\"cursor:pointer;\">" . $applicantName . "</a></td>";
		$content .= "<td>" . $applicantAge . "</td>";
		$content .= "<td>" . $applicantGender . "</td>";
		$content .= "<td>" . $applicantCivilStatus . "</td>";
		$content .= "<td>" . $applicantHeight . "</td>";
		$content .= "<td>" . $applicantWeight . "</td>";
		$content .= "<td>" . $applicantEducationalAttainment . "</td>";
		$content .= "<td>" . $applicantLocation . "</td>";
		$content .= "<td>" . $dateRegistered . "</td>";
		$content .= "</tr>";
	}
	$content .= "</table>";
}
else{
	$error += 1;
	$content .= "<span class=\"alert alert-error\">ERROR: NO RESULTS FOUND!</span>";
}
$content.="</div></div>";

/**END results**/

/**START select all and total number**/
$currStartItemNum = $startPage + 1;
$totalNumberDisplay = "";
$totalNumberDisplay .= "<div class=\"row-fluid\">
							<div class=\"span2\">
								(<strong>$currStartItemNum</strong> - <strong>$countDisplay</strong> of <span style=\"color:#00c2ff; font-weight:bold;\">$totalApplicants</span>)
							</div>
							<div class=\"span2\">
								<label class=\"checkbox\"><input type=\"checkbox\" id=\"selectAll\" name=\"selectAll\" value=\"0\" onclick=\"checkAll();\">Select All</label>
							</div>
							<div class=\"span3\">
								<div class=\"btn-toolbar specialToolBar\">
									<div class=\"btn-group\">
										<span id=\"viewBtnBlock\"><a class=\"btn btn-primary btn-mini\" id=\"viewBtn\" name=\"viewBtn\" href=\"#\" onclick=\"viewApplicantResume();\"><i class=\"icon-file icon-white\"></i> View</a></span>
										<span id=\"exportBtnBlock\"><a class=\"btn btn-primary btn-mini\" id=\"exportBtn\" name=\"exportBtn\" href=\"#\" onclick=\"exportApplicantData();\"><i class=\"icon-download-alt icon-white\"></i> Export</a></span>
									</div>
								</div>
							</div>
						</div>";
/**END select all and total number**/

/*START print outputs*/
if($error===0)
	echo $totalNumberDisplay . $content . $pagination;
else
	echo $content;
/*END print outputs*/
?>