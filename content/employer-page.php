<?php
include './includes/functions.php';
include './includes/db.php';
require './includes/fb/facebook.php';
include './includes/fb/fb-setup.php';

//GET ALL JOB POSITIONS LISTED
$jobPositions = getJobPositions();
//GET ALL AVAILABLE JOB LOCATIONS
$jobLocations = getJobLocations();

/* GET SESSION DATA*/
session_start();
if(isset($_SESSION['SRIUsername'])){
	$username = $_SESSION['SRIUsername'];
}
else{
	$username = "none";
}
?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<link rel="image_src" href="http://people-link.asia/content/img/pic.gif" />
<meta property="og:image" content="http://people-link.asia/content/img/pic.gif" /> 
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
	<!-- START HEADER  -->
	<?php echo $header = getHeader('Employer'); ?>
	<!-- END HEADER  -->
	<!-- START NAVBAR  -->
	<?php echo $navbar=getEmployerStartNavbar(); ?>
	<!-- END NAVBAR  -->
	<!-- START SLIDER  -->
	<?php echo $navbar=getEmployerTestimonialSlider(); ?>
	<!-- END SLIDER  -->
	<div class="row-fluid">
		<div class="row-fluid" id="content">
			<div class="span7 offset1"> 
				<div class="row-fluid" style="padding-top:40px;">
					<div class="span12 well">
						<h4 class="content-heading5">WELCOME EMPLOYER</h4>
						<div class="row-fluid">
							<div class="span10">
								<p id="employer_first_job">Place your First Job Ad</p>
								<p>Welcome to JobFair-Online.Net! You’re ready to post your first job listing. Just fill out the required fields below and click “Post Job” to add your first listing. </p>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span12">
								<!-- START FIRST ADD JOB POST FORM -->
								<form method="POST" name="jobPostForm" id="jobPostForm" action="process-first-jobpost.php">
									<div class="row-fluid">
										<div class="span7">
											<div class="control-group">
												<label class="control-label" for="inputMobile" >JOB POSITION</label>
												<div class="controls">
													<select class="span12" id="position" name="position"  data-placeholder="Choose a job position" required>
														<option></option>
														<?php
															foreach($jobPositions as $jP) {
																echo "<option value=\"$jP\">$jP</option>";
															}
														?>
													</select>
													<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
												</div>
											</div>
										</div>
										<div class="span5">
											 <div class="control-group" >
												<label class="control-label">NO. OF VACANCIES</label>
												<div class="controls" style="padding-top:8px;">
													<input class="span12" type="number" min="1" max="100" id="numVacancies" name="numVacancies" placeholder="1" required />
												  <p class="help-block"></p>
												</div>
											</div>
										</div>
									</div>
									<div class="row-fluid">
										<div class="span6">
											 <div class="control-group" style="margin-top:-5px;">
												<label class="control-label">JOB SITE</label>
												<div class="controls" style="margin-top:-5px;">
													<select class="span12" id="location" name="location" data-placeholder="Choose a job location" required>
														<option></option>
														<?php
															foreach($jobLocations as $jL) {
																echo "<option value=\"$jL\">$jL</option>";
															}
														?>
													</select>
												  <p class="help-block"></p>
												</div>
											</div>
										</div>
										<div class="span6">
											<div class="control-group">
												<label class="control-label" style="margin-top:-5px;">STREET ADDRESS</label>
												<div class="controls" style="margin-top:3px;">
													<input class="span12" type="text" id="street" name="street" required />
												    <p class="help-block"></p>
												</div>
											</div>
										</div>
									</div>
									<div class="row-fluid">
										<div class="span12">
											<div class="control-group">
												<label class="control-label" style="margin-top:-4px;">JOB DESCRIPTION</label>
												<div class="controls">
													<textarea class="input-block-level" id="jobDesc" name="jobDesc" placeholder="Give a brief description of your company operations" required ></textarea>
												  <p class="help-block"></p>
												</div>
											</div>
										</div>
									</div><br>
									<div class="row-fluid">
										<div class="span4 offset8">
											<button type="submit" class="btn btn-primary job-post-add span12" id="postJob" name="postJob">POST JOB</button>		
										</div>
									</div>
								</form>
								<!-- END FIRST ADD JOB POST FORM -->
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="span3">
				<!-- START EMPLOYER SIGN IN BOX-->
				<div class="row-fluid" style="padding-top:40px;">
					<div class="span12 well">
						<h4 class="content-heading">EMPLOYER SIGN IN</h4>
						<div class="row-fluid">
							<div class="span12">
								<!-- START LOGIN FORM-->
								<form name="login-form" id="login-form" method="POST" action="./login.php">
									<div class="control-group">
										<label class="control-label" for="mobile"></label>
										<div class="controls">
											<input type="text"  class="span12" id="username" name="username" onfocus="removeMsg();" placeholder="Username" required />
											<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="password"></label>
										<div class="controls">
											<input type="password"  class="span12" id="password" name="password" onfocus="removeMsg();" minlength="8" placeholder="Password" required />
											<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
										</div>
									</div>
								</div>
							</div>
							<div class="row-fluid">
								<div class="span12" >
									<div class="row-fluid">
										<div class="span12">
											<div class="row-fluid">
												<div class="span8 offset2">
													<button type="submit" class="btn btn-primary span12" id="go-btn"name="go-btn">SIGN IN</button> 
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							</form>
							<!-- END LOGIN FORM-->
							</div>
						</div>
					</div>
				</div>
				<!-- -END EMPLOYER SIGN IN BOX -->
			</div>
		</div>
	</div>
	<div class="push"></div>
