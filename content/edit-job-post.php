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

//set minimum open/close date
$minBirthDate = strtotime('-60 years');
$minBirthDate =  date('Y-m-d', $minBirthDate);
//GET ALL JOB POSITIONS LISTED
$jobPositions = getJobPositions();

//GET ALL AVAILABLE JOB LOCATIONS
$jobLocations = getJobLocations();

//get job post details
$jobPostCode = $_GET['code'];
$jobPostsQuery = "SELECT * FROM job_post WHERE code='$jobPostCode' ";
$getJobPosts = mysql_query($jobPostsQuery) or die(mysql_error());
$jobPostsData = mysql_fetch_assoc($getJobPosts);
$jobSite = $jobPostsData['location_id'];
$jobSite = getJobLocation($jobSite);
$street = $jobPostsData['street'];
$position = $jobPostsData['job_position'];
$position = getJobPost($jobPostCode, "job-pos-name");
$numVacancies = $jobPostsData['num_vacancies'];
$jobDesc = $jobPostsData['job_desc'];
$eSex = $jobPostsData['e_sex'];
if($eSex === "M"){
	$male = "checked";
	$female = "required";
	$nr = "required";
}
else if($eSex === "F"){
	$male = "required";
	$female = "checked";
	$nr = "required";
}
else{
	$male = "required";
	$female = "required";
	$nr = "checked";
}

$eCivilStatus = $jobPostsData['e_civil_status'];
if($eCivilStatus === "Single"){
	$single = "checked";
	$married = "required";
	$nr2 = "required";
}
else if($eCivilStatus === "Married"){
	$single = "required";
	$married = "checked";
	$nr2 = "required";
}
else{
	$single = "required";
	$married = "required";
	$nr2 = "checked";
}
$eMinAge = $jobPostsData['e_min_age'];
$eMaxAge = $jobPostsData['e_max_age'];
$eHeight = $jobPostsData['e_height'];
$eHeight = explode("'", $eHeight);
$eHeightFt = $eHeight[0];
$eHeightIn = $eHeight[1];
$eWeight = $jobPostsData['e_weight'];
$eEducAttainment = $jobPostsData['e_educ_attainment'];
$jobOpenDate = $jobPostsData['job_opendate'];
$jobCloseDate = $jobPostsData['job_closedate'];

//get reqirements
$requirementEntryQuery = "SELECT * FROM requirements WHERE post_code='$jobPostCode' ";
$getRequirement = mysql_query($requirementEntryQuery) or die(mysql_error());
$requirementCount = mysql_num_rows($getRequirement);
$num = $requirementCount + 1;
$otherReq2 = "otherReq" . $num;
$addBtn1 = "addBtn" . $num;
$icon1 = "icon" . $num;

$startTimeStamp = strtotime($jobOpenDate);
$endTimeStamp = strtotime($jobCloseDate);

$timeDiff = abs($endTimeStamp - $startTimeStamp);

