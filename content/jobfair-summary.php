<?php
require './includes/db.php';

$jobFairCode= $_GET['jobFairCode'];
$content = "";

date_default_timezone_set('Singapore');
$currDate = date("Y-m-d");
$currDateTime = date("Y-m-d H:i:s");
$currentYear = date('Y');

$getJobFairQuery = "SELECT * FROM job_fair WHERE code='$jobFairCode'";
//AND date_scheduled <= '$currDateTime'
$getJobFair = mysql_query($getJobFairQuery) or die(mysql_error());
$jobFairCount = mysql_num_rows($getJobFair);


if($jobFairCount > 0){
	$counter = 0;
	while($jobFairRow = mysql_fetch_assoc($getJobFair)){
		$jobFairCode = $jobFairRow['code'];
	
		$title = $jobFairRow['title'];
		$title = ucwords((strtolower($title)));
		
		$venue = $jobFairRow['establishment_name'];
		$street = $jobFairRow['street'];
		$street = ucwords((strtolower($street)));
		$location = $jobFairRow['location_id'];
		$address = $street . ", " . getJobLocation($location);
		
		$dateAdded = $jobFairRow['date_added'];
		$dateAdded = date('Y-m-d', strtotime($dateAdded));
		$dateScheduled = $jobFairRow['date_scheduled'];
		$dateScheduled = date('M d', strtotime($dateScheduled));
		$duration = $jobFairRow['duration'];
		if($duration > 1){
			$dateEndSchedule = date('M d', strtotime('+'. $duration . 'days', strtotime($dateScheduled)));
			$schedule = $dateScheduled . "-" . $dateEndSchedule;
		}
		else{
			$schedule = $dateScheduled;
		}

		$openingTime = $jobFairRow['start_time'];
		$openingTime = date('g:i:a', strtotime($openingTime));
		$closingTime = $jobFairRow['end_time'];
		$closingTime = date('g:i:a', strtotime($closingTime));

		$numVacancies = $jobFairRow['num_vacancies'];
		if($numVacancies > 1){
			$numVacancies = "$numVacancies slots";
		}
		else{
			$numVacancies = "$numVacancies slot";
		}			

		$websiteLink = $jobFairRow['website_link'];

		$contactPerson = $jobFairRow['first_name'] . " " . $jobFairRow['middle_name'] . " " . $jobFairRow['last_name'];
		$contactPerson = ucwords((strtolower($contactPerson)));
		$mobile = $jobFairRow['mobile'];
		$email = $jobFairRow['email'];
		$landline = $jobFairRow['landline'];
		$status = $jobFairRow['status'];
		if($status == 0){
			$status = "<span class=\"text-success\">Open</span>";
		}
		else{
			$status = "<span class=\"text-error\">Closed</span>";
		}

		$content .="<div class=\"modal-header\">
								<h4 class=\"content-heading3\">Job Fair Summary</h4>
							</div>
							<div class=\"modal-body\">
								<div class=\"row-fluid\">
									<div class=\"span8\">
										<p>Title: <span style=\"font-weight:bold; color:#089DFF;\">$title</span></p>
										<p>Venue: <span style=\"font-weight:bold; color:#089DFF;\">$venue</span></p>
										<p>Address: <span style=\"font-weight:bold; color:#089DFF;\">$address</span></p>
										<p>Date Scheduled: <span style=\"font-weight:bold; color:#089DFF;\">$schedule</span></p>
										<p>Vacancies: <span style=\"font-weight:bold; color:#089DFF;\">$numVacancies</span></p>
										<p>Contact Person: <span style=\"font-weight:bold; color:#089DFF;\">$contactPerson</span></p>
											<p>Contact Information:</p>
											<ul>";

											if($websiteLink != ""){
												$content.= "<li><a href=\"$websiteLink\">Website Link</a></li>";
											}

											if($mobile != ""){
												$content.= "<li>Mobile: $mobile</li>";
											}

											if($email != ""){
												$content.= "<li>Email: $email</li>";
											}

											if($landline != ""){
												$content.= "<li>Landline: $landline</li>";
											}

		$content .="				</ul>
									</div>
									<div class=\"span3 offset1\">
										<p>Status: $status</p>
										<p>Date Opened: $dateAdded</p>
									</div>";

		$content.="	</div>
							</div>
							<div class=\"modal-footer\">
								<div class=\"row-fluid\">
									<div class=\"span6 offset3\">
										<button class=\"btn btn-primary span6\" data-dismiss=\"modal\">OK</button>
										<button class=\"btn btn-primary span6\" onclick=\"editJobFair('$jobFairCode');\">EDIT</button>
									</div>
							</div>";
		echo $content;
	}
}
?>