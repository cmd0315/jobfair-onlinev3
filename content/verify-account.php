<?php
include './includes/functions.php';
  /** START FOR DB **/
include './includes/connect.inc.php';
$link_id = db_connect($dbname);
if(!$link_id) error_message(sql_error());
/** END FOR DB**/

//get currentdate
date_default_timezone_set('Singapore');
$currDate = date("Y-m-d");
$currDateTime = date("Y-m-d H:i:s");
  
 //extract form element values
extract($_POST);

/* CLEAN INPUTS */
$firstName = trim(mysql_real_escape_string($firstName));
$middleName = trim(mysql_real_escape_string($middleName));
$lastName = trim(mysql_real_escape_string($lastName));
$street1 = trim(mysql_real_escape_string($street1));
$mobile1 = trim(mysql_real_escape_string($mobile1));
$mobile2 = trim(mysql_real_escape_string($mobile2));
$mobile3 = trim(mysql_real_escape_string($mobile3));
$mobile = $mobile1. $mobile2. $mobile3;
$email = trim($email);
$landline1 = trim(mysql_real_escape_string($landline1));
$landline2 = trim(mysql_real_escape_string($landline2));
$landline3 = trim(mysql_real_escape_string($landline3));
$landline = $landline1 . $landline2 . $landline3;

$area1 = explode(",", $area1);
$city1 = trim($area1[0]);
$province1 = trim($area1[1]);


if($acctType == 2){//for employee
	$birthPlace = trim(mysql_real_escape_string($birthPlace));
	$weight = trim(mysql_real_escape_string($weight));
	$numChildren = trim(mysql_real_escape_string($numChildren));
	$hs = trim(mysql_real_escape_string($hs));
	$college =  trim(mysql_real_escape_string($college));
	$collegeDegree =  trim(mysql_real_escape_string($collegeDegree));
	$vocationalSchool =  trim(mysql_real_escape_string($vocationalSchool));
	$vocationalDegree =  trim(mysql_real_escape_string($vocationalDegree));
}
else if($acctType == 1){//for employer
	$companyName =  trim(mysql_real_escape_string($companyName));
	$companyDesc =  trim(mysql_real_escape_string($companyDesc));
	$cpPosition =  trim(mysql_real_escape_string($cpPosition));
	$cpDept =  trim(mysql_real_escape_string($cpDept));
}
 
 //FORMAT PROFILE PICTURE PATH
if($status == "Employee"){
	$acctType = 2;
	$path = "uploads/profile_pictures/";
 }
 else if($status == "Employer"){
	$acctType = 1;
	$path = "uploads/company_logos/";
 }
 else
	$acctType = 0;

//FORMAT HEIGHT OPTIONS
if($heightFt != ""){
 $totalHeight = $heightFt . "'" . $heightIn . '"';
 $totalHeight = mysql_real_escape_string($totalHeight);
}
else{
	$totalHeight = "";
}

//FORMAT WEIGHT OPTIONS
define('POUND_CONVERSION', '2.2046'); // constant for lbs to kg. conversion
if($weightType === "kgs."){
	$weight = $weight*POUND_CONVERSION;
}

 
 //FORMAT WITH HONORS OPTION
if($hsWithHonors == ""){
	$hsWithHonors = 0;
}

if($collegeWithHonors == ""){
	$collegeWithHonors = 0;
}

if($vocationalWithHonors == ""){
	$vocationalWithHonors = 0;
}


