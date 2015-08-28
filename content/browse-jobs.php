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

$jobPositions = getJobPositionsWithID();//GET ALL JOB POSITIONS LISTED
$availableLocations= getJobLocationsWithID(); //GET ALL LOCATIONS LISTED

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
<div id="wrap">
	<!-- START HEADER -->
	<?php echo $header = getHeader('Default'); ?>
	<!-- END HEADER -->
	<!-- START SEARCH NAVBAR  -->
	<?php echo $navbar=getApplicantNavBar($username); ?>
	<!-- END SEARCH NAVBAR  -->
	<div class="container-fluid" style="padding-top: 40px;">
		<div class="row-fluid" id="content">
			<div class="span7 offset1"> 
				<h4 class="content-heading5" style="font-weight:bold; margin-left: 20px;">Choose a Job</h4>
				<div class="row-fluid" style="margin-top: -10px;">
					<div class="span12 well">
						<div class="row-fluid">
							<div class="span6">
								<div class="row-fluid">
									<div class="span3">
										<h4>Location:</h4>
									</div>
									<div class="span9" style="padding-top:6px;">
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
									<div class="span4">
										<h4>Position:</h4>
									</div>
									<div class="span8" style="padding-top:6px;">
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
								<button class="btn btn-medium btn-primary" id="searchJob" name="searchJob" type="button" onclick="displayJobs(0);">Search</button>
							</div>
						</div>
					</div>
				</div> 
				<!-- START JOB POSTS DIV-->
				<div class="row-fluid" id="jobPostsDiv" name="jobPostsDiv" style="margin-bottom: 20px; display:<?php echo $display; ?>"></div>
				<!-- END JOB POSTS DIV-->
				<!-- START NEW JOB POSTS DIV-->
				<div class="row-fluid" style="padding-top: 20px;">
					<div class="span12 well" id="newJPDiv">
						<h4 class="content-heading5">All Jobs Available</h4>
						<div class="span1 offset5 flash"></div>
						<div class="row-fluid" id="newJobPostsDiv"><!-- newJobPostItems --></div>
					</div>
				</div>
				<!-- END NEW JOB POSTS DIV-->
			</div>
			<div class="span3">
				<!-- START PROFILE BOX -->
				<?php echo $profileBox=getProfileBox($status, $fullName, $age, $address, $profilePic);?>
				<!-- END PROFILE BOX -->
				<div class="row-fluid">
					<div class="span10 offset1">
						<div class="alert alert-info">
							<p>If you still haven’t found what you’re looking for, contact us at <a href="mailto: srinbs@sri.ph">srinbs@sri.ph</a> or call +63917-823-5978</p>
						</div>
					</div>
				</div>
			</div>
			<div class="span3">
				<div class="row-fluid">
					<div class="span6 offset3" style="display:<?php if($jobLocationId=="") echo "none"; ?>">
						<button class="btn-info span12" id="scroll-down" name="scroll-down"><i class="icon-arrow-down icon-white"></i>View Search Results</button>
					</div>
				</div>
			</div>
			<!-- hidden variables-->
			<input type="hidden" name="username" id="username" value="<?php echo $username;?>"/>
			<input type="hidden" name="jLId" id="jLId" value="<?php echo $jobLocationId;?>">
		</div>		
	</div>
	<div class="push"></div>
</div>
<!-- START FOOTER -->
<?php echo $footer=getFooter(); ?>
<!-- END FOOTER -->
<!-- START JOB POST MODAL-->
<div id="jobPostModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<!-- END JOB POST MODAL -->
</div>
<!-- JS scripts -->
<script src="./js/jquery.min.js"></script>
<script src="./js/select2.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-51e2c4c131144687"></script>
<script>
$(document).ready(function() { 
	changePagination(0); //display new jobs available
	$("#jobLocation").select2(); 
	$("#jobTitle").select2(); 
	$(".collapse").collapse();
});
</script>
<script>
/* scrolls down page */
$(function() {
	// the element inside of which we want to scroll
	var $elem = $('#content');
	// clicking the "down" button will make the page scroll to the $elem's height
	$('#scroll-down').click(
		function (e) {
			$('html, body').animate({scrollTop: $elem.height()}, 800);
		}
	);
});

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
	var username = document.getElementById("username").value;
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

function viewResumeEmp(){
	var username = document.getElementById('username').value;
	window.open("view-resume.php?username="+username, '_blank');
}

function changePagination(pageNum){
	var resultsFileURL = "display-new-jobs.php";
	$(".flash").show();
	$(".flash").fadeIn(400).html('<center><p>Loading...</p><img src="./img/ajax-loader2.gif"/></center>');
	var dataString = 'pageNum='+ pageNum;
	$.ajax({
	   type: "GET",
	   url: resultsFileURL,
	   data: dataString,
	   cache: false,
	   success: function(result){
	  		$(".flash").hide();
	        $("#newJobPostsDiv").html(result);
	   }
	});
}
</script>
<!-- JS scripts -->
</body>
</html>