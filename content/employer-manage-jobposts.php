<?php
include './includes/functions.php';
include './includes/db.php';

//GET CURRENT DATE
$date = date('Y-m-d');
$year = date('Y');

/* GET SESSION DATA*/
session_start();

if(isset($_SESSION['SRIUsername']) && $_SESSION['SRIUsername'] != ""){
	$username = $_SESSION['SRIUsername'];
}
else{
	header("Location: index.php");
}

//set Profile Box Info
$status = getAcctInfo($username, "status");
$coName = getEmployerData($username, "company-name");
$location = getEmployerData($username, "address");
$profilePic = getEmployerData($username, "profile_pic");

$jobPostsQuery = "SELECT * FROM job_post WHERE employer_username='$username' AND status2='0' ORDER BY job_opendate DESC";
$getJobPosts = mysql_query($jobPostsQuery) or die(mysql_error());
$jobPostsCount = mysql_num_rows($getJobPosts);
$jobsText = "";
if($jobPostsCount > 1){
	$jobsText = "jobs";
	if($jobPostsCount > 6){
		$display = "block";
	}
	else{
		$display = "none";
	}
}
else{
	$jobsText = "job";
}

//array of job posts codes for batch export
$jobPostsArray = "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<!-- START META -->
<?php echo $meta=getMeta(); ?>
<!-- END META -->
<!-- CSS scripts -->
<link rel="stylesheet" href="./css/bootstrap.css"/>
<!-- [if IE]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif] -->
<link rel="stylesheet" href="./css/bootstrap-responsive.css"/>
<link rel="stylesheet" href="./js/datatable/css/demo_table.css">
<link rel="stylesheet" href="./js/datatable/css/datatables.responsive.css"/>
<style type="text/css">
	select[size] {
		height: auto;
		width: 100px;
	}
