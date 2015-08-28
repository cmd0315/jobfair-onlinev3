<?php
include './includes/db.php';
include './includes/functions.php';

session_start();
if(isset($_SESSION['SRIUsername']) && $_SESSION['SRIUsername'] != ""){
	$username = $_SESSION['SRIUsername'];
}
else{
	header("Location: index.php");
}
$applicant = $_SESSION['SRIEResumeId'];

date_default_timezone_set('Singapore');
$currDateTime = date("Y-m-d H:i:s");

$content = "";

//add log activity
$logAction = "VIEW RESUME";
$refObj = "";
if($username == $applicant){
	$logAction = "VIEW OWN RESUME";
}
else{
	$refObj = $applicant;
}
$addLogQuery = "INSERT INTO activity_logs(date_made, username, action, reference_object) VALUES('$currDateTime', '$username', '$logAction', '$refObj')";
$addLog = mysql_query($addLogQuery) OR die(mysql_error());

//get applicant nfo
$applicantFName = getEmployeeData($applicant, "first-name");
$applicantMName = getEmployeeData($applicant, "middle-name");
$applicantLName = getEmployeeData($applicant, "last-name");
$applicantName = $applicantLName . ", " . $applicantFName . " " . $applicantMName;
$applicantPicSrc = getEmployeeData($applicant, "profile_pic");
if($applicantPicSrc === Null || !(file_exists($applicantPicSrc))){
	$applicantPicSrc = "./img/id.png";
}
$applicantSex = getEmployeeData($applicant, "sex");
if($applicantSex === "M"){
	$applicantSex = "Male";
}
else{
	$applicantSex = "Female";
}

$applicantBirthDate= getEmployeeData($applicant, "birth_date");
$applicantBirthDate= date('F d Y', strtotime($applicantBirthDate));
$applicantBirthPlace= getEmployeeData($applicant, "birth_place");
$applicantAge = getEmployeeData($applicant, "age") . " yrs. old";
$applicantCivilStatus = getEmployeeData($applicant, "civil_status");
$applicantNumChildren = getEmployeeData($applicant, "num_children");

$applicantHSEduc = strtoupper(getEmployeeData($applicant, "hs_educ"));
$applicantHSName = getEmployeeData($applicant, "hs_name");
if($applicantHSName == ""){
	$applicantHSName = "-";
}
$applicantHSEducStartYear = getEmployeeData($applicant, "hs_start_yr");
$applicantHSEducEndYear = getEmployeeData($applicant, "hs_end_yr");
if($applicantHSName != "-"){
	if($applicantHSEducEndYear == ""){
		$applicantHSEducEndYear = "-";
	}
	$applicantHSEducSY = $applicantHSEducEndYear; 
}
else{
	$applicantHSEducSY = "-";
}

$applicantCollegeEduc = strtoupper(getEmployeeData($applicant, "college_educ"));
$applicantCollegeName = getEmployeeData($applicant, "college_name");
$applicantCollegeDegree = strtoupper(getEmployeeData($applicant, "college_degree"));
if($applicantCollegeName == ""){
	$applicantCollegeName = "-";
}
if($applicantCollegeDegree == ""){
	$applicantCollegeDegree = "-";
}
$applicantCollegeEducStartYear = getEmployeeData($applicant, "college_start_yr");
$applicantCollegeEducEndYear = getEmployeeData($applicant, "college_end_yr");
if($applicantCollegeName != "-"){
	if($applicantCollegeEducEndYear == ""){
		$applicantCollegeEducEndYear = "-";
	}
	
	$applicantCollegeEducSY = $applicantHSEducEndYear; 
}
else{
	$applicantCollegeEducSY = "-";
}

$applicantVocEduc = strtoupper(getEmployeeData($applicant, "voc_educ"));
$applicantVocSchoolName = getEmployeeData($applicant, "voc_school_name");
$applicantVocCourse = strtoupper(getEmployeeData($applicant, "voc_course"));
if($applicantVocSchoolName == ""){
	$applicantVocSchoolName = "-";
}
if($applicantVocCourse == ""){
	$applicantVocCourse = "-";
}
$applicantVocEducStartYear = getEmployeeData($applicant, "voc_start_yr");
$applicantVocEducEndYear = getEmployeeData($applicant, "voc_end_yr");
if($applicantVocSchoolName != "-"){
	if($applicantVocEducEndYear == ""){
		$applicantVocEducEndYear = "-";
	}
	
	$applicantVocEducSY = $applicantVocEducEndYear; 
}
else{
	$applicantVocEducSY = "-";
}

