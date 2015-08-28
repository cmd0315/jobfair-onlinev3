<?php
include './includes/functions.php';
include './includes/db.php';
include './includes/generate-code.php';

//GET CURRENT DATE
$date = date('Y-m-d');
$year = date('Y');
$day = date('d');
$isBrowsing = $_GET['browsing'];
if($isBrowsing == "true") {
	header("Location: login.php?session=false&accttype=1");
}
else{
	/* GET SESSION DATA*/
	session_start();
	if(isset($_SESSION['SRIUsername']) && $_SESSION['SRIUsername'] != ""){
		$username = $_SESSION['SRIUsername'];
	}
	else{
		header("Location: index.php");
	}
}

//GET SESSION VARIABLES
$position = $_SESSION['SRIJobPosition'];
$numVacancies = $_SESSION['SRIJobNumVacancies'];
$location = $_SESSION['SRIJobLocation'];
$street = $_SESSION['SRIJobStreet'];
$jobDesc = $_SESSION['SRIJobDesc'];

//set Profile Box Info (if session variables not available)
$status = getAcctInfo($username, "status");
$coName = getEmployerData($username, "company-name");
$location = getEmployerData($username, "address");
$profilePic = getEmployerData($username, "profile_pic");

//set minimum open/close date
$minBirthDate = strtotime('-60 years');
$minBirthDate =  date('Y-m-d', $minBirthDate);
$jobPositions = getJobPositions(); //job positions
$jobLocations = getJobLocations(); //job locations

