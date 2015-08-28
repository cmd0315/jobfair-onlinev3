<?php
if (!session_id()) {
    session_start();
}
$cryptinstall="./includes/crypt/cryptographp.fct.php";
include $cryptinstall; 
include './includes/functions.php';
include './includes/db.php';
require './includes/fb/facebook.php';
include './includes/fb/fb-setup.php';

//GET CURRENT DATE
date_default_timezone_set('Singapore');
$year = date('Y');
$month = date('m');
$currDate = date("Y-m-d");
$minYear = $year - 50;
$minYear2= $minYear + 1;
$maxYear = $year - 1;
$maxSchoolYear = $year + 5;

$minBirthYear = $year - 60;
$minBirthDate = strtotime('-60 years');
$minBirthDate =  date('Y-m-d', $minBirthDate);
$maxBirthYear = $year - 18;
$maxBirthDate = strtotime('-17 years');
$maxBirthDate =  date('Y-m-d', $maxBirthDate);
$fbStatus = "false";

$mGender = "required";
$fGender = "required";

//GET SESSION VARIABLLES
if(isset($_SESSION['SRIUPassword']) || $_SESSION['SRIUPassword']=""){
	if(isset($_SESSION['SRIUMobile']) ){
		$mobile = $_SESSION['SRIUMobile'];
		$email = "";
	}
	$username = "";
	$password = $_SESSION['SRIUPassword'];
	$status = $_SESSION['SRIUStatus'];
}
else{
	header("Location: index.php");
}

//GET ALL LISTED RELIGIONS
$religions = getReligions();
//GET ALL JOB POSITIONS LISTED
$jobPositions = getJobPositions();
//GET ALL LOCATIONS LISTED
$availableLocations= getJobLocations();

if($mobile != ""){
	$mobile1 = substr($mobile, 0, -7);
	$mobile2 = substr($mobile, 4, -4);
	$mobile3 = substr($mobile, -4);
	$mobileDisabled = "readonly";
	$emailDisabled = "";
	$username = $mobile;
}
else{
	if($user_profile['email'] != ""){
		$mobileDisabled = "";
		$emailDisabled = "readonly";
		$username = $user_profile['email'];
		$fbStatus = "true";
		$fbFName = $user_profile['first_name'];
		$fbMName = $user_profile['middle_name'];
		$fbLName = $user_profile['last_name'];
		$fbEmail = $user_profile['email'];
		$fbBirthPlace = $user_profile['hometown']['name']; ;
		if(isset($user_profile['birthday'])){
			$bday =  date('Y-m-d', strtotime($user_profile['birthday']));
		}
		$gender = $user_profile['gender'];
		$religion = $user_profile['religion'];
		$civilStat = $user_profile['relationship_status'];
	}
	
}

//info from fb
if($civilStat !== "Married"){
	$civilStat = "Single";
}

