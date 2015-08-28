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

//query
$getLogHistoryQuery = "SELECT * FROM activity_logs";
$getLogHistory = mysql_query($getLogHistoryQuery) or die(mysql_error());
$logCount = mysql_num_rows($getLogHistory);
$records = "";

if($logCount > 1){
	$records = " logs";
}
else{
	$records = " log";
}

//Add log activity
$addLogQuery = "INSERT INTO activity_logs(date_made, username, action) VALUES('$currDateTime', '$sessionUsername', 'EXPORT ACTIVITY LOGS')";
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
            ->setCellValue('D2', 'ACTIVITY LOG HISTORY:')
			->setCellValue('E2', $logCount . $records)
			->setCellValue('F2', PHPExcel_Shared_Date::PHPToExcel( gmmktime(0,0,0,date('m'),date('d'),date('Y')) ))
			->setCellValue('A4', '#')
			->setCellValue('B4', 'Log Date')
			->setCellValue('C4', 'Username')
            ->setCellValue('D4', 'Name')
            ->setCellValue('E4', 'Action')
            ->setCellValue('F4', 'Reference Object')
            ->setCellValue('G4', 'Status');
			
	$objPHPExcel->getActiveSheet()->getStyle('F2')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX15);

	// Set fills
	$objPHPExcel->getActiveSheet()->getStyle('A4:G4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle('A4:G4')->getFill()->getStartColor()->setRGB('48a0f8'); //blue
	
	// Set autofilter
	$objPHPExcel->getActiveSheet()->setAutoFilter("A4:G4");
	
	// Populate table with applicant data
	$i = 5;
	$count = 1;
	while($logRow = mysql_fetch_assoc($getLogHistory)){
		$A = 'A' . $i;
		$B = 'B' . $i;
		$C = 'C' . $i;
		$D = 'D' . $i;
		$E = 'E' . $i;
		$F = 'F' . $i;
		$G = 'G' . $i;

		$logID = $logRow['id'];
		$logDate = $logRow['date_made'];
		$userID = $logRow['username'];
		$accountType = getAcctInfo($userID, "status");
		$logAction = $logRow['action'];
		$referenceObj = $logRow['reference_object'];
		$logStatus = $logRow['status'];
		$logStatus = $logRow['status'];
		if($logStatus === "0"){
			$logStatus = "Failed";
		}
		else if($logStatus === "1"){
			$logStatus = "Successful";
		}
		$jobPosition = "";
		$jobLocation = "";

		//get user information
		$user = "-";
		if($accountType === "Web Admin"){
			$user = getWebAdminData($userID, "full-name");
		}
		else if($accountType === "Employer" || $accountType === "SRI Branch Manager"){
			$user = getEmployerData($userID, "company-name");
		}
		else if($accountType === "Employee"){
			$user = getEmployeeData($userID, "full-name");
		}

		//get reference object translation
		if($referenceObj != "" || $referenceObj != NULL){
			if(strpos($logAction, "JOB POST") !== false){
				$jobPosition = getJobPost($referenceObj, "job-pos-name");
				$referenceObj = $referenceObj . " ($jobPosition)";
			}
			else if(strpos($logAction, "SEARCH JOB") !== false){
				$extractedReferenceObj = explode("-", $referenceObj);
				if($extractedReferenceObj[0] != ""){
					$jobLocation = getJobLocation($extractedReferenceObj[0]);
				}
				if($extractedReferenceObj[1] != ""){
					$jobPosition = getJobPositionName($extractedReferenceObj[1]);
				}

				$referenceObj = $referenceObj . " ($jobLocation - $jobPosition)";
			}
		}

		//fill row data
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue($A, $count)
			->setCellValue($B, $logDate)
			->setCellValue($C, $userID)
			->setCellValue($D, $user)
			->setCellValue($E, $logAction)
			->setCellValue($F, $referenceObj)
			->setCellValue($G, $logStatus);
		$i+=1;
		$count+=1;

	}
	
// Set column widths
$toCol++;
$fromCol = 'B';
$toCol = 'H';
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
$objPHPExcel->getActiveSheet()->getStyle("A4:G4")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A4:G4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('D2:E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

// Rename worksheet
$sheetTitle = "Activity Logs";
$objPHPExcel->getActiveSheet()->setTitle($sheetTitle);

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Activity Log History(JOBFAIR-ONLINE.NET).xls"');
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