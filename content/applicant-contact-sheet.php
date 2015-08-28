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

if(isset($usernames)){
	$usernames = explode("-", $usernames);
	$usernamesNum = count($usernames);
	$records = "";
	if($usernamesNum > 1){
		$records = " Records";
	}
	else{
		$records = " Record";
	}
}

$o=0;
if(isset($jobPositions)){
	$jobPositions = explode(",", $jobPositions);
	$jobPositionsCount = sizeof($jobPositions);
	$jobPositionTitle = "";
	$jobPositionsList = "";
	if($jobPositionsCount >= 1){
		$counter = 0;
		$jobPositionTitle = "JOB POSITIONS:";
		$jobPositionsList = "";
		foreach($jobPositions as $jP){
			$counter += 1;
			if($jP != ""){
				$jP = getJobPositionName($jP);
				if($counter < $jobPositionsCount){
					$jPL .= $jP . ", ";
				}
				else{
					$jPL .= $jP;
				}
			}
		}
		$addLogQuery = "INSERT INTO activity_logs(date_made, username, action, reference_object) VALUES('$currDateTime', '$sessionUsername', 'EXPORT APPLICANTS MULTIPLE POSITIONS', '$jPL')";
	}
}
else if(isset($jobPostCode)){
	$jobPositionTitle = "JOB POSITIONS:";
	$jP = getJobPost($jobPostCode, 'job-pos-name');
	$jPL = $jP;
	$addLogQuery = "INSERT INTO activity_logs(date_made, username, action, reference_object) VALUES('$currDateTime', '$sessionUsername', 'EXPORT APPLICANTS', '$jPL')";
}
else{
	if($o==0){
		$addLogQuery = "INSERT INTO activity_logs(date_made, username, action, reference_object) VALUES('$currDateTime', '$sessionUsername', 'EXPORT APPLICANTS MULTIPLE POSITIONS', 'ALL JOB POSITIONS')";
	}
	$o+=1;
}

//add log activity
$addLog = mysql_query($addLogQuery) OR die(mysql_error());

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("JobFair-Online.Net 2013")
							 ->setLastModifiedBy("JobFair-Online.Net", '&B')
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Applicant Contact Sheet")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Contact Sheet");

