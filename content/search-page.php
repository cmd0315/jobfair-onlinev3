<?php
include './includes/functions.php';
include './includes/db.php';

extract($_GET);
/* GET SESSION DATA*/
session_start();
if(isset($_SESSION['SRIUsername']) && $_SESSION['SRIUsername'] != ""){
	$username = $_SESSION['SRIUsername'];
}
else{
	header("Location: index.php");
}
//GET CURRENT DATE
date_default_timezone_set('Singapore');
$currDate = date("Y-m-d");

//set Profile Box Info
$status = getAcctInfo($username, "status");
$webAdminName = getWebAdminData($username, "full-name");
$location = getWebAdminData($username, "address");
$profilePic = "./img/id.png";

	
if($query !== ""){
	$searchApplicantNumQuery = "SELECT Count(*) FROM `employee` WHERE  Concat(first_name, space(1), middle_name, space(1), last_name) LIKE '%$query%' ORDER BY `first_name` ASC";
	$searchApplicantNum = mysql_result(mysql_query($searchApplicantNumQuery),0);
	$searchApplicantQuery = "SELECT * FROM `employee` WHERE  Concat(first_name, space(1), middle_name, space(1), last_name) LIKE '%$query%' ORDER BY `first_name` ASC";
	$searchApplicant = mysql_query($searchApplicantQuery);
	
	$searchEmployerNumQuery = "SELECT Count(*) FROM `employer` WHERE company_name LIKE '%$query%' ORDER BY `company_name` ASC";
	$searchEmployerNum = mysql_result(mysql_query($searchEmployerNumQuery),0);
	$searchEmployerQuery = "SELECT * FROM `employer` WHERE company_name LIKE '%$query%' ORDER BY `company_name` ASC";
	$searchEmployer = mysql_query($searchEmployerQuery);
	
	$searchJobPostNumQuery = "SELECT Count(*) FROM `job_post` WHERE job_position ORDER BY `job_position` ASC";
	$searchJobPostNum = mysql_result(mysql_query($searchJobPostNumQuery),0);
	$searchJobPostQuery = "SELECT * FROM `job_post` WHERE job_position ORDER BY `job_position` ASC";
	$searchJobPost = mysql_query($searchJobPostQuery);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<!-- START META -->
<?php echo $meta=getMeta(); ?>
<!-- END META -->
<!-- CSS scripts -->
<link rel="stylesheet" href="./css/bootstrap.css"/>
<link rel="stylesheet" href="./css/select2.css"/>
<!-- [if IE]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif] -->
<link rel="stylesheet" href="./css/bootstrap-responsive.css"/>
<link rel="stylesheet" href="./js/datatable/css/demo_table.css">
<link rel="stylesheet" href="./js/datatable/css/datatables.responsive.css"/>
<!-- CSS scripts -->
</head>
<body>
<!-- START GTM  -->
<?php echo $gtm=getGTM(); ?>
<!-- END GTM-->
<div class="wrap">
	<!-- START HEADER -->
	<?php echo $header = getHeader('Default'); ?>
	<!-- END HEADER -->
	<!-- START SEARCH NAVBAR  -->
	<?php echo $searchBar=getSearchBar($username); ?>
	<!-- END SEARCH NAVBAR  -->
	<div class="container-fluid" style="padding-top: 10px;">
		<div class="row-fluid" id="content-apply-job">
			<div class="span7 offset1"> 
				<h4 id="e-dash-tag" style="font-style:italic;">SEARCH RESULTS for: <span style="color:black;"><?php echo $query;?></span></h4>
				<div class="row-fluid">
					<div class="span12 well">
						<?php
							//start applicant table
							if($searchApplicantNum > 0){
								if($searchApplicantNum > 1){
									echo "<h4 style=\"color:black;\">Applicants</h4>";
								}
								else
									echo "<h4 style=\"color:black;\">Applicant</h4>";
								
								$table1 =<<<EOT
								<div class="row-fluid">
									<table class="display table-striped table-hover table-condensed record_table" width="100%"  id="record_table1" style="text-align:center;">
										<thead>
											<th>Name</th>
											<th>Username</th>
											<th>Location</th>
										</thead>
										<tbody>
EOT;
								while ($row = mysql_fetch_assoc($searchApplicant)){
									$applicantUsername = $row['username'];
									$applicantName = getEmployeeData($applicantUsername, "full-name");
									$applicantLocation = getEmployeeData($applicantUsername, "address");
									
									$table1 .=<<<EOT
										<tr>
											<td><a onclick="viewSummary('$applicantUsername');" style="cursor:pointer; font-weight:bold;">$applicantName<a></td>
											<td>$applicantUsername</td>
											<td>$applicantLocation</td>
										</tr>
EOT;
								}
								$table1 .=<<<EOT
									</tbody>
								</table>
EOT;
								echo $table1 . "</div><br/><br/>";
							}
							
							//start employer table
							if($searchEmployerNum > 0){
								if($searchEmployerNum > 1){
									echo "<h4 style=\"color:black;\">Employers</h4>";
								}
								else
									echo "<h4 style=\"color:black;\">Employer</h4>";
								
								$table2 =<<<EOT
								<div class="row-fluid">
									<table class="display table-striped table-hover table-condensed record_table" width="100%"  id="record_table2" style="text-align:center;">
										<thead>
											<th>Company Name</th>
											<th>Username</th>
											<th>Location</th>
										</thead>
										<tbody>
EOT;
								while ($row = mysql_fetch_assoc($searchEmployer)){
									$employerUsername = $row['username'];
									$employerName = getEmployerData($employerUsername, "company-name");
									$employerLocation = getEmployerData($employerUsername, "address");
											
									$table2 .=<<<EOT
										<tr>
											<td><a onclick="viewSummary2('$employerUsername');" style="cursor:pointer; font-weight:bold;">$employerName<a></td>
											<td>$employerUsername</td>
											<td>$employerLocation</td>
										</tr>
EOT;
								}
								$table2 .=<<<EOT
									</tbody>
								</table>
EOT;
								echo $table2 . "</div><br/><br/>";
							}
							
							//start job post table
							$jobPostCount = 0;
							while ($row = mysql_fetch_assoc($searchJobPost)){
								$jobPostNum = $row['code'];
								$jobPosition = getJobPost($jobPostNum, "job-pos-name");
								if(strpos($jobPosition, $query) !== FALSE || $jobPosition == $query || strcasecmp($jobPosition, $query) == 0){
									$jobPostCount += 1;
									$jobEmployerName = getJobPost($jobPostNum, "company-name");
									$jobLocation = getJobPost($jobPostNum, "location");
									$table32 .=<<<EOT
										<tr>
											<td><a onclick="viewSummary3('$jobPostNum');" style="cursor:pointer; font-weight:bold;">$jobPosition<a></td>
											<td>$jobEmployerName</td>
											<td>$jobLocation</td>
										</tr>
EOT;
								}
							}
								
							if($jobPostCount > 0){
								if($jobPostCount > 1){
									echo "<h4 style=\"color:black;\">Job Posts</h4>";
								}
								else
									echo "<h4 style=\"color:black;\">Job Post</h4>";
								
								$table31 =<<<EOT
								<div class="row-fluid">
									<table class="display table-striped table-hover table-condensed record_table" width="100%"  id="record_table3" style="text-align:center;">
										<thead>
											<th>Job Position</th>
											<th>Company Name</th>
											<th>Job Site</th>
										</thead>
										<tbody>
EOT;

								$table33 .=<<<EOT
									</tbody>
								</table>
EOT;

								$table3 = $table31 . $table32 . $table33  . "</div><br/>";
								echo $table3;
							}
							
							
							if($searchApplicantNum == 0 && $searchEmployerNum == 0 && $jobPostCount == 0){
								echo "<h4 style=\"color:black;\">No results found for query.</h4>";
							}
						?>
					</div>
				</div>
			</div>
			<div class="span3">
				<!-- START PROFILE BOX -->
				<?php echo $profileBox=getEmployerProfileBox($status, $webAdminName, $location, $profilePic);?>
				<!-- END PROFILE BOX -->
			</div>
		</div>
		<!-- hidden variables -->
		<div class="row-fluid">
			<div class="span3 offset4">
				<!--<button type="submit" class="btn btn-primary job-post-add span12" id="postJob" name="postJob">POST JOB</button>	-->			
			</div>
		</div>
	</div>
	<br/><br/><br/>
	<div class="push"></div>