$applicantCityAddress= getEmployeeData($applicant, "city1");
$applicantProvAddress= getEmployeeData($applicant, "province1");
$applicantCompleteAddress= getEmployeeData($applicant, "complete_address"); 
$applicantHeight = getEmployeeData($applicant, "height");
$applicantWeight = getEmployeeData($applicant, "weight") . " lbs.";
$applicantReligion = getEmployeeData($applicant, "religion");
$applicantMobile = getEmployeeData($applicant, "mobile");
if($applicantMobile == ""){
	$applicantMobile = "-";
}
else{
	$applicantMobile = substr($applicantMobile, 0, -7) . "-" . substr($applicantMobile, 4, -4) . "-" . substr($applicantMobile, 7);
}
$applicantEmail = getEmployeeData($applicant, "email");
if($applicantEmail == ""){
	$applicantEmail = "-";
}
$applicantLandline = getEmployeeData($applicant, "landline");
if($applicantLandline == ""){
	$applicantLandline = "-";
}
else{
	$applicantLandline = substr($applicantLandline, 0, -4) . "-" . substr($applicantLandline, 3, -2) . "-" . substr($applicantLandline, 5);
}
$highestEduc = "";
if($applicantCollegeEduc === ""){
	if($applicantVocEduc !== ""){
		$highestEduc = "Vocational Course" . " " .  $applicantVocEduc;
	}
	else
		$highestEduc = "High School" . " " .  $applicantHSEduc;
}
else
	$highestEduc = "College" . " " . $applicantCollegeEduc;
//GET EMPLOYEE INTERESTS
$employeeInterestQuery = "SELECT * FROM interest WHERE employee_username='$applicant' ";
//GET WORK HISTORY
$employeeWorkHistoryQuery = "SELECT * FROM work_history WHERE employee_username='$applicant'";
$getEmployeeWorkHistory = mysql_query($employeeWorkHistoryQuery) or die(mysql_error());

/** START CREATION OF PDF FILE**/
require('includes/fpdf.php');

class PDF extends FPDF
{
	private $PG_W = 190;

	// Page header
	function Header(){
		// Page header
		global $title;
		$this->AddFont('Proxima Nova','','proximanova-regular-webfont.php');
		$this->SetFont('Proxima Nova','',15);
	}

	// Page footer
	function Footer(){
		// Position at 1.5 cm from bottom
		$this->SetY(-15);
		$this->date = $currDateTime;
		// Arial italic 8
		$this->AddFont('Proxima Nova','','proximanova-regular-webfont.php');
		$this->SetFont('Proxima Nova','',8);
		$copyright = chr(169);
		// Page number
		$this->Cell(0,10, $copyright . ' JobFair-Online.Net. - Page '.$this->PageNo().'/{nb}',0,0,'C');
	}

	function TableTitle($label){
		// Title
	   $this->SetTextColor(0,0,0);
		$this->SetFont('Proxima Nova','',10);
		$this->SetFillColor(236,236,236);
		$this->Cell(0,6,"  $label",0,1,'L',true);
		$this->Ln(4);
		// Save ordinate
		$this->y0 = $this->GetY();
	}

	function TableCaption($caption){
		$this->SetFont('Proxima Nova','',10);
		$this->SetTextColor(128,128,128);
		$this->Cell($this->PG_W / 6, 5, "$caption", 0, 0, 'R');
	}
	
	function TableData($data){
		$this->SetFont('Proxima Nova','',10);
		$this->SetTextColor(0,0,0);
		$this->Cell($this->PG_W / 6, 5, "$data", 0, 0, 'L');
	}
	
	function TableEntry($caption, $data){
		$this->TableCaption($caption);
		$this->TableData($data);
	}
}