// Add HEADER
$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('B2', 'JOBFAIR-ONLINE.NET')
            ->setCellValue('E2', 'APPLICANT CONTACT RECORDS:')
			->setCellValue('F2', $usernamesNum . $records)
			->setCellValue('G2', PHPExcel_Shared_Date::PHPToExcel( gmmktime(0,0,0,date('m'),date('d'),date('Y')) ))
			->setCellValue('H2', $jobPositionTitle)
			->setCellValue('I2', $jPL)
			->setCellValue('A4', '#')
			->setCellValue('B4', 'Date Registered')
            ->setCellValue('C4', 'Last Name')
			->setCellValue('D4', 'First Name')
			->setCellValue('E4', 'Middle Name')
			->setCellValue('F4', 'Street Address')
			->setCellValue('G4', 'City')
			->setCellValue('H4', 'Province')
            ->setCellValue('I4', 'Age')
            ->setCellValue('J4', 'Sex')
			->setCellValue('K4', 'Civil Status')
			->setCellValue('L4', 'Number of Children')
			->setCellValue('M4', 'Height')
			->setCellValue('N4', 'Weight')
			->setCellValue('O4', 'Religion')
			->setCellValue('P4', 'Mobile Num')
			->setCellValue('Q4', 'Email Address')
			->setCellValue('R4', 'Landline Num')
			->setCellValue('S4', 'Educational Attainment')
			->setCellValue('T4', 'High School Name')
			->setCellValue('U4', 'High School End Year')
			->setCellValue('V4', 'College Name')
			->setCellValue('W4', 'College Degree')
			->setCellValue('X4', 'College End Year')
			->setCellValue('Y4', 'Vocational School Name')
			->setCellValue('Z4', 'Vocational School Degree')
			->setCellValue('AA4', 'Vocational School End Year')
			->setCellValue('AB4', 'Working Experience 1')
			->setCellValue('AC4', 'Position')
			->setCellValue('AD4', 'Duration')
			->setCellValue('AE4', 'Working Experience 2')
			->setCellValue('AF4', 'Position')
			->setCellValue('AG4', 'Duration')
			->setCellValue('AH4', 'Working Experience 3')
			->setCellValue('AI4', 'Position')
			->setCellValue('AJ4', 'Duration');
			
			
	$objPHPExcel->getActiveSheet()->getStyle('G2')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX15);
	
	// Set fills
	$objPHPExcel->getActiveSheet()->getStyle('A4:AJ4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle('A4:AJ4')->getFill()->getStartColor()->setRGB('48a0f8');
	
	// Set autofilter
	$objPHPExcel->getActiveSheet()->setAutoFilter("A4:AJ4");
	
	// Populate table with applicant data
	$i = 5;
	$count = 1;
	foreach($usernames as $username){
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
		$X = 'X' . $i;
		$Y = 'Y' . $i;
		$Z = 'Z' . $i;
		$AA = 'AA' . $i;
		$AB = 'AB' . $i;
		$AC = 'AC' . $i;
		$AD = 'AD' . $i;
		$AE = 'AE' . $i;
		$AF = 'AF' . $i;
		$AG = 'AG' . $i;
		$AH = 'AH' . $i;
		$AI = 'AI' . $i;
		$AJ = 'AJ' . $i;
		if($username !== ""){
			$dateRegistered = getAcctInfo($username, "date-joined");
			$dateRegistered = date('Y-m-d', strtotime($dateRegistered));
			$lastName = getEmployeeData($username, "last-name");
			$firstName = getEmployeeData($username, "first-name");
			$middleName = getEmployeeData($username, "middle-name");
			$street = getEmployeeData($username, "street1");
			$city = getEmployeeData($username, "city1");
			$province = getEmployeeData($username, "province1");
			$age = getEmployeeData($username, "age");
			$sex = getEmployeeData($username, "sex");
			$civilStatus = getEmployeeData($username, "civil_status");
			$numChildren = getEmployeeData($username, "numChildren");
			$height = getEmployeeData($username, "height");
			$weight = getEmployeeData($username, "weight");
			$religion = getEmployeeData($username, "religion");
			$mobile = getEmployeeData($username, "mobile");
			$mobile = substr($mobile, 0, -7) . "-" . substr($mobile, 4, -4) . "-" . substr($mobile, 7);
			$email = getEmployeeData($username, "email");
			$landline = getEmployeeData($username, "landline");
			$landline = substr($landline, 0, -4) . "-" . substr($landline, 3, -2) . "-" . substr($landline, 5);
			$applicantHSEduc = getEmployeeData($username, "hs_educ");
			$applicantCollegeEduc = getEmployeeData($username, "college_educ");
			$applicantVocEduc = getEmployeeData($username, "voc_educ");
			$highestEduc = "";
			if($applicantCollegeEduc === ""){
				if($applicantVocEduc !== ""){
					$highestEduc = "Vocational School" . " " .  $applicantVocEduc;
				}
				else
					$highestEduc = "High School" . " " .  $applicantHSEduc;
			}
			else
				$highestEduc = "College" . " " . $applicantCollegeEduc;
			$hSName = getEmployeeData($username, "hs_name");
			$hSEndYr = getEmployeeData($username, "hs_end_yr");
			$collegeName = getEmployeeData($username, "college_name");
			$collegeDegree = getEmployeeData($username, "college_degree");
			$collegeEndYr = getEmployeeData($username, "college_end_yr");
			$vocSchoolName = getEmployeeData($username, "voc_school_name");
			$vocSchoolCourse = getEmployeeData($username, "voc_course");
			$vocSchoolEndYr = getEmployeeData($username, "voc_end_yr");
			$workExperience1 = getWorkHistory($username, "work_experience1");
			$workExperience2 = getWorkHistory($username, "work_experience2");
			$workExperience3 = getWorkHistory($username, "work_experience3");
			$whJobPosition1 = getWorkHistory($username, "position1");
			$whJobPosition2 = getWorkHistory($username, "position2");
			$whJobPosition3 = getWorkHistory($username, "position3");
			$whDuration1 = getWorkHistory($username, "work_duration1");
			$whDuration2 = getWorkHistory($username, "work_duration2");
			$whDuration3 = getWorkHistory($username, "work_duration3");
			
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue($A, $count)
			->setCellValue($B, $dateRegistered)
			->setCellValue($C, $lastName)
			->setCellValue($D, $firstName)
			->setCellValue($E, $middleName)
			->setCellValue($F, $street)
			->setCellValue($G, $city)
			->setCellValue($H, $province)
			->setCellValue($I, $age)
			->setCellValue($J, $sex)
			->setCellValue($K, $civilStatus)
			->setCellValue($L, $num_hildren)
			->setCellValue($M, $height)
			->setCellValue($N, $weight)
			->setCellValue($O, $religion)
			->setCellValue($P, $mobile)
			->setCellValue($Q, $email)
			->setCellValue($R, $landline)
			->setCellValue($S, $highestEduc)
			->setCellValue($T, $hSName)
			->setCellValue($U, $hSEndYr)
			->setCellValue($V, $collegeName)
			->setCellValue($W, $collegeDegree)
			->setCellValue($X, $collegeEndYr)
			->setCellValue($Y, $vocSchoolName)
			->setCellValue($Z, $vocSchoolCourse)
			->setCellValue($AA, $vocSchoolEndYr)
			->setCellValue($AB, $workExperience1)
			->setCellValue($AC, $whJobPosition1)
			->setCellValue($AD, $whDuration1)
			->setCellValue($AE, $workExperience2)
			->setCellValue($AF, $whJobPosition2)
			->setCellValue($AG, $whDuration2)
			->setCellValue($AH, $workExperience3)
			->setCellValue($AI, $whJobPosition3)
			->setCellValue($AJ, $whDuration3);
			$i+=1;
			$count+=1;
			
		}
	}
	
// Set column widths
$toCol++;
$fromCol = 'B';
$toCol = 'AK';
for($i = $fromCol; $i !== $toCol; $i++) {
	$objPHPExcel->getActiveSheet()->getColumnDimension($i)->setAutoSize(true);
}

// Set fonts
$objPHPExcel->getActiveSheet()->getStyle("B2")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setName('Candara');
$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setSize(20);
$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->getColor()->setRGB('089DFF');
$objPHPExcel->getActiveSheet()->getStyle("E2:F2")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("H2")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('E2:H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('F2')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
$objPHPExcel->getActiveSheet()->getStyle('A4:AJ4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("A4:AJ4")->getFont()->setBold(true);

// Rename worksheet
$sheetTitle = "Applicants";
$objPHPExcel->getActiveSheet()->setTitle($sheetTitle);

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Applicant Contact Sheet(JOBFAIR-ONLINE.NET).xls"');
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
?>