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

//GET CURRENT DATE
date_default_timezone_set('Singapore');
$currDate = date("Y-m-d");

//set Profile Box Info
$status = getAcctInfo($username, "status");
$webAdminName = getWebAdminData($username, "full-name");
$location = getWebAdminData($username, "address");
$profilePic = "./img/id.png";

//get job position requests
$getInterestRequestsQuery = "SELECT * FROM interest WHERE status='1' ";
$getInterestRequests = mysql_query($getInterestRequestsQuery) or die(mysql_error());
$interestRequestsCount = mysql_num_rows($getInterestRequests);
if($interestRequestsCount > 0){
	$interestBadge = "inline";
}
else{
	$interestBadge = "none";
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
	<!-- START NAVBAR -->
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
						<a class="brand dropdown-toggle" data-toggle="dropdown" style="padding-left:46px;">Welcome, Web Admin!</a>
						<div class="nav-collapse collapse navbar-responsive-collapse">
							<ul class="nav">
								<li>
									<a href="admin-sitemap.php"><i class="icon-home icon-white"></i></a>
								</li>
								<li>
									<a href="add-jobposition-requests.php"><i class="icon-bullhorn icon-white"></i><span class="badge badge-important" style="display:<?php echo $interestBadge; ?>;"><?php echo $interestRequestsCount; ?></span></a>
								</li>
								<li>
									<a href="https://email22.secureserver.net/webmail.php"><i class="icon-envelope icon-white"></i></a>
								</li>
							</ul>
							<form class="navbar-form pull-right" method="GET" action="search-page.php" style="margin-top:-1px;">
								<input type="text" name="query" id="query" class="appendedInputButton" placeholder="Applicants, Employers, Job Posts" style="height: 20px !important;">
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
	<!-- END NAVBAR -->
	<div class="container-fluid" style="padding-top: 10px;">
		<div class="row-fluid" id="content-apply-job">
			<div class="span7 offset1">
				<div class="row-fluid">
					<div class="span12 well">
						<h4 class="content-heading2">DASHBOARD</h4>
						<ul id="map" class="nav nav-tabs nav-stacked" align="center" style="text-align: center;">
							<li id="profile" name="profile"><a href="admin-edit-profile.php" rel="tooltip" data-placement="bottom" title="Update profile information"><i class="icon-user"></i> EDIT PROFILE</a></li>
							<li id="changepwd" name="changepwd"><a href="change-password.php" rel="tooltip" data-placement="bottom" title="Change account password"><i class="icon-lock"></i> CHANGE PASSWORD</a></li>
							<li id="reports" name="reports"><a href="admin-reports.php" rel="tooltip" data-placement="bottom" title="View registrations, job posts and job applications" data-trigger="hover"><i class="icon-book"></i> VIEW ADMIN REPORTS</a></li>
							<li id="search" name="search"><a href="admin-search-employees.php" rel="tooltip" data-placement="bottom" title="Filter employee and employers based on job positions"><i class="icon-list-alt"></i> VIEW QUALIFIED USERS</a></li>
							<li id="jobfairs" name="jobfairs"><a href="admin-jobfairs.php" rel="tooltip" data-placement="bottom" title="Add, edit, delete job fairs" data-trigger="hover"><i class="icon-globe"></i> MANAGE JOB FAIRS</a></li>
							<li id="jobpos" name="jobpos"><a href="edit-job-positions.php" rel="tooltip" data-placement="bottom" title="Manage job positions"><i class="icon-briefcase"></i> MANAGE JOB POSITIONS</a></li>
							<li id="upload" name="upload"><a href="admin-manage-data.php" rel="tooltip" data-placement="bottom" title="Export/import a .csv copy of database tables"><i class="icon-hdd"></i> MANAGE DATA</a></li>
							<li id="supportemail" name="supportemail"><a href="https://email22.secureserver.net/webmail.php" rel="tooltip" data-placement="bottom" title="Check emails in account: support@people-link.asia"><i class="icon-envelope"></i> VISIT SUPPORT EMAIL</a></li>
						</ul>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span12">
						<p><a href="download-sitemanual.php"><i class="icon-download-alt"></i> WEB SITE MANUAL</a></p>
					</div>
				</div>
			</div>
			<div class="span3">
				<!-- START PROFILE BOX -->
				<?php echo $profileBox=getEmployerProfileBox($status, $webAdminName, $location, $profilePic);?>
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