</style>
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
	<?php echo $navbar=getEmployerNavBar($username); ?>
	<!-- END SEARCH NAVBAR  -->
	<div class="container-fluid" style="padding-top: 10px;">
		<div class="row-fluid">
			<div class="span7 offset1">
				<div class="row-fluid">
					<div class="span12">
						<div class="row-fluid">
							<div class="span12">
								<h4 id="e-dash-tag">You posted <span id="job-post-count"><?php echo $jobPostsCount; ?></span> <?php echo $jobsText;?>.</h4>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span12 well">
								<div class="tabbable tabs-left">
									<ul class="nav nav-tabs nav-pills">
										<li><button class="btn btn-info span12 employerDashboardBtn" id="addBtn" name="addBtn" onclick="window.location.href='add-job-post.php'">ADD JOB POST</button></li>
										<li><button class="btn btn-info span12 employerDashboardBtn" id="editJPBtn" name="editJPBtn" onclick="edit();">EDIT JOB POST</button></li>
										<li><button class="btn btn-info span12 employerDashboardBtn" id="deleteBtn" name="deleteBtn" onclick="stop();">CLOSE JOB LISTING</button></li>
										<li><button class="btn btn-info span12 employerDashboardBtn" id="deleteBtn" name="deleteBtn" onclick="removePost();">REMOVE JOB POST</button></li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane active" id="lB">
											<div class="row-fluid">
												<table class="display table-striped table-hover table-condensed" width="100%"  id="record_table" style="text-align:center;">
													<thead>
														<tr>
															<th></th>
															<th>Job Position</th>
															<th>Location</th>
															<th>Date of Job Post</th>
															<th>No. of Vacancies</th>
															<th>No. of Applicants</th>
															<th>Job Expiration</th>
															<th>Search for Employees</th>
														</tr>
													</thead>
													<tbody>
													<?php
														while($jobPostsData = mysql_fetch_assoc($getJobPosts)){
															$content = "";
															$jobPostCode = $jobPostsData['code'];
															$jobPosition = getJobPost($jobPostCode, "job-pos-name");
															$jobLocation = $jobPostsData['location_id'];
															$jobLocation = getJobLocation($jobLocation);
															$numVacancies = getJobPost($jobPostCode, "num-vacancies");
															$jobOpenDate = $jobPostsData['job_opendate'];
															$jobOpenDate = date('F d Y', strtotime($jobOpenDate));
															$jobCloseDate = $jobPostsData['job_closedate'];
															$jobCloseDate = date('F d Y', strtotime($jobCloseDate));
															$locationPostsQuery = "SELECT * FROM location_posts WHERE post_code='$jobPostCode'";
															$getLocationPosts = mysql_query($locationPostsQuery) or die(mysql_error());
															$locationPostsData = mysql_fetch_assoc($getLocationPosts);
															$jobStatus = $locationPostsData['status'];
															if($jobStatus == 1){
																$jobPosition = "<span class='text-info'><strike>" . $jobPosition . "</strike></span>";
															}
															$jobPosLabel = "jobPos" . $jobPostCode;
															
															$getApplicantsQuery = "SELECT * FROM application_history WHERE job_code='$jobPostCode' ";
															$getApplicants = mysql_query($getApplicantsQuery) or die(mysql_error());
															$countApplicants = mysql_num_rows($getApplicants);
															if($countApplicants > 0){
																$applicants = "<a class=\"text-info\" href=\"view-applicants.php?code=$jobPostCode\" style=\"font-weight:bold;\">$countApplicants</a>";
															}
															else{
																$applicants = $countApplicants;
															}
															$content.="<tr>
															<td><input type=\"radio\" name=\"offeredJob\" id=\"offeredJob\" value=\"$jobPostCode\"></td>
															<td style=\"text-align:left; cursor:pointer;\" onclick=\"viewSummary('$jobPostCode');\"><span class=\"extra-label3\">$jobPosition</span></td>
															<td style=\"cursor:pointer;\" onclick=\"viewSummary('$jobPostCode');\">$jobLocation</td>
															<td style=\"cursor:pointer;\" onclick=\"viewSummary('$jobPostCode');\">$jobOpenDate</td>
															<td style=\"cursor:pointer;\" onclick=\"viewSummary('$jobPostCode');\">$numVacancies</td>
															<td>$applicants</td>
															<td style=\"cursor:pointer;\" onclick=\"viewSummary('$jobPostCode');\">$jobCloseDate</td>
															<td><button type=\"button\" class=\"btn-primary btn-mini\" id=\"addBtn\" style=\"margin-top:-5px; border-radius: 25px; width: 26px; height: 26px; box-shadow: 2px 2px 5px #888888;\" onclick=\"searchEmployees('$jobPostCode')\" ><i id=\"icon\" class=\"icon-search icon-white\" style=\"margin-left:-2px; padding-top:1px;\"></i></button></td>
															</tr>";
															$jobPostsArray .= $jobPostCode . "-";
															echo $content;
														}
													?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- hidden variable-->
							<input type="hidden" id="jobPostsArray" name="jobPostsArray" value="<?php echo $jobPostsArray;?>"/>
							<div class="row-fluid">
								<div class="span4 offset8">
									<button type="button" id="exportBtn" name="exportBtn" class="btn btn-primary span12" onclick="exportJobPostsData();"><i class="icon-download-alt icon-white"></i> Job Post Records</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="span3">
				<div class="row-fluid" style="height:30px;"></div>
				<!-- START PROFILE BOX -->
				<?php echo $profileBox=getEmployerProfileBox($status, $coName, $location, $profilePic);?>
				<!-- END PROFILE BOX -->
				<div class="row-fluid">
					<div class="span10 offset1">
						<div class="alert alert-info">
							<p>If you still haven’t found what you’re looking for, contact us at <a href="mailto: srinbs@sri.ph">srinbs@sri.ph</a> or call +63917-823-5978</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row-fluid" id="job-posts-list">
		</div>
	</div>
	<div class="push"></div>
</div>
<!-- START FOOTER -->
<?php echo $footer=getFooter(); ?>
<!-- END FOOTER -->
<!-- START MODALS -->
<?php echo $modals=getEmployerDashboardModals(); ?>
<!-- END MODALS -->
<!-- START JOB POST SUMMARY MODAL-->
<div id="summaryModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<!-- END JOB POST SUMMARY MODAL -->
<!-- JS scripts -->
<script src="./js/jquery.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script src="./js/datatable/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="./js/datatable/js/datatables.responsive.js"></script>
<script>
 $(document).ready(function(){
	$('#record_table').dataTable();
});

var jPCode = "";

function edit() {
	var radioButton = String($('input:radio[name=offeredJob]:checked').val());
	if(radioButton != "undefined"){
		window.location.href = "edit-job-post.php?code="+radioButton;
	}
	else{
		document.getElementById("modalContent").innerHTML = "Please select a job post to be edited.";
		$("#errorModal").modal('show');
	}
}

