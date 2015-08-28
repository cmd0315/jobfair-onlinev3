<?php
//GET SESSION VARIABLLES
session_start();
if(isset($_SESSION['SRIUsername']) && $_SESSION['SRIUsername'] != ""){
	$sessionUsername = $_SESSION['SRIUsername'];
}
else{
	header("Location: index.php");
}

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
date_default_timezone_set('Singapore');
$currDateTime = date("Y-m-d H:i:s");

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');
	

/** Include PHPExcel */
require_once './includes/excel/PHPExcel.php';
require './includes/db.php';

//EXTRACT GET VARIABLES
extract($_GET);

if(isset($codes)){
	$jobPosts = explode("-", $codes);
	$jobPostsNum = count($jobPosts);
	$records = "";
	if($jobPostsNum > 1){
		$records = " Records";
	}
	else{
		$records = " Record";
	}
}

//add log activity
$addLogQuery = "INSERT INTO activity_logs(date_made, username, action) VALUES('$currDateTime', '$sessionUsername', 'EXPORT JOB POSTS')";
$addLog = mysql_query($addLogQuery) or die(mysql_error());

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("JobFair-Online.Net 2013")
							 ->setLastModifiedBy("JobFair-Online.Net", '&B')
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Employer Contact Sheet")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Job Post Records");


// Add HEADER
$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('B2', 'JOBFAIR-ONLINE.NET')
            ->setCellValue('D2', 'JOB POST RECORDS:')
			->setCellValue('E2', $jobPostsNum . $records)
			->setCellValue('F2', PHPExcel_Shared_Date::PHPToExcel( gmmktime(0,0,0,date('m'),date('d'),date('Y')) ))
			->setCellValue('A4', '#')
			->setCellValue('B5', 'Date Posted')
			->setCellValue('C4', 'JOB SPECIFICATIONS')
			->setCellValue('C5', 'Job Post Code')
            ->setCellValue('D5', 'Job Position')
            ->setCellValue('E5', 'Company Name')
            ->setCellValue('F5', 'Street Address')
			->setCellValue('G5', 'City')
			->setCellValue('H5', 'Province')
			->setCellValue('I5', 'Number of Vacancies')
			->setCellValue('J5', 'Job Description')
			->setCellValue('K4', 'JOB REQUIREMENTS')
			->setCellValue('K5', 'Sex')
			->setCellValue('L5', 'Civil Status')
			->setCellValue('M5', 'Minimum Age')
			->setCellValue('N5', 'Maximum Age')
			->setCellValue('O5', 'Height')
			->setCellValue('P5', 'Weight')
			->setCellValue('Q5', 'Educational Attainment')
			->setCellValue('R5', 'Other Requirements')
			->setCellValue('S4', 'JOB EXPIRY')
			->setCellValue('S5', 'Opening Date')
			->setCellValue('T5', 'Expiration Date')
			->setCellValue('U4', 'JOB STATUS')
			->setCellValue('U5', 'Status1')
			->setCellValue('V5', 'Status2');
			
	$objPHPExcel->getActiveSheet()->getStyle('F2')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX15);
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C4:J4');
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('K4:R4');
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('S4:T4');
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('U4:V4');
	
	// Set fills
	$objPHPExcel->getActiveSheet()->getStyle('A4:V5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle('A4:V5')->getFill()->getStartColor()->setRGB('48a0f8');
	
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
	$objPHPExcel->getActiveSheet()->getStyle('C4:J4')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('C4:J5')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('K4:R4')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('K4:R5')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('S4:T4')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('S4:T5')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('U4:V4')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('U4:V5')->applyFromArray($styleThinBlackBorderOutline);
	
	// Set autofilter
	$objPHPExcel->getActiveSheet()->setAutoFilter("A5:V5");
	
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
			->setCellValue($J, $jobDesc)
			->setCellValue($K, $eSex)
			->setCellValue($L, $eCivilStatus)
			->setCellValue($M, $eMinAge)
			->setCellValue($N, $eMaxAge)
			->setCellValue($O, $eHeight)
			->setCellValue($P, $eWeight)
			->setCellValue($Q, $eEducAttainment)
			->setCellValue($R, $otherRequirements)
			->setCellValue($S, $jobOpenDate)
			->setCellValue($T, $jobCloseDate)
			->setCellValue($U, $status1)
			->setCellValue($V, $status2);
			$i+=1;
			$count+=1;
		}
	}
	
// Set column widths
$toCol++;
$fromCol = 'B';
$toCol = 'W';
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
$objPHPExcel->getActiveSheet()->getStyle("A4:V5")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A4:V5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('D2:E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

// Rename worksheet
$sheetTitle = "Available Job Posts";
$objPHPExcel->getActiveSheet()->setTitle($sheetTitle);

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Job Post Records(JOBFAIR-ONLINE.NET).xls"');
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