</div>
<!-- START FOOTER -->
<?php echo $footer=getFooter(); ?>
<!-- END FOOTER -->
<!-- START RESET PASSWORD MODAL-->
<?php echo $resetPasswordModal=getResetPasswordModal(); ?>
<!-- END RESET PASSWORD MODAL-->
<!-- START INVALID ACCT TYPE MODAL-->
<?php echo $invalidAcctTypeModal=getInvalidAcctTypeModal(); ?>
<!-- END INVALID ACCT TYPE MODAL -->
<!-- JS scripts -->
<script src="./js/jquery.min.js"></script>
<script src="./js/select2.js"></script>
<!-- Validate plugin -->
<script src="./js/jqBootstrapValidation.js"></script>
<!-- AddThis Smart Layers BEGIN -->
<!-- Go to http://www.addthis.com/get/smart-layers to customize -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-51e2c4c131144687"></script>
<script type="text/javascript">
  addthis.layers({
    'theme' : 'transparent',
    'share' : {
      'position' : 'left',
      'numPreferredServices' : 5
    },  
	'linkFilter' : function(link, layer) {
		return false;
	},
    'whatsnext' : {},  
    'recommended' : {}
  });
</script>
<!-- AddThis Smart Layers END -->
<!-- START FB Script -->
<script>
	window.fbAsyncInit = function() {
		FB.init({
		  appId: '<?php echo $facebook->getAppID() ?>',
		  status: true,
		  cookie: true,
		  xfbml: true,
		  oauth: true
		});
		FB.Event.subscribe('auth.login', function(response) {
			$('#signUpModal').modal('hide');
			window.location.href="sign-up.php?acctType=1";
		});
		FB.Event.subscribe('auth.logout', function(response) {
		  window.location.href="index.php";
		});
	};
	(function() {
	var e = document.createElement('script'); e.async = true;
	e.src = document.location.protocol +
	  '//connect.facebook.net/en_US/all.js';
	document.getElementById('fb-root').appendChild(e);
	}());
</script>
<!-- END FB Script -->
<script>
$(document).ready(function() { 
	$('.carousel').carousel({
		interval: 5000
	});
	
	$("#location").select2(); 
	$("#position").select2(); 
	$('#resetPasswordLink').click(function () {
		$('#resetPasswordModal').modal('show');
	});
	$('#resendVCodeLink').click(function () {
		$('#signUpModal').modal('hide');
		$('#resetPasswordModal').modal('show');
	});
	$(function () { $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); } );
});
</script>
<script>
	//go to post job page, if no session exists, force to login
	function postJob() {
		window.location.href = "./add-job-post.php?browsing=true";
	}
	
	//search account if password is forgotten or verification code is not received
	function findAccount(){
		var hr = new XMLHttpRequest();
		var accountInfo = document.getElementById("searchAcct").value;
		var url = "check-account-exists.php?accountInfo="+accountInfo;
		hr.open("GET", url, true);
		hr.onreadystatechange = function() {
			if(hr.readyState == 4 && hr.status == 200) {
				var return_data = hr.responseText;
				if(return_data == "No" || accountInfo == ""){
					document.getElementById("noAccountResult").style.display="block";
				}
				else if(return_data == "Not Verified"){
					var url2 = "resend-verification-code.php"; 
					window.location.href= url2;
				}
				else {
					var url2 = "search-account.php"; 
					window.location.href= url2;
				}
			}
		}
		hr.send(null); 
	}
	function createEmployeeAccount(){
		window.location.href = "sign-up.php?applyingjob=true";
	}
</script>
<script src="./js/bootstrap.min.js"></script>
<!-- JS scripts -->
</body>
</html>