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
include './includes/excel/PHPExcel.php';
include './includes/db.php';

//EXTRACT GET VARIABLES
extract($_GET);

$jobPositionIDs = explode("-", $jobPositionIDs);
$jobPositionsNum = count($jobPositionIDs)-1;
$records = "";
if($jobPositionsNum > 1){
	$records = " Records";
}
else{
	$records = " Record";
}

//add log activity
$addLogQuery = "INSERT INTO activity_logs(date_made, username, action) VALUES('$currDateTime', '$sessionUsername', 'EXPORT JOB POSITIONS')";
$addLog = mysql_query($addLogQuery) or die(mysql_error());

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
            ->setCellValue('C2', 'JOB POST RECORDS:')
			->setCellValue('D2', $jobPositionsNum . $records)
			->setCellValue('E2', PHPExcel_Shared_Date::PHPToExcel( gmmktime(0,0,0,date('m'),date('d'),date('Y')) ))
			->setCellValue('A4', '#')
			->setCellValue('B4', 'Date Registered')
            ->setCellValue('C4', 'Job Position Title');
			
			
	$objPHPExcel->getActiveSheet()->getStyle('E2')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX15);
	
	// Set fills
	$objPHPExcel->getActiveSheet()->getStyle('A4:C4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle('A4:C4')->getFill()->getStartColor()->setRGB('48a0f8');
	
	// Set autofilter
	$objPHPExcel->getActiveSheet()->setAutoFilter("A4:C4");
	
	// Populate table with applicant data
	$i = 5;
	$count = 1;
	foreach($jobPositionIDs as $jobPositionID){
		$A = 'A' . $i;
		$B = 'B' . $i;
		$C = 'C' . $i;
		if($jobPositionID !== ""){
			$dateAdded = getJobPositionDateRegistered($jobPositionID);
			$jobPosition = getJobPositionName($jobPositionID);
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue($A, $count)
			->setCellValue($B, $dateAdded)
			->setCellValue($C, $jobPosition);
			$i+=1;
			$count+=1;
			
		}
	}

// Set column widths
$toCol++;
$fromCol = 'B';
$toCol = 'D';
for($i = $fromCol; $i !== $toCol; $i++) {
	$objPHPExcel->getActiveSheet()->getColumnDimension($i)->setAutoSize(true);
}

// Set fonts
$objPHPExcel->getActiveSheet()->getStyle("B2:E2")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setName('Candara');
$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setSize(20);
$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->getColor()->setRGB('089DFF');
$objPHPExcel->getActiveSheet()->getStyle("H2")->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C2:E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('D2')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
$objPHPExcel->getActiveSheet()->getStyle('A4:C4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle("A4:C4")->getFont()->setBold(true);

// Rename worksheet
$sheetTitle = "Job Positions";
$objPHPExcel->getActiveSheet()->setTitle($sheetTitle);

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Job Positions Sheet(JOBFAIR-ONLINE.NET).xls"');
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
