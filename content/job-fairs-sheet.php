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
	$jobFairs = explode("-", $codes);
	$jobFairsNum = count($jobFairs);
	$records = "";
	if($jobFairsNum > 1){
		$records = " Records";
	}
	else{
		$records = " Record";
	}
}

//add log activity
$addLogQuery = "INSERT INTO activity_logs(date_made, username, action) VALUES('$currDateTime', '$sessionUsername', 'EXPORT JOB FAIRS')";
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
            ->setCellValue('D2', 'JOB FAIR RECORDS:')
			->setCellValue('E2', $jobFairsNum . $records)
			->setCellValue('F2', PHPExcel_Shared_Date::PHPToExcel( gmmktime(0,0,0,date('m'),date('d'),date('Y')) ))
			->setCellValue('A4', '#')
			->setCellValue('B5', 'Date Opened')
			->setCellValue('C5', 'Status')
			->setCellValue('D4', 'JOB FAIR DETAILS')
			->setCellValue('D5', 'Job Fair Code')
            ->setCellValue('E5', 'Title')
            ->setCellValue('F5', 'Venue')
            ->setCellValue('G5', 'Street Address')
			->setCellValue('H5', 'City')
			->setCellValue('I5', 'Province')
			->setCellValue('J5', 'Number of Vacancies')
			->setCellValue('K5', 'Website Link')
			->setCellValue('L4', 'JOB FAIR DURATION')
			->setCellValue('L5', 'Scheduled Date')
			->setCellValue('M5', 'Number of Days')
			->setCellValue('N5', 'Start Time')
			->setCellValue('O5', 'End Time')
			->setCellValue('P4', 'CONTACT PERSON')
			->setCellValue('P5', 'First Name')
			->setCellValue('Q5', 'Middle Name')
			->setCellValue('R5', 'Last Name')
			->setCellValue('S5', 'Mobile Number')
			->setCellValue('T5', 'Email Address')
			->setCellValue('U5', 'Landline Number');
			
	$objPHPExcel->getActiveSheet()->getStyle('F2')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX15);
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('D4:K4');
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('L4:O4');
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('P4:U4');
	
	// Set fills
	$objPHPExcel->getActiveSheet()->getStyle('A4:U5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle('A4:U5')->getFill()->getStartColor()->setRGB('48a0f8');
	
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
	$objPHPExcel->getActiveSheet()->getStyle('C4:C5')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('D4:K4')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('D4:K5')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('L4:O4')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('L4:O5')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('P4:U4')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('P4:U5')->applyFromArray($styleThinBlackBorderOutline);
	
	// Set autofilter
	$objPHPExcel->getActiveSheet()->setAutoFilter("A5:U5");
	
	// Populate table with applicant data
	$i = 6;
	$count = 1;
	foreach($jobFairs as $jobFairCode){
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
		
		if($jobFairCode !== ""){
			$getJobFairQuery = "SELECT * FROM JOB_FAIR WHERE code='$jobFairCode'";
			$getJobFair = mysql_query($getJobFairQuery) or die(mysql_error());
			$jobFairCount = mysql_num_rows($getJobFair);

			while($jobFairRow = mysql_fetch_assoc($getJobFair)){
				$dateAdded = $jobFairRow['date_added'];
				$dateAdded = date('Y-m-d', strtotime($dateAdded));
				$status = $jobFairRow['status'];
				if($status == 0){
					$status = "Open";
				}
				else{
					$status = "Closed";
				}
				
				$title = $jobFairRow['title'];
				$title = ucwords((strtolower($title)));
				$venue = $jobFairRow['establishment_name'];
				$street = $jobFairRow['street'];
				$street = ucwords((strtolower($street)));
				$location = $jobFairRow['location_id'];
				$location = getJobLocation($location);
				$location = explode(",", $location);
				$city = $location[0];
				$province = $location[1];
				$numVacancies = $jobFairRow['num_vacancies'];
				$websiteLink = $jobFairRow['website_link'];

				$dateScheduled = $jobFairRow['date_scheduled'];
				$dateScheduled = date('M d', strtotime($dateScheduled));
				$duration = $jobFairRow['duration'];
				$openingTime = $jobFairRow['start_time'];
				$openingTime = date('g:i:a', strtotime($openingTime));
				$closingTime = $jobFairRow['end_time'];
				$closingTime = date('g:i:a', strtotime($closingTime));	

				$contactPersonFName = $jobFairRow['first_name'];
				$contactPersonFName = ucwords((strtolower($contactPersonFName)));
				$contactPersonMName = $jobFairRow['middle_name'];
				$contactPersonMName = ucwords((strtolower($contactPersonMName)));
				$contactPersonLName = $jobFairRow['last_name'];
				$contactPersonLName = ucwords((strtolower($contactPersonLName)));
				$mobile = $jobFairRow['mobile'];
				$email = $jobFairRow['email'];
				$landline = $jobFairRow['landline'];
				
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue($A, $count)
				->setCellValue($B, $dateAdded)
				->setCellValue($C, $status)
				->setCellValue($D, $jobFairCode)
				->setCellValue($E, $title)
				->setCellValue($F, $venue)
				->setCellValue($G, $street)
				->setCellValue($H, $city)
				->setCellValue($I, $province)
				->setCellValue($J, $numVacancies)
				->setCellValue($K, $websiteLink)
				->setCellValue($L, $dateScheduled)
				->setCellValue($M, $duration)
				->setCellValue($N, $openingTime)
				->setCellValue($O, $closingTime)
				->setCellValue($P, $contactPersonFName)
				->setCellValue($Q, $contactPersonMName)
				->setCellValue($R, $contactPersonLName)
				->setCellValue($S, $mobile)
				->setCellValue($T, $email)
				->setCellValue($U, $landline);
				$i+=1;
				$count+=1;
			}
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
$objPHPExcel->getActiveSheet()->getStyle("A4:U5")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A4:U5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('D2:E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

// Rename worksheet
$sheetTitle = "Available Job Posts";
$objPHPExcel->getActiveSheet()->setTitle($sheetTitle);

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Job Fair Records(JOBFAIR-ONLINE.NET).xls"');
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