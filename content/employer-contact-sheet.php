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

if(isset($jobPositions)){
	$jobPositions= explode(",", $jobPositions);
	$jobPositionsCount = count($jobPositions);
	$jobPositionTitle = "";
	$jobPositionsList = "";
	if($jobPositionsCount >0){
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
	}
	$addLogQuery = "INSERT INTO activity_logs(date_made, username, action, reference_object) VALUES('$currDateTime', '$sessionUsername', 'EXPORT EMPLOYERS MULTIPLE POSITIONS', '$jPL')";
}
else{
	$addLogQuery = "INSERT INTO activity_logs(date_made, username, action, reference_object) VALUES('$currDateTime', '$sessionUsername', 'EXPORT EMPLOYERS MULTIPLE POSITIONS', 'ALL JOB POSITIONS')";
}

//add log activity
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
							 ->setCategory("Contact Sheet");


// Add HEADER
$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('B2', 'JOBFAIR-ONLINE.NET')
            ->setCellValue('D2', 'EMPLOYER CONTACT RECORDS:')
			->setCellValue('E2', $usernamesNum . $records)
			->setCellValue('F2', PHPExcel_Shared_Date::PHPToExcel( gmmktime(0,0,0,date('m'),date('d'),date('Y')) ))
			->setCellValue('H2', $jobPositionTitle)
			->setCellValue('I2', $jPL)
			->setCellValue('A5', '#')
			->setCellValue('B5', 'Date Registered')
			->setCellValue('C4', 'COMPANY INFORMATION')
			->setCellValue('C5', 'Username')
            ->setCellValue('D5', 'Company Name')
            ->setCellValue('E5', 'Company Desc')
            ->setCellValue('F5', 'Street')
			->setCellValue('G5', 'City')
			->setCellValue('H5', 'Province')
			->setCellValue('I4', 'CONTACT PERSON')
			->setCellValue('I5', 'Last Name')
			->setCellValue('J5', 'First Name')
			->setCellValue('K5', 'Middle Name')
			->setCellValue('L5', 'Position')
			->setCellValue('M5', 'Department')
			->setCellValue('N5', 'Mobile Num')
			->setCellValue('O5', 'Email Address')
			->setCellValue('P5', 'Landline Num');
			
	$objPHPExcel->getActiveSheet()->getStyle('F2')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX15);
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C4:H4');
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('I4:O4');
	
	// Set fills
	$objPHPExcel->getActiveSheet()->getStyle('A4:P5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle('A4:P5')->getFill()->getStartColor()->setRGB('48a0f8');
	
	// Set autofilter
	$objPHPExcel->getActiveSheet()->setAutoFilter("A5:P5");
	
	//Set table border
	$styleThinBlackBorderOutline = array(
		'borders' => array(
			'outline' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array('argb' => '00000000'),
			),
		),
	);
	$objPHPExcel->getActiveSheet()->getStyle('A4:A4')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('B4:B4')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('C4:H4')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('C4:H5')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('I4:P4')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('I4:P5')->applyFromArray($styleThinBlackBorderOutline);

	// Populate table with applicant data
	$i = 6;
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
		
		if($username !== ""){
			$dateRegistered = getAcctInfo($username, "date-joined");
			$dateRegistered = date('Y-m-d', strtotime($dateRegistered));
			$companyName = getEmployerData($username, "company-name");
			$companyDesc = getEmployerData($username, "company_desc");
			$street = getEmployerData($username, "street1");
			$city = getEmployerData($username, "city1");
			$province = getEmployerData($username, "province1");
			$last_name = getEmployerData($username, "last_name");
			$first_name = getEmployerData($username, "first_name");
			$middle_name = getEmployerData($username, "middle_name");
			$position = getEmployerData($username, "position");
			$department = getEmployerData($username, "department");
			$mobile= getEmployerData($username, "mobile");
			$mobile = substr($mobile, 0, -7) . "-" . substr($mobile, 4, -4) . "-" . substr($mobile, 7);
			$email = getEmployerData($username, "email");
			$landline = getEmployerData($username, "landline");
			$landline = substr($landline, 0, -4) . "-" . substr($landline, 3, -2) . "-" . substr($landline, 5);
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue($A, $count)
			->setCellValue($B, $dateRegistered)
			->setCellValue($C, $username)
			->setCellValue($D, $companyName)
			->setCellValue($E, $companyDesc)
			->setCellValue($F, $street)
			->setCellValue($G, $city)
			->setCellValue($H, $province)
			->setCellValue($I, $last_name)
			->setCellValue($J, $first_name)
			->setCellValue($K, $middle_name)
			->setCellValue($L, $position)
			->setCellValue($M, $department)
			->setCellValue($N, $mobile)
			->setCellValue($O, $email)
			->setCellValue($P, $landline);
			$i+=1;
			$count+=1;
			
		}
	}
	
// Set column widths
$toCol++;
$fromCol = 'B';
$toCol = 'Q';
for($i = $fromCol; $i !== $toCol; $i++) {
	$objPHPExcel->getActiveSheet()->getColumnDimension($i)->setAutoSize(true);
}

// Set fonts
$objPHPExcel->getActiveSheet()->getStyle("B2")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setName('Candara');
$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setSize(20);
$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->getColor()->setRGB('089DFF');
$objPHPExcel->getActiveSheet()->getStyle("D2:E2")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle("H2")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('E2')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
$objPHPExcel->getActiveSheet()->getStyle('A4:P5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("A4:P5")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('D2:H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

// Rename worksheet
$sheetTitle = "Employers";
$objPHPExcel->getActiveSheet()->setTitle($sheetTitle);

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Employer Contact Sheet(JOBFAIR-ONLINE.NET).xls"');
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