/*------------------------------------- START DATABASE MANIPULATION -----------------------------------------------*/
	//ADD ROW TO ACCOUNT TABLE - no verification
	$accountDataQuery = "INSERT INTO account(username, password, acct_type, status, date_joined) VALUES('$username', '$password', '$acctType', '1', '$currDateTime')";
	$accountTypeTitle = "";
	
	/*START ADD ROW TO EMPLOYEE/EMPLOYER TABLE */
	if($acctType == 2){
	 //add row to employee table
		$userDataQuery = "INSERT INTO employee(username, first_name, middle_name, last_name, street1, city1, province1, birth_date, birth_place, sex, height, weight, civil_status, num_children, 
		religion, mobile_num, email, tel_num, hs_name, hs_end_yr, hs_educ, college_name, college_degree, college_end_yr, college_educ, voc_school_name, voc_course, voc_end_yr, voc_educ) VALUES ('$username', '$firstName', '$middleName', '$lastName', '$street1', '$city1', '$province1', '$birthDate', '$birthPlace', '$sex', '$totalHeight', '$weight', '$civilStatus', '$numChildren', '$religion', '$mobile', '$email', '$landline', '$hs', '$hsEndYr', '$hsEduc', '$college', '$collegeDegree', '$collegeEndYr', '$collegeEduc', '$vocationalSchool', '$vocationalDegree', '$vocationalEndYr', '$vocationalEduc')"; 
		$accountTypeTitle = "Employee";
	}
	 else if($acctType == 1){
		 //add row to employer table
		$userDataQuery = "INSERT INTO employer(username, company_name, company_desc, street1, city1, province1, first_name, middle_name, last_name, mobile_num, email, tel_num, position, department) VALUES ('$username', '$companyName', '$companyDesc', '$street1', '$city1', '$province1', '$firstName', '$middleName', '$lastName', '$mobile', '$email', '$landline', '$cpPosition', '$cpDept')"; 
		$accountTypeTitle = "Employer";
	 }

	//check if exists
	$checkAccountExistsQuery = "SELECT * FROM account WHERE username='$username' ";
	$checkAccountExists = mysql_query($checkAccountExistsQuery) or die(mysql_error());
	$accountExistingRowNum = mysql_result($checkAccountExists, 0);
	
	$checkEmployeeExistsQuery = "SELECT * FROM employee WHERE username='$username' ";
	$checkEmployeeExists = mysql_query($checkEmployeeExistsQuery) or die(mysql_error());
	$employeeExistingRowNum = mysql_result($checkEmployeeExists, 0);
	
	$checkEmployerExistsQuery = "SELECT * FROM employer WHERE username='$username' ";
	$checkEmployerExists = mysql_query($checkEmployerExistsQuery) or die(mysql_error());
	$employerExistingRowNum = mysql_result($checkEmployerExists, 0);
	
	if($accountExistingRowNum == 0 && ($employeeExistingRowNum == 0 || $employerExistingRowNum == 0) ){
		$insertAccountData = mysql_query($accountDataQuery); //INSERT DATA TO ACCOUNT TABLE
		$insertUserData = mysql_query($userDataQuery) or die(mysql_error()); //INSERT DATA INTO RESPECTIVE DATABASE TABLE
		
		if($fbStatus === "true"){
			$verifyFBAcctQuery = "UPDATE account SET status='1' WHERE username='$username' ";
			$verifyFBAcct = mysql_query($verifyFBAcctQuery) or die(mysql_error());
		}
		
		$_SESSION['SRIUPassword']=""; //so back loading wont work
		/* END ADD ROW TO EMPLOYEE/EMPLOYER TABLE*/
		
		//for PROFILE PIC Upload
		$valid_formats = array("jpg", "JPG", "png", "PNG", "gif", "GIF", "bmp", "BMP", "jpeg", "JPEG");
		if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
			$name = $_FILES['photoimg']['name'];
			$size = $_FILES['photoimg']['size'];
			
			// if fb upload
			if($name == "" || $size == 0){
				$name = $_FILES['profPic']['name'];
				$size = $_FILES['profPic']['size'];
			}
			
			if(strlen($name)) {
				list($txt, $ext) = explode(".", $name);
				if(in_array($ext,$valid_formats)) {
					// Image size max 5 MB
					if($size<(5024*5024)) {
						$actual_image_name = time()."-".$username.".".$ext;
						$tmp = $_FILES['photoimg']['tmp_name'];
						if(move_uploaded_file($tmp, $path.$actual_image_name)) {
							if($acctType == 2){ //if employee
								$updatePicQuery = "UPDATE employee SET profile_pic='$actual_image_name' WHERE username='$username' ";
							}
							else if($acctType == 1){ //if employer
								$updatePicQuery = "UPDATE employer SET profile_pic='$actual_image_name' WHERE username='$username' ";
							}
							$updatePIc = mysql_query($updatePicQuery);
							//echo "<img src='uploads/profile_pictures/".$actual_image_name."' class='preview' id='prev-picture' name='prev-picture' style='border:1px dashed gray; width:105px; height:104px; margin-right:5px;'>";
						}
						else
							$errorMsg = "failed";
					}
					else
						$errorMsg =  "Image file size max 5 MB"; 
				}
				else
					$errorMsg =  "Invalid file format.."; 
			}		
			else
				$errorMsg =  "Please select image..!";
		}
	
		if($acctType == 2){
			//for WORK HISTORY TABLE OPERATIONS
			for ($i=1; $i<50; $i++) {
				$coName = $_POST['coName' . $i];
				$position = $_POST['position' . $i];
				$startDM = $_POST['startMonth' . $i];
				$endDM = $_POST['endMonth' . $i];
				
				$startDM = explode("-", $startDM);
				$startYear = $startDM[0];
				$startMonth = $startDM[1];
				
				$endDM = explode("-", $endDM);
				$endYear = $endDM[0];
				$endMonth = $endDM[1];
				
				$startDM = date("Y-m-d", mktime(0, 0, 0, $startMonth, 1, $startYear));
				$endDM = date("Y-m-d", mktime(0, 0, 0, $endMonth, 1, $endYear));

				if($coName != "" && $position !="" && $startMonth != "" && $endMonth != "") {
					$workHistoryQuery = "INSERT INTO work_history(employee_username, company_name, position, work_start, work_end, date_added) VALUES('$username', '$coName', '$position', '$startDM', '$endDM', '$currDateTime')";
					$insertWorkHistory = mysql_query($workHistoryQuery) or die(mysql_error());
				}
			}

			//FOR INTEREST table
			foreach ($_POST['classification'] as $classifications) {
				$interestQuery = "INSERT INTO interest(employee_username, position_id, date_added) VALUES('$username', '$classifications', '$currDateTime')";
				$insertInterest = mysql_query($interestQuery) or die(mysql_error());
			}
			
			//FOR EXTRA INTEREST table
			foreach ($_POST['extraInterest'] as $extraInterest) {
				$extraInterest = explode(",", $extraInterest);
				foreach($extraInterest as $eI){
					$checkInterestExistsQuery = "SELECT * FROM interest WHERE position_id='$eI' ";
					$checkInterestExists = mysql_query($checkInterestExistsQuery) or die(mysql_error());
					$existingInterestRowNum = mysql_num_rows($checkInterestExists);
					if($existingInterestRowNum === 0){
						$extraInterestQuery = "INSERT INTO interest(employee_username, position_id, status, date_added) VALUES('$username', '$eI', '1', '$currDateTime')";
						$insertExtraInterest = mysql_query($extraInterestQuery) or die(mysql_error());
					}
				}
			}
		}
	}