$numberDays = $timeDiff/86400;  // 86400 seconds in one day
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
<link rel="stylesheet" href="./css/datepicker.css"/>
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
					<h4>Edit Job Post: <span class="text-info"><?php echo $position . " (#" . $jobPostCode . ")" ;?></span></h4>
					<p class="text-info" style="font-weight:bold;">*Required fields</p><br>
				</div>
				<div class="row-fluid">
					<div class="span12 well">
						<h4 class="content-heading2">JOB SPECIFICATIONS</h4>
						<!-- START FORM -->
						<form method="POST" name="jobPostForm" id="jobPostForm" class="form-horizontal" action="process-edit-jobpost.php">
							<div class="control-group">
								<label class="control-label" style="margin-top:3px;">*JOB SITE</label>
								<div class="controls">
									<select class="span12" id="location" name="location" data-placeholder="Choose a job location" required>
										<option></option>
										<?php
											foreach($jobLocations as $jL) {
												if($jL === $jobSite){
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
											<input class="span12" type="text" id="street" name="street" value="<?php echo $street;?>" required />
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
												if($jP === $position){
													echo "<option value=\"$jP\" selected=\"selected\">$jP</option>";
												}
												else{
													echo "<option value=\"$jP\">$jP</option>";
												}
											}
										?>
									</select>
								  <p class="help-block"></p>
								</div>
							</div>
							 <div class="control-group">
								<label class="control-label" style="margin-top:-4px;">*NO. OF VACANCIES</label>
								<div class="controls">
									<input class="span4" type="number" min="1" max="100" id="numVacancies" name="numVacancies" placeholder="1" value="<?php echo $numVacancies;?>" required />
								  <p class="help-block"></p>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" style="margin-top:-4px;">*JOB DESCRIPTION</br><span id="formLabel" style="font-weight:normal; color:#404040;">(Brief description of the job post)</label>
								<div class="controls">
									<textarea class="input-block-level" id="jobDesc" name="jobDesc" placeholder="Give a brief description of the job post" maxlength="650" required><?php echo $jobDesc;?></textarea>
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
								<label class="radio inline" id="gender-option-label"><input type="radio" name="eSex" id="sex1" value="M" <?php echo $male;?>>M</label>
								<label class="radio inline" id="gender-option-label"><input type="radio" name="eSex" id="sex2" value="F" <?php echo $female;?>>F</label>
								<label class="radio inline" id="gender-option-label"><input type="radio" name="eSex" id="sex3" value="NR" <?php echo $nr;?>>Not Required</label>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span2 offset2">
								<label style="text-align:right;">*CIVIL STATUS</label>
							</div>
							<div class="span8">
								<label class="radio inline" id="gender-option-label"><input type="radio" name="eCivilStatus" id="eCivilStatus1" value="Single" <?php echo $single;?>>Single</label>
								<label class="radio inline" id="gender-option-label"><input type="radio" name="eCivilStatus" id="eCivilStatus2" value="Married" <?php echo $married;?>>Married</label>
								<label class="radio inline" id="gender-option-label"><input type="radio" name="eCivilStatus" id="eCivilStatus3" value="Not Required" <?php echo $nr2;?>>Not Required</label>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span2 offset2">
								<label style="text-align:right;">AGE</label>
							</div>
							<div class="span8">
								<span class="extra-label"><input class="span5" type="number" id="eMinAge" name="eMinAge" placeholder="Min" min="17" max="60" value="<?php echo $eMinAge;?>"/>
								to <input class="span5" type="number" id="eMaxAge" name="eMaxAge" placeholder="Max" min="15" max="60" value="<?php echo $eMaxAge;?>"/></span>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span2 offset2" >
								<label style="text-align:right;">HEIGHT<br><span id="formLabel" style="font-weight:normal; color:#404040;">( ft., in. )</span></label>
							</div>
							<div class="span4">
								<span><input class="span5" type="number" min="4" max="10" id="eHeightFt" name="eHeightFt" value="<?php echo $eHeightFt;?>"/>
								<input class="span5" type="number" id="eHeightIn" id="eHeightIn" name="eHeightIn" value="<?php echo $eHeightIn;?>"/></span>
							</div>
							<div class="span4">
								<div class="row-fluid">
									<div class="span4">
										<label style="text-align:right;">WEIGHT<br><span id="formLabel" style="font-weight:normal; color:#404040;">( lbs. )</span></label>
									</div>
									<div class="span6">
										<input class="span12" type="number" id="eWeight" name="eWeight" value="<?php echo $eWeight;?>" />
									</div>
								</div>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span3 offset1">
								<label style="text-align:right;">EDUC. ATTAINMENT</label>
							</div>
							<div class="span7">
								<select class="span12" id="eEducAttainment" name="eEducAttainment">
								  <?php
									if($eEducAttainment === "High School Graduate"){
										$content = "<option value=\"High School Graduate\" selected=\"selected\">High School Graduate</option>
															<option value=\"College Graduate\">College Graduate</option>";
									}
									else if($eEducAttainment === "College Graduate"){
										$content = "<option value=\"High School Graduate\">High School Graduate</option>
															<option value=\"College Graduate\" selected=\"selected\">College Graduate</option>";
									}
									else{
										$content = "<option value=\"High School Graduate\">High School Graduate</option>
															<option value=\"College Graduate\">College Graduate</option>";
									}
									echo $content;
								  ?>
								</select>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span2 offset2">
								<label style="text-align:right;">OTHERS</label>
							</div>
							<div class="span7">
								<?php
									while($requirementData = mysql_fetch_assoc($getRequirement)){
										$c += 1;
										$otherReq = "otherReq" . $c;
										$reqValue = $requirementData['req_name'];
										echo "<input type=\"text\" class=\"span12\"  id=\"$otherReq\" name=\"$otherReq\" value=\"$reqValue\"/>";
									}
								?>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span8 offset4">
								<span id="add1" style="display:block;">
									<input type="text" class="span10" id="<?php echo $otherReq2;?>" name="<?php echo $otherReq2;?>" placeholder="Add Requirement">
									<button type="button" class="btn-primary btn-mini" id="<?php echo $addBtn1;?>" style="margin-top:-5px;" name="<?php echo $num;?>" onclick="addReqField(this.name);" style="display:inline;"> 
									<i id="<?php echo $icon1; ?>" class="icon-plus-sign icon-white" style="margin-left:-2px; padding-top:1px;"></i></button>
								</span>
								<?php
									$j = $requirementCount + 2;
									for($i=$j; $i<49; $i++) {
										$add = "add" . $i;
										$addBtn = "addBtn" . $i;
										$icon = "icon" . $i;
										$next = $i+1; 
										$otherReq3 = "otherReq" . $next;
										
										echo "<span id=\"$add\" style=\"display:none;\">
										<input type=\"text\" class=\"span10\"  id=\"$otherReq3\" name=\"$otherReq3\" placeholder=\"Add Requirement\">
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
									<select class="span12" id="expiration" name="expiration">
										<?php
											echo "<option></option>";
											if($numberDays <= 30){
												echo "<option value=\"30\" selected=\"selected\">30 Days</option>
												<option value=\"60\">60 Days</option>
												<option value=\"90\">90 Days</option>";
											}
											else if($numberDays>30 && $numberDays <= 60){
												echo "<option value=\"30\">30 Days</option>
												<option value=\"60\" selected=\"selected\">60 Days</option>
												<option value=\"90\">90 Days</option>";
											}
											else if($numberDays>60 && $numberDays <= 90){
												echo "<option value=\"30\">30 Days</option>
												<option value=\"60\">60 Days</option>
												<option value=\"90\" selected=\"selected\">90 Days</option>";
											}
										?>
									</select>
								  <p class="help-block"></p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- hidden variables -->
				<input type="hidden" name="jobPostCode" id="jobPostCode" value="<?php echo $jobPostCode;?>"/>
				<input type="hidden" name="position" id="position" value="<?php echo $position;?>"/>
				<div class="row-fluid">
					<div class="span4 offset8">
						<button type="submit" class="btn btn-primary job-post-add span12" id="postJob" name="postJob">SAVE CHANGES</button>				
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
		<br/><br/><br/>
	</div>
	<div class="push"></div>
</div>
<!-- START MODAL-->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
	<h4 class="content-heading4" id="modal-title">Job Post Updated!</h4>
  </div>
  <div class="modal-body">
	<div class="row-fluid">
		<div class="span9 offset1" style="padding-left:25px;">
			  <p style="text-align:center;">Congratulations! Job post with opening on position <span class="text-info"><strong><span id="positionLabelModal"></span></strong></span> has been successfully updated.</p>
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
<!-- START FOOTER -->
<?php echo $footer=getFooter(); ?>
<!-- END FOOTER -->

<!-- JS scripts -->
<script src="./js/jquery.min.js"></script>
<script src="./js/jqBootstrapValidation.js"></script>
<script src="./js/jquery.form.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script src="./js/bootstrap-datepicker.js"></script>
<script src="./js/select2.js"></script>
<script>
$(document).ready(function() { 
	$("#position").select2(); 
	$("#eEducAttainment").select2();
	$("#location").select2();
	$("#expiration").select2();

	$('#jobPostForm').ajaxForm( { //ajax submit form
		beforeSubmit: validateForm,
		complete: function(xhr) {
			var positionLabel = document.getElementById('positionLabelModal');
			positionLabel.innerHTML = document.getElementById('position').value;
			$("#myModal").modal('show');
		}
	}); 
});

$(document).ready(function() { $(function () { $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); } ); });

/*modified from http://malsup.com/jquery/form/#validation */
function validateForm(formData, jqForm, options) { 
    var form = jqForm[0]; 
   	var errors = 0;
	var fields = new Array();
	var requiredFields = new Array();
	var errorMsg = "";
	var street = document.getElementById('street').value;
	var jobDesc = document.getElementById('jobDesc').value;
	var numVacancies = document.getElementById('numVacancies').value;
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
	if(eMinAge<15 || eMinAge > 60){
		errorMsg += "<li>Inputted <strong>employee minimum age</strong> should be between 15-60 yrs. old only</li>";
		errors +=1;
	}
	if(eMaxAge<15 || eMaxAge > 60){
		errorMsg += "<li>Inputted <strong>employee maximum age</strong> should be between 15-60 yrs. old only</li>";
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
	
	if(errors>0){
		document.getElementById('errorContent').innerHTML = errorMsg;
		$("#errorModal").modal('show');
		return false;
	}
}

function addReqField(inptBtn) {
	var nxtVal = parseInt(inptBtn) + 1;
	var currReqFieldName = "add" + inptBtn;
	var nxtReqFieldName = "add" + nxtVal.toString();
	var btnName = "addBtn" + inptBtn;
	var icoName = "icon" + inptBtn;
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

function showAddJobPage() {
	window.location.href = "add-job-post.php";
}

function showDashboard() {
	window.location.href = "employer-manage-jobposts.php";
}
</script>
<script>
 //for expiration dates
var nowTemp = new Date();
var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
 
var checkin = $('#openDate').datepicker({
  onRender: function(date) {
    return date.valueOf() < now.valueOf() ? 'disabled' : '';
  }
}).on('changeDate', function(ev) {
  if (ev.date.valueOf() > checkout.date.valueOf()) {
    var newDate = new Date(ev.date)
    newDate.setDate(newDate.getDate() + 1);
    checkout.setValue(newDate);
  }
  checkin.hide();
  $('#closeDate')[0].focus();
}).data('datepicker');
var checkout = $('#closeDate').datepicker({
  onRender: function(date) {
    return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
  }
}).on('changeDate', function(ev) {
  checkout.hide();
}).data('datepicker');
</script>
<!-- JS scripts -->
</body>
</html>