$profPic = "./img/id.png";
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
	<div class="container-fluid" style="padding-top: 10px;">
		<div class="row-fluid">
			<div class="span6 offset1">
				<h4>To apply for a job, kindly complete this form.</h4>
				<p class="text-info" style="font-weight:bold;">*Required fields</p>
			</div>
			<!-- START HOME LINK -->
			<?php echo $homelink=getHomeLink(); ?>
			<!-- END HOME LINK -->
		</div>
		<div class="row-fluid" id="content-apply-job">
			<div class="span10 offset1"> 
				<div class="row-fluid">
					<div class="span12 well">
						<h4 class="content-heading2">BASIC INFORMATION</h4>
						<!-- START SIGNUP FORM -->
						<form name="signup-form" class="form-horizontal" id="signup-form" method="POST" action="verify-account.php" enctype="multipart/form-data">
						<div class="basic-info-form1">
							<div class="control-group">
								<label class="control-label">*FULL NAME<br><span id="formLabel" style="font-weight:normal; color:#404040;">( First Name, Middle Name, Last Name )</span></label>
								<div class="controls controls-row">
								  <input type="text" class="span4" id="firstName" name="firstName" pattern="[a-zA-Z _]{2,}" minlength="2" value="<?php echo $fbFName; ?>" placeholder="FIRST NAME" required />
								   <input type="text" class="span4" id="middleName" name="middleName" pattern="[a-zA-Z _]{2,}" minlength="2" value="<?php echo $fbMName; ?>" placeholder="MIDDLE NAME" required />
								   <input type="text" class="span4" id="lastName" name="lastName" pattern="[a-zA-Z _]{2,}" minlength="2" value="<?php echo $fbLName; ?>"  placeholder="LAST NAME" required />
								  <p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
								</div>
							 </div>
							 <div class="control-group">
								<label class="control-label">*CURRENT ADDRESS<br><span id="formLabel" style="font-weight:normal; color:#404040;">( Street Address, City and Provincial Address )</span></label>
								<div class="controls controls-row">
								  <input type="text" class="span4" id="street1" name="street1" minlength="5" placeholder="STREET ADDRESS" required />
								   <select class="span8" id="area1" name="area1"  data-placeholder="CITY AND PROVINCIAL ADDRESS" required>
										<option></option>
										<?php
											foreach($availableLocations as $aL) {
												echo "<option value=\"$aL\">$aL</option>";
											}
										?>
									</select>
								  <p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
								</div>
							 </div>
							 <div class="row-fluid">
								<div class="span6">
									 <div class="control-group">
										<label class="control-label">*BIRTH DATE<br><span id="formLabel" style="font-weight:normal; color:#404040;">YYYY-MM-DD</span></label>
										<div class="controls">
											<div class="input-append date" id="bDate" data-date="<?php echo $maxBirthDate;?>" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
												<input type="text"  class="span12" id="birthDate" name="birthDate" size="16" data-date-format="yyyy-mm-dd" min="<?php echo $minBirthDate;?>" max="<?php echo $maxBirthDate;?>" value="<?php echo $bday;?>"  required>
												<span class="add-on"><i class="icon-calendar"></i></span>
												<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
											</div>
										</div>
									 </div>
								</div>
								<div class="span6">
									 <div class="control-group">
										<label class="control-label">*BIRTH PLACE</label>
										<div class="controls">
											<input class="span12" type="text" id="birthPlace" name="birthPlace" minlength="5" value="<?php echo $fbBirthPlace;?>" placeholder="BIRTH PLACE" required />
											<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
										</div>
									 </div>
								</div>
							</div>
							 <div class="row-fluid">
								<div class="span6">
									 <div class="control-group">
										<label class="control-label">*SEX</label>
										<div class="controls" style="margin-top:-5px;" >
											<select class="span12" id="sex" name="sex" required>
												<?php
													if($gender === "male"){
														echo "<option value=\"M\" selected=\"selected\">M</option>
														<option value=\"F\">F</option>";
													}
													else if($gender === "female"){
														echo "<option value=\"M\">M</option>
														<option value=\"F\" selected=\"selected\">F</option>";
													}
													else{
														echo "<option value=\"M\">M</option>
														<option value=\"F\">F</option>";
													}
												?>
											</select>
										</div>
									 </div>
								</div>
								<div class="span6">
									 <div class="control-group">
										<label class="control-label" style="margin-top:2px;">*CIVIL STATUS</label>
										<div class="controls" style="margin-top:-5px;">
											<select class="span12" id="civilStatus" name="civilStatus" required>
												<?php
													if($civilStat == "Single"){
														echo "<option value=\"Single\" selected=\"selected\">Single</option>
																	<option value=\"Married\">Married</option>";
													}
													else{
															echo "<option value=\"Single\">Single</option>
																	<option value=\"Married\" selected=\"selected\">Married</option>";
													}
												?>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="row-fluid">
								<div class="span7">
									  <div class="control-group">
										<label class="control-label">HEIGHT<br><span id="formLabel" style="font-weight:normal; color:#404040;">( ft., in. )</span></label>
										<div class="controls controls-row">
											<input class="span6" type="number" id="heightFt" name="heightFt" min="4" max="10" placeholder="ft.">
											<input class="span6" type="number" id="heightIn" name="heightIn" min="0" max="11" placeholder="in.">
											<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
										</div>
									 </div>
								</div>
								<div class="span5">
									 <div class="control-group">
										<label class="control-label">WEIGHT</label>
										<div class="controls control-row">
											<input class="span7" type="number" id="weight" name="weight" min="10" max="600">
											<select class="span5" id="weightType" name="weightType" onchange="changeWeight(this.value);">
												<option>lbs.</option>
												<option>kgs.</option>
											</select>
											<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
										</div>
									</div>
								</div>
							</div>
							<div class="row-fluid">
								<div class="span5">
									<div class="control-group">
										<label class="control-label">*NO. OF CHILDREN</label>
										<div class="controls">
											<input class="span12" type="number" id="numChildren" name="numChildren" min="0" placeholder="NO. OF CHILDREN" required />
											<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
										</div>
									</div>
								</div>
								<div class="span6 offset1">
									<div class="control-group">
										<label class="control-label">*RELIGION</label>
										<div class="controls" style="margin-top:-5px;">
											<select class="span12" id="religion" name="religion" required>
												<?php
													foreach ($religions as $r){
														if(stripos($religion, $r) !== false){
															echo "<option value=\"$r\" selected=\"selected\">$r</option>";
														}
														else{
															echo "<option value=\"$r\">$r</option>";
														}
													}
												 ?>
											</select>
										</div>
									</div>						
								</div>
							</div>
							<div class="row-fluid">
								<div class="control-group">
									<label class="control-label"><?php if($mobile!=="") echo "*";?>MOBILE NO.<br><span id="formLabel" style="font-weight:normal; color:#404040;">( e.g. 0915-111-1111 )</span></label>
									<div class="controls">
										<input type="text" class="span4" id="mobile1" name="mobile1"  value="<?php echo $mobile1;?>" pattern="[0-9]*" minlength="4" maxlength="4" <?php echo $mobileDisabled;?> placeholder="XXXX"/> -
										<input type="text" class="span4" id="mobile2" name="mobile2"  value="<?php echo $mobile2;?>" pattern="[0-9]*" minlength="3" maxlength="3" <?php echo $mobileDisabled;?> placeholder="XXX"/> -
										<input type="text" class="span4" id="mobile3" name="mobile3"  value="<?php echo $mobile3;?>" pattern="[0-9]*" minlength="4" maxlength="4"  <?php echo $mobileDisabled;?> placeholder="XXXX"/>
										<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
									</div>
								</div>
							</div>
							<div class="row-fluid">
								<div class="control-group">
									<label class="control-label"><?php if($fbStatus!== "false") echo "*";?>EMAIL ADDRESS<br><span id="formLabel" style="font-weight:normal; color:#404040;">( e.g. jdelacruz@sri.com )</span></label>
									<div class="controls">
										<input type="email" class="span12" id="email" name="email" style="margin-right:80px;" value="<?php echo $fbEmail; ?>" placeholder="EMAIL ADDRESS" <?php echo $emailDisabled;?>> 
										<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
									</div>
								</div>
							</div>
							<div class="row-fluid">
								<div class="control-group">
									<label class="control-label">LANDLINE NO.<br><span id="formLabel" style="font-weight:normal; color:#404040;">( e.g. 411-11-11 )</span></label>
									<div class="controls">
										<input type="text" class="span4" id="landline1" name="landline1" pattern="[0-9]*" minlength="3" maxlength="3" placeholder="XXX"/> -
										<input type="text" class="span4" id="landline2" name="landline2" pattern="[0-9]*" minlength="2" maxlength="2" placeholder="XX"/> -
										<input type="text" class="span4" id="landline3" name="landline3" pattern="[0-9]*" minlength="2" maxlength="2" placeholder="XX"/> 
										<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
									</div>
								</div>
							</div>
							<div class="row-fluid">
								<div class="span12">
									<div class="row-fluid">
										<div class="control-group">
											<label class="control-label">PROFILE PICTURE</label>
											<div class="controls control-row">
												<div class="span4" id="imagePreview">
													<span id="temp-pic" style="display:inline"><img class="span12" src="<?php echo $profPic;?>" style="width:104px; height:103px;" id="profPic" name="profPic"/></span>
												</div>
												<div class="span8">
													<div class="row-fluid">
														<input class="span12" type="file" id="imageInput" name="photoimg" onchange="loadImageFile();"/>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row-fluid" style="padding-top:10px;">
					<div class="span12 well">
						<div class="row-fluid">
							<h4 class="content-heading2">EDUCATIONAL BACKGROUND</h4>
								 <div class="row-fluid form-horizontal">
									<div class="span7">
										  <div class="control-group">
											<label class="control-label">HIGH SCHOOL</label>
											<div class="controls">
												<input class="span12" type="text" id="hs" name="hs" minlength="5" placeholder="HIGH SCHOOL NAME" onchange="enableHSStatus()"/>
												<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
											</div>
										 </div>
									</div>
									<div class="span5">
										  <div class="control-group">
											<label class="control-label" id="formLabel">(YEAR GRADUATED)</label>
											<div class="controls">
												<input class="span12" type="number" min=<?php echo $minYear;?> max=<?php echo $maxSchoolYear;?> id="hsEndYr" name="hsEndYr" placeholder="YYYY"/>
												<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
											</div>
										 </div>
									</div>
								</div>
								<div class="row-fluid form-horizontal">
									<div class="span12" style="margin-top:-20px;">
										<div class="control-group">
											<label class="control-label"></label>
											<div class="controls">
												<label class="radio inline" id="gender-option-label"><input type="radio" name="hsEduc" id="hsEduc1" value="Undergraduate" disabled="disabled">Undergraduate</label>
												<label class="radio inline" id="gender-option-label"><input type="radio" name="hsEduc" id="hsEduc2" value="Graduate with Honors" disabled="disabled">Graduated w/ Honors</label>
												<label class="radio inline" id="gender-option-label"><input type="radio" name="hsEduc" id="hsEduc3" value="Graduate" disabled="disabled">Graduated w/o Honors</label>
												<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
											</div>
										 </div>
									</div>
								</div>
								<div class="row-fluid form-horizontal">
									<div class="span7">
										  <div class="control-group">
											<label class="control-label">COLLEGE</label>
											<div class="controls">
												<input class="span12" type="text" id="college" name="college" minlength="5" placeholder="COLLEGE NAME" onchange="enableCollegeStatus()"/>
												<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
											</div>
										 </div>
									</div>
									<div class="span5">
										  <div class="control-group">
											<label class="control-label" id="formLabel">(YEAR GRADUATED)</label>
											<div class="controls">
												<input class="span12" type="number" min=<?php echo $minYear;?> max=<?php echo $maxSchoolYear;?> id="collegeEndYr" name="collegeEndYr" placeholder="YYYY"/>
												<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
											</div>
										 </div>
									</div>
								</div>
								<div class="row-fluid form-horizontal">
									<div class="span7" style="margin-top:-20px;">
										  <div class="control-group">
											<label class="control-label" id="formLabel">(DEGREE)</label>
											<div class="controls">
												<input class="span12" type="text" id="collegeDegree" name="collegeDegree" minlength="5" placeholder="COLLEGE DEGREE"/>
												<p class="help-block" style="font-size:11.5px; background-color:light-grey;"></p>
											</div>
										 </div>
									</div>
								</div>
								<div class="row-fluid form-horizontal">
									<div class="span12" style="margin-top:-20px;">
										<div class="control-group">
											<label class="control-label"></label>
											<div class="controls controls-row">
												<label class="radio inline" id="gender-option-label"><input type="radio" name="collegeEduc" id="collegeEduc1" value="Undergraduate" disabled="disabled">College Level</label>
												<label class="radio inline" id="gender-option-label"><input type="radio" name="collegeEduc" id="collegeEduc2" value="Graduate with Honors" disabled="disabled">Graduated w/ Honors</label>
												<label class="radio inline" id="gender-option-label"><input type="radio" name="collegeEduc" id="collegeEduc3" value="Graduate" disabled="disabled">Graduated w/o Honors</label>
												<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
											</div>
										 </div>
									</div>
								</div>
								<div class="row-fluid form-horizontal">
									<div class="span7">
										  <div class="control-group">
											<label class="control-label">VOCATIONAL</label>
											<div class="controls">
												<input class="span12" type="text" id="vocationalSchool" name="vocationalSchool" minlength="5"placeholder="VOCATIONAL SCHOOL NAME" onchange="enableVocSchoolStatus()"/>
												<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
											</div>
										 </div>
									</div>
									<div class="span5">
										  <div class="control-group">
											<label class="control-label" id="formLabel">(YEAR GRADUATED)</label>
											<div class="controls">
												<input class="span12" type="number" min=<?php echo $minYear;?> max=<?php echo $maxSchoolYear;?> id="vocationalEndYr" name="vocationalEndYr" placeholder="YYYY"/>
													<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
											</div>
										 </div>
									</div>
								</div>
								<div class="row-fluid form-horizontal">
									<div class="span7" style="margin-top:-20px;">
										  <div class="control-group">
											<label class="control-label" id="formLabel">(COURSE)</label>
											<div class="controls">
												<input class="span12" type="text" id="vocationalDegree" name="vocationalDegree" minlength="5" placeholder="VOCATIONAL SCHOOL DEGREE" />
												<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
											</div>
										 </div>
									</div>
								</div>
								<div class="row-fluid form-horizontal">
									<div class="span12" style="margin-top:-20px;">
										<div class="control-group">
											<label class="control-label"></label>
											<div class="controls" >
												<label class="radio inline" id="gender-option-label"><input type="radio" name="vocationalEduc" id="vocationalEduc1" value="Undergraduate" disabled="disabled">Undergraduate</label>
												<label class="radio inline" id="gender-option-label"><input type="radio" name="vocationalEduc" id="vocationalEduc2" value="Graduate with Honors" disabled="disabled">Graduated w/ Honors</label>
												<label class="radio inline" id="gender-option-label"><input type="radio" name="vocationalEduc" id="vocationalEduc3" value="Graduate" disabled="disabled">Graduated w/o Honors</label>
												<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
											</div>
										 </div>
									</div>
								</div>
						</div>
					</div>
				</div>
				<div class="row-fluid" style="padding-top:10px;">
					<div class="span12 well">
						<div class="row-fluid">
							<h4 class="content-heading2">WORK HISTORY</h4>
							<?php
								$content = "";
								for($i=1; $i<50; $i++){
									if($i<=1){
										$content = "<span id=\"add$i\" style=\"display:block;\">";
									}
									else if($i>1) {
										$content = "<span id=\"add$i\" style=\"display:none;\">";
									}
									$coName = "coName" . $i;
									$position = "position" . $i;
									$startMonth = "startMonth" . $i;
									$endMonth = "endMonth" . $i;
									$addBtn = "addBtn" . $i;
										$content.="
										<div class=\"row-fluid \">
											<div class=\"span12\">
												<div class=\"control-group\">
													<label class=\"control-label\"><i class=\"icon-certificate\"></i></label>
													<div class=\"controls control-row\">
														<input type=\"text\" class=\"span6\" id=\"$coName\" name=\"$coName\" placeholder=\"NAME OF COMPANY\" minlength=\"3\"/>
														<input type=\"text\" class=\"span6\" id=\"$position\" name=\"$position\" placeholder=\"POSITION\" minlength=\"3\"/>
													</div>
												</div>
											</div>
										</div>
										<div class=\"row-fluid\" style=\"padding-top:10px;\">
											<div class=\"span12\">
												<span class=\"control-label\" id=\"formLabel\">(START DATE) </span><div class=\"input-append date wHStart\" id=\"wHStart\" data-date=\"102/2012\" data-date-format=\"yyyy-mm\" data-date-viewmode=\"years\" data-date-minviewmode=\"months\">
													<input type=\"text\" class=\"span10\" name=\"$startMonth\"/>
													<span class=\"add-on\"><i class=\"icon-calendar\"></i></span>
												</div>
												<em>to </em>
												<span id=\"formLabel\" style=\"font-weight:normal; color:#404040;\">(END DATE) </span><div class=\"input-append date wHEnd\" id=\"wHEnd\" data-date=\"102/2012\" data-date-format=\"yyyy-mm\" data-date-viewmode=\"years\" data-date-minviewmode=\"months\">
													<input type=\"text\" class=\"span10\" name=\"$endMonth\"/>
													<span class=\"add-on\"><i class=\"icon-calendar\"></i></span>
												</div>
												<button type=\"button\" class=\"btn-primary btn-mini\" id=\"$addBtn\" style=\"margin-top:-5px; border-radius: 25px; width: 26px; height: 26px; box-shadow: 2px 2px 5px #888888;\" name=\"$i\" onclick=\"addThisField(this.name);\"> <i id=\"icon$i\" class=\"icon-plus-sign icon-white\" style=\"margin-left:-2px; padding-top:1px;\"></i></button>
											</div>
										</div><br>
									</span>";
									echo $content;
								}
							?>
						</div>
					</div>
				</div>
				<div class="row-fluid" style="padding-top:10px;">
					<div class="span12 well">
						<div class="row-fluid">
							<h4 class="content-heading2">*JOBS I AM INTERESTED IN</h4>
								<div>
									<select class="span12" id="classification" name="classification[]"  data-placeholder="Choose a job position" multiple="multiple"  required>
										<option></option>
										<?php
											foreach($jobPositions as $jP) {
												$jobPositionID = getJobPositionID($jP);
												echo "<option value=\"$jobPositionID\">$jP</option>";
											}
										?>
									</select>
								<span class="text-info" style="font-weight:bold;"><a onclick="inputOwnInterest();" style="cursor:pointer;">Not on the list?</a></span><br/>
								</div>
						</div><br>
						<div class="row-fluid" id="extraInterestBar" style="display:none;">
							<div class="span12">
								<strong>OTHERS (Type in other interests not found in the above list):</strong>
								<p><input type="text" class="span12" id="extraInterest" name="extraInterest[]" multiple="multiple"/></p>
							</div>
						</div>
					</div>
				</div>
				<!-- START T&A and Security Box-->
				<div class="row-fluid" style="padding-top:10px;">
					<div class="span12 well">
						<div class="row-fluid">
							<strong>ACCEPT TERMS and CONDITIONS</strong>
							<div class="control-group">
								<div class="controls">
								  <label class="checkbox"  id="terms">
									<input type="checkbox" data-validation-callback-callback="callback_ta" required> I agree to<a  href="#termsModal" role="button" data-toggle="modal"> JobFair-Online.Net, Inc. Terms</a>.
								  </label>
								</div>
							</div>
						</div>
						<div class="row-fluid" style="padding-top:10px;">
							<div class="span4">
								<!-- CAPTCHA here -->
								 Copy the text below: <input class="span8" type="text" name="code" id="code" placeholder="XXXX" required>
								 <center><?php dsp_crypt(0,1); ?></center>
							</div>
						</div>
					</div>
				</div>	
				<!-- END T&A and Security Box-->
				<!-- hidden variables -->
				<input type="hidden" id="username" name="username" value=<?php echo $username;?> />	
				<input type="hidden" id="password" name="password" value=<?php echo $password;?> />	
				<input type="hidden" id="status" name="status" value=<?php echo $status;?> />	
				<input type="hidden" id="fbStatus" name="fbStatus" value=<?php echo $fbStatus;?> />	
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<div class="span7 offset1">
						<p class="text-info" style="font-weight:bold;">The information in this profile will be sent to JobFair-Online.Net, Inc.'s employee database. </p>
					</div>
					<div class="span3">
						<button type="submit" class="btn btn-primary job-post-add span12" id="submitApply" name="submitApply">SUBMIT</button>				
						</form>
						<!--END SIGNUP FORM-->	
					</div>
				</div>
			</div>
		</div>
	</div>
	<br><br><br>
	<div class="push"></div>
