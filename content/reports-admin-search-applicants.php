<?php
include './includes/functions.php';
include './includes/db.php';
include './includes/paginate.php';

$getFilteredApplicantsQuery = "SELECT * FROM interest";
$getFilteredApplicantsQuery2 = "SELECT application_history.* FROM application_history INNER JOIN job_post ON application_history.job_code=job_post.code";
$getFilteredApplicantsQuery3 = "SELECT * FROM work_history";
$getTotalApplicantsQuery = "SELECT * FROM employee INNER JOIN account ON employee.username = account.username";
$linkVariables = array();
$linkVariables2 = array();
$linkVariables3 = array();
$getFinalApplicantsQuery = "";
$page = (int) (!isset($_GET['pageNum']) ? 1 :$_GET['pageNum']);
$page = ($page == 0 ? 1 : $page);
$startPage = ($page-1) * $resultpPageMax;
$jobPositionsBool = 1; //$_GET job positions is not set
$error = 0;

/**START get applicant list based on interest**/
if(isset($_GET['jobPositions'])){
	$jobPositions = $_GET['jobPositions'];
	$jPositions = json_decode(stripslashes($jobPositions), true);
	$jCount = 0;
	$jPositionsSize = sizeof($jPositions);
	while($jCount<$jPositionsSize){ //filter by interests
		$jP = $jPositions[$jCount];
		$jPName = getJobPositionName($jP);
		$jCount +=1;
		$linkString = "position_id='". $jP . "'";
		$linkString2 = "job_post.job_position='". $jP . "'";
		$linkString3 = "position LIKE '%". $jPName . "%'";
		array_push($linkVariables, $linkString);
		array_push($linkVariables2, $linkString2);
		array_push($linkVariables3, $linkString3);
	}

	//complete '$getFilteredApplicantsQuerys' query
	$formedQuery = formQuery($linkVariables, $getFilteredApplicantsQuery, 1);
	$formedQuery2 = formQuery($linkVariables2, $getFilteredApplicantsQuery2, 1);
	$formedQuery3 = formQuery($linkVariables3, $getFilteredApplicantsQuery3, 1);

	$getFilteredApplicants = mysql_query($formedQuery) or die(mysql_error());
	$filteredApplicantsNumRow = mysql_num_rows($getFilteredApplicants);

	$getFilteredApplicants2 = mysql_query($formedQuery2) or die(mysql_error());
	$filteredApplicantsNumRow2 = mysql_num_rows($getFilteredApplicants2);

	$getFilteredApplicants3 = mysql_query($formedQuery3) or die(mysql_error());
	$filteredApplicantsNumRow3 = mysql_num_rows($getFilteredApplicants3);

	$filteredApplicants = array();
	$linkVariables = array();

	//get filtered applicants
	while($filteredApplicantsRow = mysql_fetch_assoc($getFilteredApplicants)){
		$qAUsername = $filteredApplicantsRow['employee_username'];
		array_push($filteredApplicants, $qAUsername);
	}

	while($filteredApplicantsRow = mysql_fetch_assoc($getFilteredApplicants2)){
		$qAUsername = $filteredApplicantsRow['employee_username'];
		array_push($filteredApplicants, $qAUsername);
	}

	while($filteredApplicantsRow = mysql_fetch_assoc($getFilteredApplicants3)){
		$qAUsername = $filteredApplicantsRow['employee_username'];
		array_push($filteredApplicants, $qAUsername);
	}

	$newFilteredApplicants = array_unique($filteredApplicants); //remove duplicate entries based on different table searches
	$newFilteredApplicants = array_count_values($filteredApplicants);
	$nFACount = 0;
	foreach($newFilteredApplicants as $nFAKey => $nFA){
		if($jPositionsSize <= 1 || $nFA >= 2){
			$linkString = "employee.username='". $nFAKey . "'";
			array_push($linkVariables, $linkString);
			$nFACount += 1;
		}
	}

	if($nFACount < 1){
		$error += 1;
		$content .= "<span class=\"alert alert-error\">ERROR: NO RESULTS FOUND!</span>";
		printContent($error, '', $content, '');
	}
	$getFinalApplicantsQuery = formQuery($linkVariables, $getTotalApplicantsQuery, 1);
	$jobPositionsBool = 0;
}
else{
	$getFinalApplicantsQuery = $getTotalApplicantsQuery;
	$jobPositionsBool = 1;

}
/**END get applicant list based on interest**/

