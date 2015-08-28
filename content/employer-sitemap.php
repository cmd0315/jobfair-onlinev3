<?php
include './includes/functions.php';
include './includes/db.php';
//GET CURRENT DATE
$date = date('Y-m-d');
$year = date('Y');
$day = date('d');
$isBrowsing = $_GET['browsing'];
if($isBrowsing == "true") {
	header("Location: login.php?session=false");
}

/* GET SESSION DATA*/
session_start();
$username = $_SESSION['SRIUsername'];

//set Profile Box Info (if session variables not available)
$status = getAcctInfo($username, "status");
$coName = getEmployerData($username, "company-name");
$location = getEmployerData($username, "address");
$profilePic = getEmployerData($username, "profile_pic");
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
<!-- [if lte IE 8]>
	<link rel="stylesheet" href="./leaflet/dist/leaflet.ie.css"/>
<![endif] -->
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
						<a class="brand dropdown-toggle" data-toggle="dropdown" style="padding-left:46px;">Welcome, <?php echo $coName;?>!</a>
						<div class="nav-collapse collapse navbar-responsive-collapse">
							<ul class="nav">
								<li>
									<a href="employer-sitemap.php"><i class="icon-home icon-white"></i></a>
								</li>
								<li><a href="#"><i class="icon-bullhorn icon-white"></i></a></li>
							</ul>
							<form class="navbar-form pull-right" method="GET" action="employer-search-page.php" style="margin-top:-1px;">
								<input type="text" name="query" id="query" class="appendedInputButton" placeholder="Applicant Name/ Applicant Location" style="height: 20px !important;">
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
						<ul id="map" class="nav nav-tabs nav-stacked" align="center" style="text-align: center;">
							<li id="profile" name="profile"><a href="employer-edit-profile.php" rel="tooltip" data-placement="bottom" title="Update profile information"><i class="icon-user"></i> EDIT PROFILE</a></li>
							<li id="changepwd" name="changepwd"><a href="change-password.php" rel="tooltip" data-placement="bottom" title="Change account password"><i class="icon-lock"></i> CHANGE PASSWORD</a></li>
							<li id="jobpos" name="jobpos"><a href="employer-manage-jobposts.php" rel="tooltip" data-placement="bottom" title="View and Manage Job Posts"><i class="icon-briefcase"></i> VIEW JOB POSTS</a></li>
							<li id="addjobpos" name="addjobpos"><a href="add-job-post.php" rel="tooltip" data-placement="bottom" title="Add a new job post listing"><i class="icon-plus"></i> ADD JOB POST</a></li>
							<?php 
								if($status === "SRI Branch Manager"){
									echo "<li id=\"uploaddata\" name=\"uploaddata\"><a href=\"upload-csv.php\" rel=\"tooltip\" data-placement=\"bottom\" title=\"Upload .csv File to Website Database\"><i class=\"icon-hdd\"></i> IMPORT DATA</a></li>";
								}
							?>
						</ul>
					</div>
				</div>
			</div>
			<div class="span3">
				<!-- START PROFILE BOX -->
				<?php echo $profileBox=getEmployerProfileBox($status, $coName, $location, $profilePic);?>
				<!-- END PROFILE BOX -->
				<!--<div class="row-fluid">
					<div class="span10 offset1">
						<div class="alert alert-info">
							<p>If you still haven't found what you're looking for, please contact SRI.</p>
						</div>
					</div>
				</div>-->
			</div>
		</div>
	</div>
	<br/><br/><br/>
	<div class="push"></div>
</div>
<!-- START FOOTER -->
<?php echo $footer=getFooter(); ?>
<!-- END FOOTER -->
<!-- START ERROR MODAL-->
<?php echo $formErrorModal=getFormsErrorModal(); ?>
<!-- END ERROR MODAL-->
<!-- JS scripts -->
<script src="./js/jquery.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script src="./js/select2.js"></script>
<script>
$(document).ready(function(){
	$("[rel=tooltip]").tooltip();
});
</script>
<!-- JS scripts -->
</body>
</html>