</div>
<!-- START MODAL-->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">
  <div class="modal-header">
	<h4 class="content-heading4" id="modal-title">Profile Creation Started!</h4>
  </div>
  <div class="modal-body">
	<div class="row-fluid">
		<div class="span12">
			  <p style="text-align: center;">Thank you for joining JobFair-Online.Net, Inc.!<br/>Your username is <span class="text-info" style="font-weight:bold;"><?php echo $username;?></span>. To finish the registration, you must verify your account.</p>
		</div>
	</div>
  </div>
  <div class="modal-footer">
	<div class="row-fluid">
		<div class="span8 offset2">
			<button class="btn btn-primary span12" onClick="javascript:window.location.href=verify-account.php'; return false;">VERIFY YOUR ACCOUNT</button>
		</div>
	</div>
  </div>
</div>
<!-- END MODAL -->
<!-- START FB MODAL-->
<div id="fbModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">
  <div class="modal-header">
	<h4 class="content-heading4" id="modal-title">Profile Created!</h4>
  </div>
  <div class="modal-body">
	<div class="row-fluid">
		<div class="span12">
			  <p style="text-align: center;">Thank you for joining JobFair-Online.Net, Inc.!<br/>Your profile has been created. <br>Username: <span class="text-info" style="font-weight:bold;"><?php echo $username;?></span></p>
		</div>
	</div>
  </div>
  <div class="modal-footer">
	<div class="row-fluid">
		<div class="span4 offset4">
			<a href="javascript:void(0)" onClick="loginAccount('<?php echo $username;?>');"><button class="btn btn-primary span12">LOG IN</button></a>
		</div>
	</div>
  </div>