// Instanciation of inherited class
$pdf = new PDF('P','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetTextColor(8,157,255);
$pdf->AddFont('Proxima Nova','','proximanova-regular-webfont.php');
$pdf->SetFont('Proxima Nova','',18);
$pdf->Cell(0,10,"$applicantName",0,1);
$pdf->Image($applicantPicSrc,160,6,30);
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Proxima Nova','',10);
$pdf->Cell(0,2,"$applicantAge".", $applicantSex",0,1);
$pdf->Cell(0,8,"$applicantCompleteAddress",0,1);
$pdf->Ln(20);
$pdf->TableTitle("BASIC INFORMATION");
$pdf->TableEntry("Birthdate:", "$applicantBirthDate");
$pdf->TableEntry("Birthplace:", "$applicantBirthPlace");
$pdf->Ln();
$pdf->TableEntry("Sex:", "$applicantSex");
if($applicantHeight != ""){
	$pdf->TableEntry("Height:", "$applicantHeight");
}
if($applicantWeight != ""){
	$pdf->TableEntry("Weight:", "$applicantWeight");
}
$pdf->Ln();
$pdf->TableEntry("Civil Status:", "$applicantCivilStatus");
$pdf->TableEntry("No. of Children:", "$applicantNumChildren");
$pdf->TableEntry("Religion:", "$applicantReligion");
$pdf->Ln();
if($applicantMobile != "-"){
	$pdf->TableEntry("Mobile No:", "$applicantMobile");
}
if($applicantLandline != "-"){
	$pdf->TableEntry("Landline:", "$applicantLandline");
	$pdf->Ln();
}
if($applicantEmail != "-"){
	$pdf->TableEntry("Email:", "$applicantEmail");
}
$pdf->Ln(10);
$pdf->TableTitle("EDUCATIONAL BACKGROUND");
$pdf->TableEntry("High School:", "$applicantHSName");
$pdf->TableEntry("", "");
$pdf->TableEntry("School Year End:", "$applicantHSEducSY");
$pdf->Ln();
$pdf->TableEntry("", "$applicantHSEduc");
$pdf->Ln(8);
if($applicantCollegeName != "-"){
	$pdf->TableEntry("College:", "$applicantCollegeName");
	$pdf->TableEntry("", "");
	$pdf->TableEntry("School Year End:", "$applicantCollegeEducSY");
	$pdf->Ln();
	$pdf->TableEntry("", "$applicantCollegeDegree");
	$pdf->Ln();
	$pdf->TableEntry("", "$applicantCollegeEduc");
	$pdf->Ln(8);
}

if($applicantVocSchoolName != "-"){
	$pdf->TableEntry("Vocational School:", "$applicantVocSchoolName");
	$pdf->TableEntry("", "");
	$pdf->TableEntry("School Year End:", "$applicantVocEducSY");
	$pdf->Ln();
	$pdf->TableEntry("", "$applicantVocCourse");
	$pdf->Ln();
	$pdf->TableEntry("", "$applicantVocEduc");
	$pdf->Ln(10);
}
$pdf->TableTitle("WORK HISTORY");
while($employeeWorkHistory = mysql_fetch_assoc($getEmployeeWorkHistory)){
	$i += 1;
	$companyName = strtoupper($employeeWorkHistory['company_name']);
	if($companyName == "")
		$companyName = "-";
	$position = $employeeWorkHistory['position'];
	if($position == "")
		$position = "-";
	$workStart= $employeeWorkHistory['work_start'];
	if($workStart == ""){
		$workStart = "-";
	}
	else
		$workStart= date('F d Y', strtotime($workStart));
	$workEnd = $employeeWorkHistory['work_end'];
	if($workEnd == ""){
		$workEnd = "-";
	}
	else
		$workEnd= date('F d Y', strtotime($workEnd));
	$workDurationS = "$workStart -";
	$workDurationE = "$workEnd";
	
	//print
	$pdf->TableEntry("$i.", "$companyName");
	$pdf->TableEntry("", "");
	$pdf->TableEntry("Duration:", "$workDurationS");
	$pdf->Ln();
	$pdf->SetFont('Proxima Nova','',8);
	$pdf->TableEntry("", "$position");
	$pdf->TableEntry("", "");
	$pdf->TableEntry("", "$workDurationE");
	$pdf->Ln();
}
$filename = $applicantName . "(JOBFAIR-ONLINE.NET).pdf";
$pdf->Output($filename, 'I');
/** END CREATION OF PDF FILE**/
?>

<!DOCTYPE html>
<html lang="en">
<head>
<!-- START META -->
<?php echo $meta=getMeta(); ?>
<!-- END META -->
<link rel="stylesheet" href="./css/bootstrap.css"/>
<link rel="stylesheet" href="./css/fpdf.css"/>
</head>
<body></body>
<script src="./js/jquery.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
</html>