function editJP(jobPostCode){
	window.location.href = "edit-job-post.php?code="+jobPostCode;
}

function stop() {
	var radioButton = String($('input:radio[name=offeredJob]:checked').val());
	if(radioButton != "undefined"){
		stopJP(radioButton);
	}
	else{
		document.getElementById("modalContent").innerHTML = "Please select a job post to be stopped from being listed.";
		$("#errorModal").modal('show');
	}
}
	
function stopJP(jobPostCode){
	jPCode = jobPostCode;
	$("#summaryModal").modal('hide');
	var hr = new XMLHttpRequest();
	var url = "check-jobpost-status.php?code="+jPCode+"&status=stopped";
	hr.open("GET", url, true);
	hr.onreadystatechange = function() {
		if(hr.readyState == 4 && hr.status == 200) {
			var return_data = hr.responseText;	
			return_data = return_data.split(",");
			var position = return_data[0];
			var status = return_data[1];
			if(status == "1"){
				document.getElementById("errorModalTitle2").innerHTML = "Job Post Listing Already Closed!";
				document.getElementById("errorModalContent2").innerHTML = "This job post has already been closed.";
				$("#errorModal2").modal('show');
			}
			else{
				document.getElementById("stopModalContent").innerHTML = "Are you sure you want to close job post with position <span class='text-info'>" + position + "</span>? Note that interested applicants will be unable to search and view this job post.";
				$("#stopModal").modal('show');
			}
		}
	}
	hr.send(null); 
	
}

function stopJP2(){
	var hr = new XMLHttpRequest();
	var url = "stop-job-post.php?code="+jPCode;
	hr.open("GET", url, true);
	hr.onreadystatechange = function() {
		if(hr.readyState == 4 && hr.status == 200) {
			var return_data = hr.responseText;				
			$("#stopModal").modal('hide');
			$("#stoppedModal").modal('show');
		}
	}
	hr.send(null); 
}

function removePost() {
	var radioButton = String($('input:radio[name=offeredJob]:checked').val());
	if(radioButton != "undefined"){
		removeJP(radioButton);
	}
	else{
		document.getElementById("modalContent").innerHTML = "Please select a job post to be removed.";
		$("#errorModal").modal('show');
	}
}

function removeJP(jobPostCode){
	jPCode = jobPostCode;
	$("#summaryModal").modal('hide');
	var hr = new XMLHttpRequest();
	var url = "check-jobpost-status.php?code="+jPCode+"&status=removed";
	hr.open("GET", url, true);
	hr.onreadystatechange = function() {
		if(hr.readyState == 4 && hr.status == 200) {
			var return_data = hr.responseText;	
			return_data = return_data.split(",");
			var position = return_data[0];
			var status = return_data[1];
			if(status == "1"){
				document.getElementById("errorModalTitle2").innerHTML = "Job Post Listing Already Removed!";
				document.getElementById("errorModalContent2").innerHTML = "This job post has already been removed.";
				$("#errorModal2").modal('show');
			}
			else{
				document.getElementById("removeModalContent").innerHTML = "Are you sure you want to remove job post with position <span class='text-info'>" + position + "</span>?";
				$("#removeModal").modal('show');
			}
		}
	}
	hr.send(null); 
}

function removeJP2(){
	var hr = new XMLHttpRequest();
	var url = "remove-job-post.php?code="+jPCode;
	hr.open("GET", url, true);
	hr.onreadystatechange = function() {
		if(hr.readyState == 4 && hr.status == 200) {
			var return_data = hr.responseText;				
			$("#removeModal").modal('hide');
			$("#removedModal").modal('show');
		}
	}
	hr.send(null); 
}

function viewSummary(jobPostCode){
	var hr = new XMLHttpRequest();
	var url = "job-post-summary.php?jobPostCode="+jobPostCode;
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

function searchEmployees(jobPostCode){
	window.location.href="search-employees.php?code="+jobPostCode;
}

function reload(){
	window.location.reload();
}

function exportJobPostsData(){
	var jobPostsArray = document.getElementById('jobPostsArray').value;
	window.location.href = "employer-jobapplications-sheet.php?codes="+jobPostsArray;
}
</script>
<!-- JS scripts -->
</body>
</html>