/*END other filters*/
$linkVariables = array();
if(isset($_GET['minAge'])){
	$minAge = $_GET['minAge'];
	$minYrVar = '-' . $minAge . " years";
	$minBDate = date('Y-m-d', strtotime($minYrVar));
	$linkString = "employee.birth_date<='". $minBDate . "'";
	array_push($linkVariables, $linkString);
}
if(isset($_GET['maxAge'])){
	$maxAge = $_GET['maxAge'];
	$minYrVar = '-' . $maxAge . " years";
	$minBDate = date('Y-m-d', strtotime($minYrVar));
	$linkString = "employee.birth_date>='". $minBDate . "'";
	array_push($linkVariables, $linkString);
}
if(isset($_GET['gender'])){
	$gender = $_GET['gender'];
	$linkString = "employee.sex='". $gender . "'";
	array_push($linkVariables, $linkString);
}
if(isset($_GET['height'])){
	$height = $_GET['height'];
	$height = explode("'", $height);
	$heightFt = $height[0];
	$heightIn = $height[1];
	if($heightIn != ""){
		$linkString = "employee.height REGEXP '^". $heightFt . "' AND employee.height REGEXP '" . $heightIn . "$'";
	}
	else{
		$linkString = "employee.height REGEXP '^". $heightFt . "'";
	}
	
	array_push($linkVariables, $linkString);
}
if(isset($_GET['weight'])){
	$weight = $_GET['weight'];
	$linkString = "employee.weight='". $weight . "'";
	array_push($linkVariables, $linkString);
}
if(isset($_GET['civilStatus'])){
	$civilStatus = $_GET['civilStatus'];
	$linkString = "employee.civil_status='". $civilStatus . "'";
	array_push($linkVariables, $linkString);
}
if(isset($_GET['location'])){
	$location = $_GET['location'];
	$location = explode(",", $location);
	$city = trim($location[0]);
	$province = trim($location[1]);
	$linkString = "employee.city1='". $city . "' AND employee.province1='" . $province . "'";
	array_push($linkVariables, $linkString);
}
if(isset($_GET['educationalAttainment'])){
	$educationalAttainment = $_GET['educationalAttainment'];
	$undergraduate = "Undergraduate";
	$graduate = "Graduate";
	$graduateWithHonors = "Graduate with Honors";
	switch($educationalAttainment){
		case "High School Undergraduate":
			$linkString = "employee.hs_educ='" . $undergraduate . "'";
			break;
		case "High School Graduate":
			$linkString = "employee.hs_educ='" . $graduate . "'";
			break;
		case "High School Graduate with Honors":
			$linkString = "employee.hs_educ='" . $graduateWithHonors . "'";
			break;
		case "College Level":
			$linkString = "employee.college_educ='" . $undergraduate . "'";
			break;
		case "College Graduate":
			$linkString = "employee.college_educ='" . $graduate . "'";
			break;
		case "College Graduate with Honors":
			$linkString = "employee.college_educ='" . $graduateWithHonors . "'";
			break;
		case "Vocational School Undergraduate":
			$linkString = "employee.voc_educ='" . $undergraduate . "'";
			break;
		case "Vocational School Graduate":
			$linkString = "employee.voc_educ='" . $graduate . "'";
			break;
		case "Vocational School Graduate with Honors":
			$linkString = "employee.voc_educ='" . $graduateWithHonors . "'";
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

//complete query to add other filter values
if($jobPositionsBool === 0){
	$getFinalApplicantsQuery = formQuery($linkVariables, $getFinalApplicantsQuery, 2);
}
/*END other filters*/

if($getFinalApplicantsQuery !=""){
	$getFinalApplicants = mysql_query($getFinalApplicantsQuery) or die(mysql_error());
	$totalApplicants = mysql_num_rows($getFinalApplicants);
}


/**START pagination**/
$page = (int) (!isset($_GET['pageNum']) ? 1 :$_GET['pageNum']);
$page = ($page == 0 ? 1 : $page);
$resultpPageMax = 30; //set number of applicant rows to be displayed
$startPage = ($page-1) * $resultpPageMax;
$adjacents = "8"; //determines how many pages for navigation will be displayed
$pagination = paginateResults($page, $resultpPageMax, $adjacents, $totalApplicants);
/**END pagination**/


/*START prepare HTML to print*/
$getFinalApplicantsQuery .= " ORDER BY account.date_joined DESC LIMIT " . $startPage . " ," . $resultpPageMax;
$getFinalApplicants = mysql_query($getFinalApplicantsQuery) or die(mysql_error());

$content = "";
$content .= "<div class=\"row-fluid resultsDataTableDiv\">
				<div class=\"span12\">";
if($totalApplicants > 0){
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
	while($applicantRow = mysql_fetch_assoc($getFinalApplicants)){
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

//html for total count of results
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
										<span id=\"deleteBtnBlock\"><a class=\"btn btn-primary btn-mini\" id=\"deleteBtn\" name=\"deleteBtn\" href=\"#\" onclick=\"deleteApplicantQuery();\"><i class=\"icon-trash icon-white\"></i> Delete</a></span>
										<span id=\"exportBtnBlock\"><a class=\"btn btn-primary btn-mini\" id=\"exportBtn\" name=\"exportBtn\" href=\"#\" onclick=\"exportApplicantData();\"><i class=\"icon-download-alt icon-white\"></i> Export</a></span>
									</div>
								</div>
							</div>
						</div>";
printContent($error, $totalNumberDisplay, $content, $pagination);
/*END prepare HTML to print*/

/*START print outputs*/
function printContent($errorNum, $tND, $c, $p){
	if($errorNum===0)
		echo $tND . $p . $c . $p;
	else
		echo $c . "</br>";
	exit;
}
/*END print outputs*/


/*formQuery function*/
function formQuery($filteredArray, $query, $typeNum){
	$fACount = 0;
	$conditionalLink = "";
	if($typeNum === 1){
		foreach($filteredArray as $fA){
			if($fACount == 0){
				$query .= " WHERE (" . $fA;
			}
			else{
				$query .= " OR " . $fA;
			}
			$fACount += 1;
		}
		$query .= ")";
	}
	else if($typeNum === 2){
		foreach($filteredArray as $fA){
			$query .= " AND " . $fA;
			$fACount += 1;
		}
	}
	return $query;
}
?>