<?php
//GET SESSION VARIABLLES
session_start();
if(isset($_SESSION['SRIUsername']) && $_SESSION['SRIUsername'] != ""){
	$sessionUsername = $_SESSION['SRIUsername'];
}
else{
	header("Location: index.php");
}

//get currentdate
date_default_timezone_set('Singapore');
$currDateTime = date("Y-m-d H:i:s");

/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2013 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2013 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.7.9, 2013-06-02
 */

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');
	

/** Include PHPExcel */
require_once './includes/excel/PHPExcel.php';
require './includes/db.php';

//EXTRACT GET VARIABLES
extract($_GET);

$jobPosts = explode("-", $codes);
$jobPostsNum = count($jobPosts)-1;
$records = "";
if($jobPostsNum > 1){
	$records = " Records";
}
else{
	$records = " Record";
}

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("JobFair-Online.Net 2013")
							 ->setLastModifiedBy("JobFair-Online.Net", '&B')
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Employer Job Post Sheet")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Job Post Records");

/*------------START JOB POST SHEET-----------------*/
// Add HEADER
$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('B2', 'JOBFAIR-ONLINE.NET')
            ->setCellValue('D2', 'EMPLOYER JOB POST RECORDS:')
			->setCellValue('E2', $jobPostsNum . $records)
			->setCellValue('F2', PHPExcel_Shared_Date::PHPToExcel( gmmktime(0,0,0,date('m'),date('d'),date('Y')) ))
			->setCellValue('A5', '#')
            ->setCellValue('B5', 'Date Posted')
			->setCellValue('C4', 'JOB SPECIFICATIONS')
			->setCellValue('C5', 'Job Post Code')
            ->setCellValue('D5', 'Job Position')
            ->setCellValue('E5', 'Company Name')
			->setCellValue('F5', 'Street Address')
			->setCellValue('G5', 'City')
			->setCellValue('H5', 'Province')
			->setCellValue('I5', 'Number of Vacancies')
			->setCellValue('J5', 'Number of Applicants')
			->setCellValue('K5', 'Job Description')
			->setCellValue('L4', 'JOB REQUIREMENTS')
			->setCellValue('L5', 'Sex')
			->setCellValue('M5', 'Civil Status')
			->setCellValue('N5', 'Minimum Age')
			->setCellValue('O5', 'Maximum Age')
			->setCellValue('P5', 'Height')
			->setCellValue('Q5', 'Weight')
			->setCellValue('R5', 'Educational Attainment')
			->setCellValue('S5', 'Other Requirements')
			->setCellValue('T4', 'JOB EXPIRY')
			->setCellValue('T5', 'Opening Date')
			->setCellValue('U5', 'Expiration Date')
			->setCellValue('V4', 'JOB STATUS')
			->setCellValue('V5', 'Status1')
			->setCellValue('W5', 'Status2');
			
	$objPHPExcel->getActiveSheet()->getStyle('F2')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX15);
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C4:K4');
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('L4:S4');
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('T4:U4');
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('V4:W4');
	
	// Set fills
	$objPHPExcel->getActiveSheet()->getStyle('A4:W5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle('A4:W5')->getFill()->getStartColor()->setRGB('48a0f8');
	
	//Set table border
	$styleThinBlackBorderOutline = array(
		'borders' => array(
			'outline' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array('argb' => '00000000'),
			),
		),
	);
	$objPHPExcel->getActiveSheet()->getStyle('A4:A5')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('B4:B5')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('C4:K4')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('C4:K5')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('L4:S4')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('L4:S5')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('T4:U4')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('T4:U5')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('V4:W4')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('V4:W5')->applyFromArray($styleThinBlackBorderOutline);
	
	// Set autofilter
	$objPHPExcel->getActiveSheet()->setAutoFilter("A5:W5");
	
	// Populate table with applicant data
	$i = 6;
	$count = 1;
	foreach($jobPosts as $jobPostCode){
		$A = 'A' . $i;
		$B = 'B' . $i;
		$C = 'C' . $i;
		$D = 'D' . $i;
		$E = 'E' . $i;
		$F = 'F' . $i;
			$G = 'G' . $i;
		$H = 'H' . $i;
		$I = 'I' . $i;
		$J = 'J' . $i;
		$K = 'K' . $i;
		$L = 'L' . $i;
		$M = 'M' . $i;
		$N = 'N' . $i;
		$O = 'O' . $i;
		$P = 'P' . $i;
		$Q = 'Q' . $i;
		$R = 'R' . $i;
		$S = 'S' . $i;
		$T = 'T' . $i;
		$U = 'U' . $i;
		$V = 'V' . $i;
		$W = 'W' . $i;
		
		if($jobPostCode !== ""){
			$datePosted = getJobPost($jobPostCode, "date-posted");
			$datePosted = date('Y-m-d', strtotime($datePosted));
			$companyName = getJobPost($jobPostCode, "company-name");
			//$jobPos = getJobPost($jobPostCode, "job-pos");
			$jobPos = getJobPost($jobPostCode, "job-pos-name");
			$street = getJobPost($jobPostCode, "street");
			$jobLocation = getJobPost($jobPostCode, "location");
			$jobLocation = explode(",", $jobLocation);
			$city = $jobLocation[0];
			$province = $jobLocation[1];
			$numVacancies = getJobPost($jobPostCode, "num-vacancies");
			$getApplicantsQuery = "SELECT * FROM application_history WHERE job_code='$jobPostCode' ";
			$getApplicants = mysql_query($getApplicantsQuery) or die(mysql_error());
			$countApplicants = mysql_num_rows($getApplicants);
			
			$jobDesc = getJobPost($jobPostCode, "job-desc");
			$eSex = getJobPost($jobPostCode, "e-sex");
			$eCivilStatus = getJobPost($jobPostCode, "e-civil-stat");
			$eMinAge = getJobPost($jobPostCode, "e-min-age");
			$eMaxAge = getJobPost($jobPostCode, "e-max-age");
			$eHeight = getJobPost($jobPostCode, "e-height");
			$eWeight = getJobPost($jobPostCode, "e-weight");
			$eEducAttainment = getJobPost($jobPostCode, "e-educ-attainment");
			$otherRequirements = getJobRequirements($jobPostCode);
			$jobOpenDate = getJobPost($jobPostCode, "job-open-date");
			$jobCloseDate = getJobPost($jobPostCode, "job-close-date");
			$status1 = getJobPost($jobPostCode, "status1");
			$status2 = getJobPost($jobPostCode, "status2");
			if($status1 == 1){
				$status1 = "CLOSED";
			}
			else
				$status1 = "LISTED";
				
			if($status2 == 1){
				$status2 = "REMOVED";
			}
			else
				$status2 = "EXISTING";
			
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue($A, $count)
			->setCellValue($B, $datePosted)
			->setCellValue($C, $jobPostCode)
			->setCellValue($D, $jobPos)
			->setCellValue($E, $companyName)	
			->setCellValue($F, $street)
			->setCellValue($G, $city)
			->setCellValue($H, $province)
			->setCellValue($I, $numVacancies)
			->setCellValue($J, $countApplicants)
			->setCellValue($K, $jobDesc)
			->setCellValue($L, $eSex)
			->setCellValue($M, $eCivilStatus)
			->setCellValue($N, $eMinAge)
			->setCellValue($O, $eMaxAge)
			->setCellValue($P, $eHeight)
			->setCellValue($Q, $eWeight)
			->setCellValue($R, $eEducAttainment)
			->setCellValue($S, $otherRequirements)
			->setCellValue($T, $jobOpenDate)
			->setCellValue($U, $jobCloseDate)
			->setCellValue($V, $status1)
			->setCellValue($W, $status2);
			$i+=1;
			$count+=1;
		}
	}
	