$jobPostCode = generateCode(); //generate random code
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
	<?php echo $navbar=getEmployerNavBar($username); ?>
	<!-- END SEARCH NAVBAR  -->
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span7 offset1">
				<div class="row-fluid">
					<h4>To post a job listing, kindly complete this form.</h4>
					<p class="text-info" style="font-weight:bold;">*Required fields</p><br>
				</div>
				<div class="row-fluid">
					<div class="span12 well">
						<h4 class="content-heading2">JOB SPECIFICATIONS</h4>
						<!-- START FORM -->
						<form method="POST" name="jobPostForm" id="jobPostForm" class="form-horizontal" action="process-add-jobpost.php">
							<div class="control-group">
								<label class="control-label" style="margin-top:3px;">*JOB SITE</label>
								<div class="controls">
									<select class="span12" id="location" name="location" data-placeholder="Choose a job location" required>
										<option></option>
										<?php
											foreach($jobLocations as $jL) {
												if($location !== "" && $location == $jL){
													echo "<option value=\"$jL\" selected=\"selected\">$jL</option>";
												}
												else
													echo "<option value=\"$jL\">$jL</option>";
											}
										?>
									</select>
								  <p class="help-block"></p>
								</div>
							 </div>
							 <div class="row-fluid">
								 <div class="span9 offset3">
									 <div class="control-group">
										<label class="control-label" style="margin-top:-4px; font-weight:normal;">*STREET ADDRESS</label>
										<div class="controls">
											<input class="span12" type="text" id="street" name="street" value="<?php echo $street; ?>" required />
										  <p class="help-block"></p>
										</div>
									</div>
								</div>
							</div>
							 <div class="control-group">
								<label class="control-label" style="margin-top:4px;">*JOB POSITION</label>
								<div class="controls">
									<select class="span12" id="position" name="position"  data-placeholder="Choose a job position" required>
										<option></option>
										<?php
											foreach($jobPositions as $jP) {
												if($position !== "" && $position == $jP){
													echo "<option value=\"$jP\" selected=\"selected\">$jP</option>";
												}
												else
													echo "<option value=\"$jP\">$jP</option>";
											}
										?>
									</select>
								  <p class="help-block"></p>
								</div>
							</div>
							 <div class="control-group">
								<label class="control-label" style="margin-top:-4px;">*NO. OF VACANCIES</label>
								<div class="controls">
									<input class="span4" type="number" min="1" max="100" id="numVacancies" name="numVacancies" placeholder="1" value="<?php echo $numVacancies; ?>" required />
								  <p class="help-block"></p>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" style="margin-top:-4px;">*JOB DESCRIPTION</br><span id="formLabel" style="font-weight:normal; color:#404040;">(Brief description of the job post)</span></label>
								<div class="controls">
									<textarea class="input-block-level" id="jobDesc" name="jobDesc" placeholder="Give a brief description of the job post" maxlength="650" required><?php echo $jobDesc; ?></textarea>
								  <p class="help-block"></p>
								</div>
							</div>
					</div>
				</div><br>
				<div class="row-fluid">
					<div class="span12 well">
						<h4 class="content-heading2">JOB REQUIREMENTS</h4>
						<div class="row-fluid">
							<div class="span2 offset2">
								<label style="text-align:right;">*SEX</label>
							</div>
							<div class="span8">
								<label class="radio inline" id="gender-option-label"><input type="radio" name="eSex" id="sex1" value="M" required>M</label>
								<label class="radio inline" id="gender-option-label"><input type="radio" name="eSex" id="sex2" value="F" required>F</label>
								<label class="radio inline" id="gender-option-label"><input type="radio" name="eSex" id="sex3" value="NR" required>Not Required</label>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span2 offset2">
								<label style="text-align:right;">*CIVIL STATUS</label>
							</div>
							<div class="span8">
								<label class="radio inline" id="gender-option-label"><input type="radio" name="eCivilStatus" id="eCivilStatus1" value="Single" required>Single</label>
								<label class="radio inline" id="gender-option-label"><input type="radio" name="eCivilStatus" id="eCivilStatus2" value="Married" required>Married</label>
								<label class="radio inline" id="gender-option-label"><input type="radio" name="eCivilStatus" id="eCivilStatus3" value="Not Required" required>Not Required</label>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span2 offset2">
								<label style="text-align:right;">AGE</label>
							</div>
							<div class="span8">
								<span class="extra-label"><input class="span5" type="number" min="17" max="60" id="eMinAge" name="eMinAge" placeholder="Min"/>
								to <input class="span5" type="number" min="17" max="60" id="eMaxAge" name="eMaxAge" placeholder="Max"/></span>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span2 offset2">
								<label  style="text-align:right;">HEIGHT<br><span id="formLabel" style="font-weight:normal; color:#404040;">( ft., in. )</span></label>
							</div>
							<div class="span4">
								<span><input class="span5" type="number" min="4" max="10" id="eHeightFt" name="eHeightFt"/>
								<input class="span5" type="number" id="eHeightIn" min="0" max="59" id="eHeightIn" name="eHeightIn"/></span>
							</div>
							<div class="span4">
								<div class="row-fluid">
									<div class="span4">
										<label style="text-align:right;">WEIGHT<br><span id="formLabel" style="font-weight:normal; color:#404040;">( lbs. )</span></label>
									</div>
									<div class="span6">
										<input class="span12" type="number" min="60" max="300" id="eWeight" name="eWeight"/>
									</div>
								</div>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span3 offset1">
								<label style="text-align:right;">EDUC. ATTAINMENT</label>
							</div>
							<div class="span7">
								<select class="span12" id="eEducAttainment" name="eEducAttainment" >
								  <option value="High School Graduate">High School Graduate</option>
								  <option value="College Graduate">College Graduate</option>
								</select>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span2 offset2" >
								<label style="text-align:right;">OTHERS</label>
							</div>
							<div class="span7">
								<input type="text" class="span12"  id="otherReq1" name="otherReq1" placeholder="Add Requirement"/>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span8 offset4">
								<span id="add1" style="display:block;">
									<input type="text" class="span10" id="otherReq2" name="otherReq2" placeholder="Add Requirement">
									<button type="button" class="btn-primary btn-mini" id="addBtn1" style="margin-top:-5px;" name="1" onclick="addReqField(this.name);" style="display:inline;"> 
									<i id="icon1" class="icon-plus-sign icon-white" style="margin-left:-2px; padding-top:1px;"></i></button>
								</span>
								<?php
									for($i=2; $i<49; $i++) {
										$add = "add" . $i;
										$addBtn = "addBtn" . $i;
										$icon = "icon" . $i;
										$next = $i+1; 
										$otherReq = "otherReq" . $next;
										
										echo "<span id=\"$add\" style=\"display:none;\">
										<input type=\"text\" class=\"span10\"  id=\"$otherReq\" name=\"$otherReq\" placeholder=\"Add Requirement\">
										<button type=\"button\" class=\"btn-primary btn-mini\" id=\"$addBtn\" name=\"$i\" onclick=\"addReqField(this.name);\" style=\"margin-top:-5px; border-radius: 25px; width: 26px; height: 26px; box-shadow: 2px 2px 5px #888888;\"><i id=\"icon$i\" class=\"icon-plus-sign icon-white\" style=\"margin-left:-2px; padding-top:1px;\"></i></button></span>";
									}
								?>
							</div>
						</div>
					</div>
				</div><br>
				<div class="row-fluid">
					<div class="span12 well">
						<div class="row-fluid">
							<h4 class="content-heading2">JOB EXPIRATION</h4>
							<div class="row-fluid">
								<div class="span5" style="text-align:right;">
									<label class="control-label">This position will be available for</label>
								</div>
								<div class="span5 offset1">
									<select class="span12" id="expiration" name="expiration" required>
										<option value="30">30 Days</option>
										<option value="60">60 Days</option>
										<option value="90">90 Days</option>
									</select>
								  <p class="help-block"></p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- hidden variables -->
				<input type="hidden" name="jobPostCode" id="jobPostCode" value="<?php echo $jobPostCode;?>"/>
				<div class="row-fluid">
					<div class="span4 offset8">
						<button type="submit" class="btn btn-primary job-post-add span12" id="postJob" name="postJob">POST JOB</button>				
						</form>
						<!-- END FORM -->
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
	</div>
	<br/><br/><br/>
	<div class="push"></div>