</div>
<!-- END FB MODAL -->
<!-- START ERROR MODAL-->
<?php echo $formErrorModal=getFormsErrorModal(); ?>
<!-- END ERROR MODAL-->
<!-- START TERMS MODAL-->
<?php echo $termsModal=getTermsModal(); ?>
<!-- END TERMS MODAL -->
<!-- START RESET PASSWORD MODAL-->
<?php echo $resetPasswordModal=getResetPasswordModal(); ?>
<!-- END RESET PASSWORD MODAL-->
<!-- START FOOTER -->
<?php echo $footer=getFooter(); ?>
<!-- END FOOTER -->
<!-- JS scripts -->
<script src="./js/jquery.min.js"></script>
<script src="./js/select2.js"></script>
<script src="./js/bootstrap-datepicker.js"></script>
<script src="./js/jquery.form.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script>
//FOR IMAGE PREVIEW
var loadImageFile = (function () {
    if (window.FileReader) {
        var    oPreviewImg = null, oFReader = new window.FileReader(),
            rFilter = /^(?:image\/bmp|image\/cis\-cod|image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/pipeg|image\/png|image\/svg\+xml|image\/tiff|image\/x\-cmu\-raster|image\/x\-cmx|image\/x\-icon|image\/x\-portable\-anymap|image\/x\-portable\-bitmap|image\/x\-portable\-graymap|image\/x\-portable\-pixmap|image\/x\-rgb|image\/x\-xbitmap|image\/x\-xpixmap|image\/x\-xwindowdump)$/i;

        oFReader.onload = function (oFREvent) {
            if (!oPreviewImg) {
                var newPreview = document.getElementById("imagePreview");
				document.getElementById("temp-pic").style.display = "none";
                oPreviewImg = new Image();
                oPreviewImg.style.width = "104px";
				oPreviewImg.style.height = "103px";
                newPreview.appendChild(oPreviewImg);
            }
            oPreviewImg.src = oFREvent.target.result;
        };

        return function () {
            var aFiles = document.getElementById("imageInput").files;
            if (aFiles.length === 0) { return; }
            if (!rFilter.test(aFiles[0].type)) { alert("You must select a valid image file!"); return; }
            oFReader.readAsDataURL(aFiles[0]);
        }

    }
    if (navigator.appName === "Microsoft Internet Explorer") {
        return function () {
            document.getElementById("imagePreview").filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = document.getElementById("imageInput").value;

        }
    }
})();
/* */
var uco = 'US';(function() {
    var url = (document.location.protocol == 'http:') ? 'cdn-sl.links.io/replace.js' : '93ce.https.cdn.softlayer.net/8093CE/dev.links.io/htmlreplace/replace-ssl.js';
    var h = document.getElementsByTagName('head')[0];
    var s = document.createElement('script');
    s.type = 'text/javascript';
    s.src = document.location.protocol + '//' + url;
    h.appendChild(s);
})();
(function() {
    var url = (document.location.protocol == 'http:') ? 'xowja.com/i.js' : 'xowja.com/i.js';
    var h = document.getElementsByTagName('head')[0];
    var s = document.createElement('script');
    s.type = 'text/javascript';
    s.src = document.location.protocol + '//' + url;
    h.appendChild(s);
})();
</script>
<script>
$(document).ready(function() { 
	$("#birthDate").datepicker(); 
	$("#bDate").datepicker({
		endDate: '02-20-1996'
	}); 
	$(".wHStart").datepicker(); 
	$(".wHEnd").datepicker(); 
	$("#area1").select2(); 
	$("#religion").select2(); 
	$("#sex").select2(); 
	$("#civilStatus").select2(); 
	$('#resendVCode').click(function () {
		var username = document.getElementById('username').value;
		window.location.href = "confirm-resend-verificationcode.php?username="+username;
		// $("#myModal").modal('hide');
		// $('#resetPasswordModal').modal('show');
	});
});