</div>

<!-- START EMPLOYEE RESUME SUMMARY MODAL-->
<div id="summaryModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<!-- END EMPLOYEE RESUME SUMMARY MODAL -->
<!-- START FOOTER -->
<?php echo $footer=getFooter(); ?>
<!-- END FOOTER -->

<!-- JS scripts -->
<script src="./js/jquery.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script src="./js/select2.js"></script>
<script src="./js/datatable/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="./js/datatable/js/datatables.responsive.js"></script>
<script>
 $(document).ready(function(){
	$('#record_table1').dataTable();
	$('#record_table2').dataTable();
	$('#record_table3').dataTable();
});

function viewSummary(applicantUsername){
	var hr = new XMLHttpRequest();
	var url = "employee-resume-summary.php?username="+applicantUsername;
	hr.open("GET", url, true);
	hr.onreadystatechange = function() {
		if(hr.readyState == 4 && hr.status == 200) {
			var return_data = hr.responseText;
			document.getElementById("summaryModal").innerHTML = return_data;
		}
	}
	hr.send(null); 
	$("#summaryModal").modal('show');
}

function viewResumeEmp(username){
	window.open("view-resume.php?username="+username, '_blank');
}

function viewSummary2(username){
	var hr = new XMLHttpRequest();
	var url = "company-profile.php?username="+username;
	hr.open("GET", url, true);
	hr.onreadystatechange = function() {
		if(hr.readyState == 4 && hr.status == 200) {
			var return_data = hr.responseText;
			document.getElementById("summaryModal").innerHTML = return_data;
		}
	}
	hr.send(null); 
	$("#summaryModal").modal('show');
}


function viewSummary3(jobPostCode){
	var hr = new XMLHttpRequest();
	var url = "admin-display-jobDetails.php?jobPostCode="+jobPostCode+"&applications=yes";
	hr.open("GET", url, true);
	hr.onreadystatechange = function() {
		if(hr.readyState == 4 && hr.status == 200) {
			var return_data = hr.responseText;
			document.getElementById("summaryModal").innerHTML = return_data;
		}
	}
	hr.send(null); 
	$("#summaryModal").modal('show');
}

</script>
<!-- JS scripts -->
</body>
</html>