</div>
<!-- START FOOTER -->
<?php echo $footer=getFooter(); ?>
<!-- END FOOTER -->
<!-- START MODAL-->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">
  <div class="modal-header">
	<h4 class="content-heading4" id="modal-title">Job Post Added!</h4>
  </div>
  <div class="modal-body">
	<div class="row-fluid">
		<div class="span9 offset1" style="padding-left:25px;">
			  <p style="text-align:center;">Congratulations!  Job post with opening on position <span class="text-info"><strong><span id="positionLabelModal"></span></strong></span> has been added to our job post listings.
			  You can check your dashboard to see updates regarding this job post.</p>
		</div>
	</div>
  </div>
  <div class="modal-footer">
	<div class="row-fluid">
		<div class="span10 offset2" style="padding-left:30px;">
			<button class="btn btn-primary input-medium" onclick="showDashboard();">DASHBOARD</button>
			<button class="btn btn-primary input-large"  onclick="showAddJobPage();">ADD NEW JOB POST</button>
		</div>
	</div>
  </div>
</div>
<!-- END MODAL -->
<!-- START ERROR MODAL-->
<?php echo $formErrorModal=getFormsErrorModal(); ?>
<!-- END ERROR MODAL-->
<!-- JS scripts -->
<script src="./js/jquery.min.js"></script>
<script src="./js/jqBootstrapValidation.js"></script>
<script src="./js/jquery.form.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script src="./js/select2.js"></script>
<script>
	$(document).ready(function() { 
		$("#position").select2(); 
		$("#eEducAttainment").select2(); 
		$("#eEducAttainment").select2();
		$("#location").select2(); 
		$("#expiration").select2();
	});
	
	$(document).ready(function() { $(function () { $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); } ); });
	
	function addReqField(inptBtn) {
		var nxtVal = parseInt(inptBtn) + 1;
		var currReqFieldName = "add" + inptBtn
		var nxtReqFieldName = "add" + nxtVal.toString();
		var btnName = "addBtn" + inptBtn;
		var icoName = "icon" + inptBtn;
		var currFieldStat = document.getElementById(currReqFieldName).style.display;
		var nxtFieldStat = document.getElementById(nxtReqFieldName).style.display;
		var btnType = document.getElementById(btnName).className;
		//change add button type
		if(btnType == "btn-primary btn-mini"){
			document.getElementById(btnName).className="btn-inverse btn-mini";
			document.getElementById(icoName).className="icon-minus-sign icon-white";
			document.getElementById(nxtReqFieldName).style.display="block";
		}
		else{
			document.getElementById(currReqFieldName).style.display="none";
		}
	}
	
	 $('#position').change(function() { 
		var pos = document.getElementById('position').value;  
		document.getElementById('positionLabelModal').innerHTML=  pos;
	 });
	
	// bind to the form's submit event 
	 $('#jobPostForm').submit(function() { 
		//validate inputs
		var errors = 0;
		var fields = new Array();
		var requiredFields = new Array();
		var errorMsg = "";
		var street = document.getElementById('street').value;
		var jobDesc = document.getElementById('jobDesc').value;
		var eMinAge = document.getElementById('eMinAge').value;
		var eMaxAge = document.getElementById('eMaxAge').value;
		var eHeightFt = document.getElementById('eHeightFt').value;
		var eHeightIn = document.getElementById('eHeightIn').value;
		var eWeight = document.getElementById('eWeight').value;
		eMinAge= parseInt(eMinAge);
		eMaxAge = parseInt(eMaxAge);
		eHeightFt= parseInt(eHeightFt);
		eHeightIn = parseInt(eHeightIn);
		eWeight = parseInt(eWeight);
		fields.push(street, jobDesc, eMinAge, eMaxAge, eHeightFt, eHeightIn,eWeight);
		requiredFields.push(1,1,0,0,0,0,0);
			
		for (var f = 0; f < fields.length; f++) {
			var fElem = fields[f];
			var fReq = requiredFields[f];
			if(fReq > 0 && fElem.match(/^\s*$/)){
				errors = 1;
				errorMsg = "<li>Please make sure that all required fields are filled.</li>";
			}
		}
		
		if(numVacancies < 0 || numVacancies === ""){
			errorMsg += "<li>Provide available <strong>number of vacancies</strong> .</li>";
			errors +=1;
		}
		if(eMinAge<18 || eMinAge > 60){
			errorMsg += "<li>Inputted <strong>employee minimum age</strong> should not be between 18-60 yrs. old only</li>";
			errors +=1;
		}
		if(eMaxAge<18 || eMaxAge > 60){
			errorMsg += "<li>Inputted <strong>employee maximum age</strong> should not be between 18-60 yrs. old only</li>";
			errors +=1;
		}
		
		if(eHeightFt<4 || eHeightFt > 10){
			errorMsg += "<li>Inputted <strong>employee height in ft.</strong> should not be lesser than 4 ft. or greater than 10 ft.</li>";
			errors +=1;
		}
		
		if(eHeightIn<0 || eHeightIn > 9){
			errorMsg += "<li>Inputted <strong>employee height in.</strong> should not be lesser than 0 in. or greater than 9 in.</li>";
			errors +=1;
		}
		
		if(eWeight<60 || eWeight> 300){
			errorMsg += "<li>Inputted <strong>employee weight in lbs.</strong> should not be lesser than 60 lbs. or greater than 9 lbs.</li>";
			errors +=1;
		}
		
		if(errors>0){
			document.getElementById('errorContent').innerHTML = errorMsg;
			$("#errorModal").modal('show');
		}
		else{
			$(this).ajaxSubmit(); 
			$("#myModal").modal('show');
		}
		return false;
	});
	
	function showAddJobPage() {
		window.location.href = "add-job-post.php";
	}
	
	function showDashboard() {
		window.location.href = "employer-manage-jobposts.php";
	}
</script>
<!-- JS scripts -->
</body>
</html>