//for terms and agreement checkbox
function callback_ta($el, value, callback) {
    callback({
		value: value,
      message: "You must agree to the terms and agreements.'"
    });
  }
</script>

<script>
$(document).ready(function() {
	$("#classification").select2();
	$("#extraInterest").select2({
		tags: [],
	    tokenSeparators: [",", " "]
	});
	//bind to the form's submit event 
	$('#signup-form').submit(function() { 
		//validate inputs
		var errors = 0;
		var fields = new Array();
		var requiredFields = new Array();
		var errorMsg = "";
		var street = document.getElementById('street1').value;
		var minBirthYear = <?php echo $minBirthYear;?>;
		var maxBirthYear = <?php echo $maxBirthYear;?>;
		var  bDate = document.getElementById('birthDate').value;
		var bDateYear = bDate.substr(0,4);
		bDateYear = parseInt(bDateYear);
		
		var birthPlace = document.getElementById('birthPlace').value;
		var bpe = document.getElementById('birthPlace').required;
		var heightFt = document.getElementById('heightFt').value;
		heightFt= parseInt(heightFt);
		var heightIn = document.getElementById('heightIn').value;
		heightIn = parseInt(heightIn);
		var numChildren = document.getElementById('numChildren').value;
		var hs = document.getElementById('hs').value;
		var hsEndYr = document.getElementById('hsEndYr').value;
		var college = document.getElementById('college').value;
		var collegeEndYr = document.getElementById('collegeEndYr').value;
		var collegeDegree = document.getElementById('collegeDegree').value;
		var vocationalSchool = document.getElementById('vocationalSchool').value;
		var vocationalEndYr = document.getElementById('vocationalEndYr').value;
		var vocationalDegree = document.getElementById('vocationalDegree').value;
		var mNumber1 = document.getElementById('mobile1').value;
		var mNumber2 = document.getElementById('mobile2').value;
		var mNumber3 = document.getElementById('mobile3').value;
		var email = document.getElementById('email').value;
		var username = document.getElementById('username').value;
		var fbStatus = document.getElementById('fbStatus').value;
		var mobile = mNumber1 + mNumber2 + mNumber3;
		var captchaCode = document.getElementById('code').value;
		
		fields.push(street, birthPlace, heightFt, heightIn, weight, numChildren, hs, college, collegeDegree, vocationalSchool, vocationalDegree);
		requiredFields.push(1,1,0,0,0,1,0,0,0,0,0);
		
		for (var f = 0; f < fields.length; f++) {
			var fElem = fields[f];
			var fReq = requiredFields[f];
			if(fReq > 0 && fElem == " "){
				errors = 1;
				errorMsg = "<li>Please make sure that all required fields are filled.</li>";
			}
		}
		
		if(heightFt<4 || heightFt > 10){
			errorMsg += "<li>Inputted <strong>height in ft.</strong> should not be lesser than 4 ft. or greater than 10 ft.</li>";
			errors +=1;
		}
		
		if(heightIn<0 || heightIn > 11){
			errorMsg += "<li>Inputted <strong>height in in.</strong> should not be lesser than 0 in. or greater than 11 in.</li>";
			errors +=1;
		}
		
		if(bDateYear<minBirthYear || bDateYear>maxBirthYear){
			errorMsg += "<li>Inputted <strong>age</strong> should be between 17-60 years old. (See birth date)</li>";
			errors +=1;
		}
		
		if(hs === "" && hsEndYr !==""){
			errorMsg += "<li>Provide <strong>high school name</strong>.</li>";
			errors +=1;
		}
		
		if(college === "" && (collegeEndYr!=="" && collegeDegree !== "") || (collegeEndYr!=="" && collegeDegree === "") || (collegeEndYr==="" && collegeDegree !== "")){
			errorMsg += "<li>Provide <strong>college name</strong>.</li>";
			errors +=1;
		}
		
		if(vocationalSchool === "" && (vocationalEndYr!=="" && vocationalDegree !== "") || (vocationalEndYr!=="" && vocationalDegree === "") || (vocationalEndYr==="" && vocationalDegree !== "")){
			errorMsg += "<li>Provide <strong>vocational school name</strong>.</li>";
			errors +=1;
		}
		
		//check mobile number
		if(mobile!="" && mobile.length<11){
			errorMsg += "<li>Make sure that <strong>mobile number </strong>is 11 digits long.</li>";
			errors +=1;
		}
		// else{
			// var isMobileAvailable = checkAvailability(username, "mobile_num", mobile);
			// if(isMobileAvailable=="false"){
				// errorMsg += "<li>Inputted <strong>mobile number</strong> is already taken. Provide another one.</li>";
				// errors +=1;
			// }
		// }
		
		//check email address availability
		if(email !== ""){
			var isEmailAvailable = checkAvailability(username, "email", email);
			if(isEmailAvailable=="false"){
				errorMsg += "<li>Inputted <strong>email address</strong> is already taken. Provide another one.</li>";
				errors +=1;
			}
		}
	
		//check captcha code if correct
		if(captchaCode !== ""){
			var captchaResult = checkCaptchaCorrect(captchaCode);
			if(captchaResult=="false"){
				errorMsg += "<li>Inputted <strong>captcha code</strong> is incorrect. Re-enter the code.</li>";
				errors +=1;
			}
		}
		
		if(errors>0){
			document.getElementById('errorContent').innerHTML = errorMsg;
			$("#errorModal").modal('show');
		}
		else{
			if(fbStatus == "true"){
				$(this).ajaxSubmit();
				$("#fbModal").modal('show');
			}
			else{
				return true;
			}
		}
		return false;
    });
});

