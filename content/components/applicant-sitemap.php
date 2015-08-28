<?php
include './includes/functions.php';
include './includes/db.php';
/* GET SESSION DATA*/
session_start();
if(isset($_SESSION['SRIUsername']) && $_SESSION['SRIUsername'] != ""){
	$username = $_SESSION['SRIUsername'];
}
else{
	header("Location: index.php");
}


$applyingJob = $_SESSION['SRIEmployeeApplying'];
if(isset($_SESSION['SRIJobPostCode']) && $applyingJob=="true"){
	header("Location:confirm-apply-job.php");
}
//set Profile Box Info
$status = getAcctInfo($username, "status");
$fullName = getEmployeeData($username, "full-name");
$age = getEmployeeData($username, "age");
$address = getEmployeeData($username, "address");
$profilePic = getEmployeeData($username, "profile_pic");

//GET ALL JOB POSITIONS LISTED
$jobPositions = getJobPositions();

$jobLocationId = $_GET['jobLocationId'];
$jobLocationId = explode("%", $jobLocationId);
$jobLocationId = preg_replace("/[^0-9,.]/", "",$jobLocationId[0]);
$display = "none";
if($jobLocationId != ""){
	$display = "block";
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
<link rel="stylesheet" href="./css/bootstrap-responsive.css"/>
<!-- [if IE]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif] -->
<!-- CSS scripts -->
</head>
<body>
<!-- START GTM  -->
<?php echo $gtm=getGTM(); ?>
<!-- END GTM-->
<div class="wrap">
	<!-- START HEADER -->
	<?php echo $header=getHeader(); ?>
	<!-- END HEADER -->
	<!-- START SEARCH NAVBAR  -->
	<div class="row-fluid">
		<div class="span10 offset1">
			<div class="navbar">
				<div class="navbar-inner" id="home-sign-buttons">
					<div class="container">
						<a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-responsive-collapse">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</a>
						<a class="brand dropdown-toggle" data-toggle="dropdown" style="padding-left:46px;">Welcome, <?php echo $fullName;?>!</a>
						<div class="nav-collapse collapse navbar-responsive-collapse">
							<ul class="nav">
								<li><a href="applicant-sitemap.php"><i class="icon-home icon-white"></i></a></li>
								<li><a href="#"><i class="icon-bullhorn icon-white"></i></a></li>
							</ul>
							<form class="navbar-form pull-right" method="GET" action="applicant-search-page.php" style="margin-top:-1px;">
								<input type="text" name="query" id="query" class="appendedInputButton" placeholder="Job Posts/ Job Location" style="height: 20px !important;">
								<button type="submit" class="btn btn-info btn-small"><i class="icon-search icon-white"></i></button>
							</form>
							<ul class="nav pull-right">
								<li><a href="sign-out.php"><i class="icon-off icon-white"></i> SIGN OUT</a></li>
							</ul>
						</div><!-- /.nav-collapse -->
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- END SEARCH NAVBAR  -->
	<div class="container-fluid" style="padding-top: 10px;">
		<div class="row-fluid" id="content-apply-job">
			<div class="span7 offset1">
				<div class="row-fluid">
					<div class="span12 well">
						<h4 class="content-heading2">DASHBOARD</h4>
						<ul id="map" class="nav nav-tabs nav-stacked" align="center">
							<li id="profile" name="profile"><a href="employee-edit-profile.php" rel="tooltip" data-placement="bottom" title="Update profile information"><i class="icon-user"></i> EDIT PROFILE</a></li>
							<li id="changepwd" name="changepwd"><a href="change-password.php" rel="tooltip" data-placement="bottom" title="Change account password"><i class="icon-lock"></i> CHANGE PASSWORD</a></li>
							<li id="jobpos" name="jobpos"><a href="browse-jobs.php" rel="tooltip" data-placement="bottom" title="View Available Job Posts"><i class="icon-briefcase"></i> APPLY TO JOBS</a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="span3">
				<!-- START PROFILE BOX -->
				<?php echo $profileBox=getProfileBox($status, $fullName, $age, $address, $profilePic);?>
				<!-- END PROFILE BOX -->
			</div>
		</div>
	</div>
	<br/><br/><br/>
	<div class="push"></div>
</div>
<!-- START FOOTER -->
<?php echo $footer=getFooter(); ?>
<!-- END FOOTER -->
</div>
<!-- JS scripts -->
<script src="./js/jquery.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
	$("[rel=tooltip]").tooltip();
});
</script>
<!-- JS scripts -->
</body>
</html>