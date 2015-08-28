<?php
require './includes/functions.php';
require './includes/db.php';
require './includes/translate.php';
require './includes/fb/facebook.php';
include './includes/fb/fb-setup.php';

/*get filter values*/
$jobPositions = getJobPositionsWithID();//job positions
$availableLocations= getJobLocationsWithID();//job locations

$jobLocationId = $_GET['jobLocationId'];
$display = "none";
$jobLocationId = explode("%", $jobLocationId);
$jobLocationId = preg_replace("/[^0-9,.]/", "",$jobLocationId[0]);
if($jobLocationId != ""){
	$display = "block";
}

/*get session data*/
if(isset($_SESSION['SRIUsername'])){
	$username = $_SESSION['SRIUsername'];
}
else{
	$username = "none";
}

if(isset($_SESSION['SRISearchUser'])){
	unset($_SESSION['SRISearchUser']);
}

$checkedJob = "";
if(isset($_GET['checkJob'])){
	$checkedJob = $_GET['checkJob'];
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
	<?php echo $header = getHeader('Applicant'); ?>
	<!-- END HEADER  -->
	<!-- START NAVBAR  -->
	<?php echo $navbar=getApplicantStartNavbar(); ?>
	<!-- END NAVBAR  -->
	<!-- START SLIDER  -->
	<?php echo $banner=getJobFairBanner(); ?>
	<!-- END SLIDER -->
	<div class="row-fluid homePageContent" id="content">
		<div class="span10 offset1"> 
			<div class="row-fluid">
				<div class="span12 well">
					<h4 class="content-heading5">Get a Job Now</h4>
					<div class="row-fluid">
						<div class="span10 offset1">
							<div class="row-fluid getJobNowFilter">
								<div class="span6">
									<div class="row-fluid">
										<div class="span2">
											<h4>Location:</h4>
										</div>
										<div class="span10" style="padding-top:6px;">
											<select class="span12" id="jobLocation" name="jobLocation"  data-placeholder="Choose a job location" onchange="hideJobPostsDiv();">
												<option></option>
												<?php
													foreach($availableLocations as $aLKey => $aL) {
														echo "<option value=\"$aLKey\">$aL</option>";
													}
												?>
											</select>
										</div>
									</div>
								</div>
								<div class="span4">
									<div class="row-fluid">
										<div class="span3">
											<h4>Position:</h4>
										</div>
										<div class="span9" style="padding-top:6px;">
											<select class="span12" id="jobTitle" name="jobTitle" data-placeholder="Choose a job title" onchange="hideJobPostsDiv();">
												<option></option>
												<?php
													foreach($jobPositions as $jPKey => $jP) {
														echo "<option value=\"$jPKey\">$jP</option>";
													}
												?>
											</select>	
										</div>
									</div>
								</div>
								<div class="span2">
									<button class="btn btn-medium btn-primary span12" id="searchJob" name="searchJob" type="button" onclick="displayJobs(0);">Search</button>
								</div>
							</div>
						</div>
					</div>        
				</div>
			</div>
			<!-- START JOB POSTS DIV-->
			<div class="row-fluid" id="jobPostsDiv" name="jobPostsDiv" style="display:<?php echo $display;?>"></div>
			<!-- END JOB POSTS DIV-->
			<div class="row-fluid" style="padding-top: 40px;">
				<div class="span9 well" id="newJPDiv">
					<h4 class="content-heading5">Jobs Available</h4>
					<span class="flash"></span>
					<div class="row-fluid" id="newJobPostsDiv"><!-- newJobPostItems --></div>
					<span class="flash2"></span>
				</div>
				<div class="span3">
					<div class="row-fluid">
						<h4 class="content-heading6">Are You Looking for Applicants?</h4>
						<div class="span12 well" style="margin-left:0px">
							<button type="submit" class="btn btn-primary span12" id="post-job-btn" onclick="postJob();">POST A JOB</button>
						</div>
					</div>
					<?php echo $sriads=getSriAds(); ?>
				</div>
			</div>
		</div>
	</div>
	<!-- hidden variables -->
	<input type="hidden" name="jCode" id="jCode" value="<?php echo $checkedJob;?>">
	<input type="hidden" name="user" id="user" value="<?php echo $username;?>">
	</div>
	<br><br><br>
	<div class="push"></div>
</div>
<!-- START FOOTER -->
<?php echo $footer=getFooter(); ?>
<!-- END FOOTER -->
<!-- START RESET PASSWORD MODAL-->
<?php echo $resetPasswordModal=getResetPasswordModal(); ?>
<!-- END RESET PASSWORD MODAL-->
<!-- START JOB POST MODAL-->
<div id="jobPostModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<!-- END JOB POST MODAL -->
<!-- START INVALID ACCT TYPE MODAL-->
<?php echo $invalidAcctTypeModal=getInvalidAcctTypeModal(); ?>
<!-- END INVALID ACCT TYPE MODAL -->
<!-- START TERMS MODAL-->
<?php echo $termsModal=getTermsModal(); ?>
<!-- END TERMS MODAL -->
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
			window.location.href="sign-up.php?acctType=2";
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
	//$("#termsModal").modal('show'); //display disclaimer
	changePagination(0); //display new jobs available
	$('.carousel').carousel({
		interval: 5000
	});
	$("#jobLocation").select2(); 
	$("#jobTitle").select2(); 
	$('#resetPasswordLink').click(function () {
		$('#resetPasswordModal').modal('show');
	});
	$('#resendVCodeLink').click(function () {
		$('#signUpModal').modal('hide');
		$('#resetPasswordModal').modal('show');
	});
	$(function () { $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); } );
	displayCheckedJob();
	
});
</script>
<script>
	//go to post job page, if no session exists, force to login
	function postJob() {
		window.location.href = "./employer-page.php";
	}

	function displayCheckedJob(){
		var jobCode = document.getElementById('jCode').value;
		if(jobCode != ""){
			checkJob(jobCode);
		}
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
</script>
<script>
function displayJobs(pN){
	var hr = new XMLHttpRequest();
	var locationId = document.getElementById("jobLocation").value;
	var jobPositionId = document.getElementById("jobTitle").value;
	url = "display-jobs.php?locationId="+locationId+"&jobPositionId="+jobPositionId+"&jobPageNum="+pN;
    hr.open("GET", url, true);
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
	    	//scroll down
	    	if(pN == 0){
		    	var $elem = $('#content');
				$('html, body').animate({scrollTop: $elem.height()/6}, 1000);
			}
			//get results
		    var return_data = hr.responseText;
		   	document.getElementById("jobPostsDiv").style.display = "block";
			document.getElementById("jobPostsDiv").innerHTML = return_data;
			$("[rel=popover]").popover('hide')
	    }
    }
    hr.send(null);
    document.getElementById("lA").innerHTML = "<div class='span8 offset2'><center>Please wait while results are being processed...<br/><br/><img src='./img/ajax-loader.gif' id='loader' name='loader'/></center></div>";
}

