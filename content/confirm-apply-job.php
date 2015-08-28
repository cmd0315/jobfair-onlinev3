<?php
include './includes/functions.php';
include './includes/db.php';

//GET CURRENT DATE
$year = date('Y');
$currDate = date("Y-m-d");
$info = "block";
$error = "none";
$applyButtons = "block";

//GET SESSION VARIABLLES
session_start();
$username = $_SESSION['SRIUsername'];
$jobPostCode = $_SESSION['SRIJobPostCode'];

//check if employee already applied to the job
$checkApplicationHistoryQuery = "SELECT * FROM application_history WHERE employee_username='$username' AND job_code='$jobPostCode'";
$checkApplicationHistory = mysql_query($checkApplicationHistoryQuery) or die(mysql_error()); 
$applicationCount = mysql_num_rows($checkApplicationHistory);

if($applicationCount > 0){
	$info = "none";
	$error = "block";
	$applyButtons = "none";
}

//get job post info
$getJobPostQuery = "SELECT * FROM job_post WHERE code='$jobPostCode' ";
$getJobPost = mysql_query($getJobPostQuery) or die(mysql_error());
$jobPostData = mysql_fetch_assoc($getJobPost);
$location = $jobPostData['location_id'];
$location = getJobLocation($location);
$street = $jobPostData['street'];
$position = $jobPostData['job_position'];
$position = getJobPost($jobPostCode, "job-pos-name");
$numVacancies = $jobPostData['num_vacancies'];
if($numVacancies > 1){
	$slots = "slots";
}
else{
	$slots = "slot";
}
$description = $jobPostData['job_desc'];
$sex = $jobPostData['e_sex'];
if($sex == "M"){
	$sex = "Male";
}
else if($sex == "F"){
	$sex = "Female";
}
else if($sex == "NR"){
	$sex = "";
}

$civilStatus = $jobPostData['e_civil_status'];

$minAge = trim($jobPostData['e_min_age']);
if($minAge == 0){
	$minAge ="";
}
$maxAge = trim($jobPostData['e_max_age']);
if($maxAge == 0){
	$maxAge ="";
}
if($minAge !="" && $maxAge != ""){

	if($minAge == $maxAge){
		$age = $minAge;
	}
	else{
		$age = $minAge . " to " . $maxAge;	
	}
}
else if($minAge == ""){
	$age = $maxAge;
}
else if($maxAge == ""){
	$age = $minAge;
}

$weight = trim($jobPostData['e_weight']);
$educAttainment = trim($jobPostData['e_educ_attainment']);
//get job post other requirements
$getJobPostRequirementsQuery = "SELECT * FROM requirements WHERE post_code='$jobPostCode' ";
$getJobPostRequirements = mysql_query($getJobPostRequirementsQuery) or die(mysql_error());