// Set column widths
$toCol++;
$fromCol = 'B';
$toCol = 'X';
for($i = $fromCol; $i !== $toCol; $i++) {
	$objPHPExcel->getActiveSheet()->getColumnDimension($i)->setAutoSize(true);
}

// Set fonts		
$objPHPExcel->getActiveSheet()->getStyle("B2")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setName('Candara');
$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setSize(20);
$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->getColor()->setRGB('089DFF');
$objPHPExcel->getActiveSheet()->getStyle("D2:E2")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('E2')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
$objPHPExcel->getActiveSheet()->getStyle("A4:W5")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A4:W5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('D2:E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

// Rename worksheet
$sheetTitle = "Available Job Posts";
$objPHPExcel->getActiveSheet()->setTitle($sheetTitle);

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
/*------------END JOB POST SHEET-----------------*/

/*------------START APPLICANTS SHEET-----------------*/
// Create a new worksheet, after the default sheet
$objPHPExcel->createSheet();

	
// Add HEADER
$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('B2', 'JOBFAIR-ONLINE.NET')
            ->setCellValue('G2', 'JOB APPLICATION RECORDS:')
			->setCellValue('I2', PHPExcel_Shared_Date::PHPToExcel( gmmktime(0,0,0,date('m'),date('d'),date('Y')) ))
			->setCellValue('A5', '#')
			->setCellValue('B5', 'Date Applied')
			->setCellValue('C4', 'EMPLOYEE INFORMATION')
			->setCellValue('C5', 'Job Code')
            ->setCellValue('D5', 'Job Position')
			->setCellValue('E5', 'Company Name')
			->setCellValue('F4', 'EMPLOYEE INFORMATION')
			->setCellValue('F5', 'Last Name')
			->setCellValue('G5', 'First Name')
			->setCellValue('H5', 'Middle Name')
            ->setCellValue('I5', 'Sex')
			->setCellValue('J5', 'Age')
			->setCellValue('K5', 'Street')
			->setCellValue('L5', 'City')
			->setCellValue('M5', 'Province')
			->setCellValue('N5', 'Mobile Number')
			->setCellValue('O5', 'Email Address')
			->setCellValue('P5', 'Landline Number');
			
$objPHPExcel->getActiveSheet()->getStyle('I2')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX15);
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('C4:E4');
$objPHPExcel->setActiveSheetIndex(1)->mergeCells('F4:O4');