function hideJobPostsDiv(){
	document.getElementById("jobPostsDiv").style.display = "none";
}

function selectJobPostDiv(jobPostDivId){
	document.getElementById(jobPostDivId).style.backgroundColor="#D9E1E7";
}

function deselectJobPostDiv(jobPostDivId){
	document.getElementById(jobPostDivId).style.backgroundColor="rgb(240, 237, 237)";
}

function checkJob(jobPostCode){
	var hr = new XMLHttpRequest();
   	var shareLink = "index.php?checkJob="+jobPostCode;
    var url = "display-job-details.php?jobPostCode="+jobPostCode+"&shareLink="+shareLink;
    hr.open("GET", url, true);
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;
			document.getElementById("jobPostModal").innerHTML = return_data;
			addthis.toolbox('.addthis_toolbox');
			addthis.counter('.addthis_counter');
	    }
    }
    hr.send(null); 
	$("#jobPostModal").modal('show');
}

function applyToJob(jobPostCode){
	var username = document.getElementById("user").value;
	var hr = new XMLHttpRequest();
    var url = "check-login-status.php?username="+username+"&jobPostCode="+jobPostCode;
    hr.open("GET", url, true);
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;
			if(return_data == "Apply Job"){
				window.location.href = "confirm-apply-job.php";
			}
			else if(return_data == "Invalid"){
				$("#jobPostModal").modal('hide');
				$('#invalidAccountModal').modal('show');
			}
			else{
				window.location.href = "login.php?session=false&applyingjob=true";
			}
	    }
    }
    hr.send(null); 
}
function createEmployeeAccount(){
	window.location.href = "sign-up.php?applyingjob=true";
}

function closeDropDown(){
	$('[data-toggle="dropdown"]').parent().removeClass('open');
}

function changePagination(pageNum){
	var resultsFileURL = "display-new-jobs.php";
	$(".flash").show();
	$(".flash").fadeIn(400).html('<center><p>Loading...</p><img src="./img/ajax-loader2.gif"/></center>');
	if(pageNum!=0){
		$(".flash2").show();
		$(".flash2").fadeIn(400).html('<center><p>Loading...</p><img src="./img/ajax-loader2.gif"/></center>');
	}
	var dataString = 'pageNum='+ pageNum;
	$.ajax({
	   type: "GET",
	   url: resultsFileURL,
	   data: dataString,
	   cache: false,
	   success: function(result){
	  		$(".flash").hide();
	  		$(".flash2").hide();
	        $("#newJobPostsDiv").html(result);
	   }
	});
}
</script>
<script src="./js/bootstrap.min.js"></script>
<!-- JS scripts -->
</body>
</html>