mysql_close($link_id);
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
	<?php echo $navbar=getGeneralNavBar(); ?>
	<!-- END NAVBAR -->
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span3 offset6"> 
				<div class="row-fluid">
					<h5 class="pull-right"><a href="./index.php"><i class="icon-circle-arrow-left"></i> Back to Home Page</a></h5>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span6 well offset3"> 
				<h4 class="content-heading">Profile Created!</h4>
						<div class="row-fluid">
							<div class="span7"> 
								<div class="row-fluid">
									Thank you for registering to People-Link.Asia! You my now start using your account.
								</div>
							</div>
							<div class="span5">
								<div class="row-fluid">
									<div class="span12" style="text-align:center; border: 1px solid gray; background-color: beige;">
										<h5>Username:</h5>
										<p style="font-weight:bold;"><?php echo $username;?></p>
										<h5>Account Type:</h5> 
										<p style="font-weight:bold;"><?php echo $accountTypeTitle;?></p>
									</div>
								</div>
							</div>
					</div>
					<!-- hidden variable -->
					<Input type="hidden" name="username" id="username" value="<?php echo $username;?>">
					<Input type="hidden" name="mobile" id="mobile" value="<?php echo $mobile;?>">
					<Input type="hidden" name="email" id="email" value="<?php echo $email;?>">
			</div>
		</div>
		<div class="row-fluid">
			<div class="span2 offset7">
				<button type="submit" class="btn btn-primary job-post-add span12" id="logiInBtn" name="logiInBtn" onclick="loginAcct();">Login</button>
			</div>
		</div>
	</div>
	<div class="push"></div>
</div>
<!-- START FOOTER -->
<?php echo $footer=getFooter(); ?>
<!-- END FOOTER -->
<!-- JS scripts -->
<script src="./js/jquery.min.js"></script>
<script src="./js/jqBootstrapValidation.js"></script>
<script src="./js/jquery.form.js"></script>
<!-- Validate plugin -->
<script src="./js/jquery.validate.min.js"></script>
<script>
$(function () { $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); } );
	
function loginAcct(){
	var username = document.getElementById('username').value;
	window.location.href="login-acct.php?username="+username;
}
</script>
<script src="./js/bootstrap.min.js"></script>
</body>
</html>