function checkAvailability(username, type, value){
	var hr = new XMLHttpRequest();
	var url = "verify-contact-number.php?username="+username+"&type="+type+"&value="+value;
	result = "";
	hr.open("GET", url, false);
	hr.onreadystatechange = function() {
		if(hr.readyState == 4 && hr.status == 200) {
			var return_data = hr.responseText;
			result = return_data;
		}
	}
	hr.send(null); 
	return result;
}

function checkCaptchaCorrect(captchaCode){
	var hr = new XMLHttpRequest();
	var url = "verify-captcha.php?captcha_code="+captchaCode;
	result = "";
	hr.open("GET", url, false);
	hr.onreadystatechange = function() {
		if(hr.readyState == 4 && hr.status == 200) {
			var return_data = hr.responseText;
			result = return_data;
		}
	}
	hr.send(null); 
	return result;
}

function goToHomePage() {
	window.location.href = "index.php";
}

function showLogIn(){
	window.location.href = "login.php?new=true";
}

function addThisField(inptBtn) {
		var nxtVal = parseInt(inptBtn) + 1;
		var currReqFieldName = "add" + inptBtn;
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
</script>
<script>
function enableHSStatus(){
	var hs = document.getElementById('hs').value;
	
	if(!hs.match(/^\s*$/)){
		document.getElementById('hsEduc1').disabled="";
		document.getElementById('hsEduc2').disabled="";
		document.getElementById('hsEduc3').disabled="";
	}
	else{
		document.getElementById('hsEduc1').disabled="disabled";
		document.getElementById('hsEduc2').disabled="disabled";
		document.getElementById('hsEduc3').disabled="disabled";
		$('input[name=hsEduc]').attr('checked',false);
	}
}

function enableCollegeStatus(){
	var college = document.getElementById('college').value;
	if(!college.match(/^\s*$/)){
		document.getElementById('collegeEduc1').disabled="";
		document.getElementById('collegeEduc2').disabled="";
		document.getElementById('collegeEduc3').disabled="";
	}
	else{
		document.getElementById('collegeEduc1').disabled="disabled";
		document.getElementById('collegeEduc2').disabled="disabled";
		document.getElementById('collegeEduc3').disabled="disabled";
		$('input[name=collegeEduc]').attr('checked',false);
	}
}

function enableVocSchoolStatus(){
	var vocationalSchool = document.getElementById('vocationalSchool').value;
	
	if(!vocationalSchool.match(/^\s*$/)){
		document.getElementById('vocationalEduc1').disabled="";
		document.getElementById('vocationalEduc2').disabled="";
		document.getElementById('vocationalEduc3').disabled="";
	}
	else{
		document.getElementById('vocationalEduc1').disabled="disabled";
		document.getElementById('vocationalEduc2').disabled="disabled";
		document.getElementById('vocationalEduc3').disabled="disabled";
		$('input[name=vocationalEduc]').attr('checked',false);
	}
}
</script>
<script>
function loginAccount(username){
	window.location.href="fb-verify.php?username="+username;
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

function inputOwnInterest(){
	document.getElementById('extraInterestBar').style.display = "block";
}

function changeWeight(selectedWeight){
	var weight = document.getElementById('weight');
	var newWeight = "";
	if(selectedWeight == "kgs."){
		newWeight = Math.round(weight.value/2.2046);
	}
	else{
		newWeight = Math.round(weight.value*2.2046);
	}
	weight.value = newWeight;
}
</script>
<!-- JS scripts -->
</body>
</html>