//Set table border
$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => '00000000'),
		),
	),
);
$objPHPExcel->getActiveSheet()->getStyle('A4:A5')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('B4:B5')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('C4:E4')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('C4:E5')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('F4:P4')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('F4:P5')->applyFromArray($styleThinBlackBorderOutline);

$j = 6;
$count2 = 1;
$employeeUsername = "";
//populate table with application records
foreach($jobPosts as $jobPostCode){
	$jobApplicationQuery = "SELECT * FROM application_history WHERE job_code='$jobPostCode'";
	$getJobApplication = mysql_query($jobApplicationQuery) or die(mysql_error());
	$getJobApplication = mysql_query($jobApplicationQuery) or die(mysql_error());
	while($jobApplication = mysql_fetch_assoc($getJobApplication)){
		$A = 'A' . $j;
		$B = 'B' . $j;
		$C = 'C' . $j;
		$D = 'D' . $j;
		$E = 'E' . $j;
		$F = 'F' . $j;
		$G = 'G' . $j;
		$H = 'H' . $j;
		$I = 'I' . $j;
		$J = 'J' . $j;
		$K = 'K' . $j;
		$L = 'L' . $j;
		$M = 'M' . $j;
		$N = 'N' . $j;
		$O = 'O' . $j;
		$P = 'P' . $j;
		
		$dateApplied = $jobApplication['date_applied'];
		$dateApplied = date('Y-m-d', strtotime($dateApplied));
		//$jobPos = getJobPost($jobPostCode, "job-pos");
		$jobPos = getJobPost($jobPostCode, "job-pos-name");
		$employeeUsername = $jobApplication['employee_username'];
		$companyName = getJobPost($jobPostCode, "company-name");
		$lastName = getEmployeeData($employeeUsername, "last-name");
		$firstName = getEmployeeData($employeeUsername, "first-name");
		$middleName = getEmployeeData($employeeUsername, "middle-name");
		$street = getEmployeeData($employeeUsername, "street");
		$city = getEmployeeData($employeeUsername, "city1");
		$province = getEmployeeData($employeeUsername, "province1");
		$address = getEmployeeData($employeeUsername, "address");
		$sex = getEmployeeData($employeeUsername, "sex");
		$age = getEmployeeData($employeeUsername, "age");
		$mobile = getEmployeeData($employeeUsername, "mobile");
		$mobile = substr($mobile, 0, -7) . "-" . substr($mobile, 4, -4) . "-" . substr($mobile, 7);
		$email = getEmployeeData($employeeUsername, "email");
		$landline = getEmployeeData($employeeUsername, "landline");
		$landline = substr($landline, 0, -4) . "-" . substr($landline, 3, -2) . "-" . substr($landline, 5);
		
		$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue($A, $count2)
			->setCellValue($B, $dateApplied)
			->setCellValue($C, $jobPostCode)
			->setCellValue($D, $jobPos)
			->setCellValue($E, $companyName)
			->setCellValue($F, $lastName)
			->setCellValue($G, $firstName)
			->setCellValue($H, $middleName)
			->setCellValue($I, $sex)
			->setCellValue($J, $age)
			->setCellValue($K, $street)
			->setCellValue($L, $city)
			->setCellValue($M, $province)
			->setCellValue($N, $mobile)
			->setCellValue($O, $email)
			->setCellValue($P, $landline);
		$j+=1;
		$count2 +=1;
	}
}
$applicationsNum = $count2 - 1;
$records2 = "";
if($applicationsNum > 1){
	$records2 = " Records";
}
else{
	$records2 = " Record";
}

$objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue('H2', $applicationsNum . $records2);
	
// Set column widths
$toCol++;
$fromCol = 'B';
$toCol = 'Q';
for($i = $fromCol; $i !== $toCol; $i++) {
	$objPHPExcel->getActiveSheet()->getColumnDimension($i)->setAutoSize(true);
}

/// Set fills
$objPHPExcel->getActiveSheet()->getStyle('A4:P5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A4:P5')->getFill()->getStartColor()->setRGB('48a0f8');
// Set autofilter
$objPHPExcel->getActiveSheet()->setAutoFilter("A5:P5");
	
// Set fonts
$objPHPExcel->getActiveSheet()->getStyle("B2")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setName('Candara');
$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setSize(20);
$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->getColor()->setRGB('089DFF');
$objPHPExcel->getActiveSheet()->getStyle("G2:I2")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('H2')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
$objPHPExcel->getActiveSheet()->getStyle("A4:P5")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A4:P5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('G2:I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


// Rename second worksheet
$objPHPExcel->getActiveSheet()->setTitle('List of Applicants');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
/*------------END APPLICANTS SHEET-----------------*/

//add log activity
$addLogQuery = "INSERT INTO activity_logs(date_made, username, action) VALUES('$currDateTime', '$sessionUsername', 'EXPORT JOB POST')";
$addLog = mysql_query($addLogQuery) OR die(mysql_error());

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Employer Job Application Records(JOBFAIR-ONLINE.NET).xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 2030 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

exit;