$employer = $jobPostData['employer_username'];
$employerNameQuery = "SELECT company_name FROM employer WHERE username='$employer' ";
$getEmployerName = mysql_query($employerNameQuery) or die(mysql_error());
$employerName = mysql_result($getEmployerName, 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<!-- START META -->
<?php echo $meta=getMeta($username); ?>
<!-- END META -->
<!-- CSS scripts -->
<link rel="stylesheet" href="./css/bootstrap.css"/>
<link rel="stylesheet" href="./css/select2.css"/>
<link rel="stylesheet" href="./css/datepicker.css"/>
<!-- [if IE]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif] -->
<link rel="stylesheet" href="./css/bootstrap-responsive.css"/>
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
	<?php echo $navbar=getApplicantNavBar($username); ?>
	<!-- END SEARCH NAVBAR  -->
	<div class="container-fluid">
		<div class="row-fluid" id="content-apply-job">
			<div class="span8 offset2"> 
				<div class="row-fluid">
					<div class="span12 well">
						<h4 class="content-heading2">CONFIRM APPLICATION TO JOB POST</h4>
						<div class="span6 offset3">
							<span class="alert alert-info" style="font-weight:bold; display:<?php echo $info;?>;">Are you sure you want to apply to this job post?</span>
							<span class="alert alert-error" style="font-weight:bold; display:<?php echo $error;?>;">ERROR: You have already applied to this job post.</span>
						</div>
						<div class="row-fluid">
							<div class="span10 offset2">
								<div class="row-fluid">
									<!-- START APPLY JOB FORM -->
									<form class="form-horizontal" id="applyJobForm" name="applyJobForm" method="POST" action="process-apply-job.php">
										<p>Job Post Code: <span style="font-weight:bold; color:#089DFF;"><?php echo $jobPostCode;?></span></p>
										<p>Location: <span style="font-weight:bold; color:#089DFF;"><?php echo $street . ", " . $location; ?></span></p>
										<p>Position: <span style="font-weight:bold; color:#089DFF;"><?php echo $position; ?></span></p>
										<p>Vacancies: <span style="font-weight:bold; color:#089DFF;"><?php echo $numVacancies . " " . $slots; ?></span></p>
										<p>Work Description: <br/><em><?php echo $description;?></em></p>
										<p>JOB REQUIREMENTS:</p>
										<ul>
										<?php
											if($sex != ""){
												echo "<li>Gender: $sex</li>";
											}
											if($civilStatus != ""){
												echo "<li>Civil Status: $civilStatus</li>";
											}
											if($age != ""){
												echo "<li>Age Bracket: $age yrs. old</li>";
											}
											if($weight != ""){
												echo "<li>Weight: $weight lbs.</li>";
											}
											if($educAttainment != ""){
												echo "<li>Educ. Attainment: $educAttainment</li>";
											}
											while($jobPostRequirementData = mysql_fetch_assoc($getJobPostRequirements)){
												$jPRequirement = $jobPostRequirementData['req_name'];
												echo "<li>$jPRequirement</li>";
											}
										?>
										</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- hidden variables -->
		<input type="hidden" name="username" id="username" value="<?php echo $username;?>"/>
		<input type="hidden" name="jobPostCode" id="jobPostCode" value="<?php echo $jobPostCode;?>"/>
		<div class="row-fluid" style="display:<?php echo $applyButtons;?>;">
			<div class="span12">
				<div class="row-fluid">
					<div class="span4 offset6">
						<button type="button" class="btn span6" onclick="backDashboard();">CANCEL</button>
						<button type="submit" class="btn btn-primary job-post-add span6" id="submitApply" name="submitApply">APPLY</button>
						</form>
						<!-- END APPLY JOB Form -->
					</div>
				</div>
			</div>
		</div>
	</div></br>
	<div class="push"></div>
</div>
<!-- START MODAL-->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">
  <div class="modal-header">
	<h4 class="content-heading4" id="modal-title">Application Sent!</h4>
  </div>
  <div class="modal-body">
	<div class="row-fluid">
		<div class="span9 offset1" style="padding-left:25px;">
			  <p style="text-align:center">Thank you for applying! Employer <span class="text-info" style="font-weight:bold"><?php echo $employerName;?></span> has been notified about your application.</p>
		</div>
	</div>
  </div>
  <div class="modal-footer">
	<div class="row-fluid">
			<div class="span6 offset1" style="margin-left:80px;">
				<button class="btn btn-primary input-medium" onclick="reload();">OK</button>
			</div>
	</div>
  </div>
</div>
<!-- END MODAL -->
	<!-- START FOOTER -->
	<?php echo $footer=getFooter(); ?>
	<!-- END FOOTER -->
<!-- JS scripts -->
<script src="./js/jquery.min.js"></script>
<script src="./js/jqBootstrapValidation.js"></script>
<script src="./js/jquery.form.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script>
$(document).ready(function() { 
	$(function () { $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); } );
	
	// bind to the form's submit event 
	$('#applyJobForm').submit(function() { 
	   $(this).ajaxSubmit(); 
		$("#myModal").modal('show');
		return false; 
	});
});

function backDashboard(){
	window.location.href = "end-apply-job.php";
}

function reload(){
	window.location.href = "end-apply-job.php";
}
</script>
<!-- JS scripts -->
</body>
</html>