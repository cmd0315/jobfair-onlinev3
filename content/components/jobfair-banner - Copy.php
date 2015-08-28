<?php

function generateBanner(){
	date_default_timezone_set('Singapore');
	$currDate = date("Y-m-d");
	$currDateTime = date("Y-m-d H:i:s");
	$currentYear = date('Y');

	$getJobFairQuery = "SELECT * FROM JOB_FAIR WHERE status='0'";
	//AND date_scheduled <= '$currDateTime'
	$getJobFair = mysql_query($getJobFairQuery) or die(mysql_error());
	$jobFairCount = mysql_num_rows($getJobFair);

	$content = "";
	$content .= "<div class=\"row-fluid\">
					<div class=\"span10 offset1\" style=\"background-color:white;\">
						<div class=\"row-fluid jobFairBanner\">
							<div class=\"span12 well sliderWell\">
								<div id=\"headerCarousel\" class=\"carousel fade\">
								  <!-- Carousel items -->
								  <div class=\"carousel-inner\">
									<div class=\"active item\">
										<div class=\"row-fluid\">
											<!-- START SLIDER CAPTION -->
											<div class=\"span12\">
												<center><h1>Job Fair $currentYear</h1></center> <!--input title here-->
											</div>
										<!-- END SLIDER CAPTION -->
										</div>
									</div>";

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

			$moreInformation = "";
			$numVacancies = $jobFairRow['num_vacancies'];
			if($numVacancies > 10 || !empty($numVacancies)){
				$moreInformation .= "With <strong>$numVacancies</strong> positions available. ";
			}			
			$websiteLink = $jobFairRow['website_link'];
			if(!empty($websiteLink)){
				$moreInformation .= "For more information, click <a href=\"$websiteLink\" target=\"_blank\" style=\"font-weight:bold;\">here</a>.";
			}

			$contactPerson = $jobFairRow['first_name'] . " " . $jobFairRow['middle_name'] . " " . $jobFairRow['last_name'];
			$contactPerson = ucwords((strtolower($contactPerson)));
			$mobile = $jobFairRow['mobile'];
			$email = $jobFairRow['email'];
			$landline = $jobFairRow['landline'];
			$status = $jobFairRow['status'];

			//start slide
			$content .= "<div class=\"item\"> 
							<div class=\"row-fluid\">
								<!-- START SLIDER CAPTION -->";
			$content .= "		<div class=\"span5 offset1\">
									<center>
										<h2>\"$title\"</h2>
										<p class=\"quote-caption\">$venue &#149; $schedule &#149; $openingTime-$closingTime</p>
										<p class=\"text-info\">$moreInformation</p>
									</center>
								</div>
								<div class=\"span5 offset1\">
									<blockquote>
										<p>$address</p>
										<p>$schedule ($openingTime - $closingTime)</p>
										<p>Look for: $contactPerson</p>
										<ul class=\"inline contactList\">
										  <li>$mobile</li>
										  <li>$email</li>
										  <li>$landline</li>
										</ul>
									</blockquote>
								</div>";
			
			//end slide
			$content .= "		<!-- END SLIDER CAPTION -->
							</div>
						</div>";
			
			$counter += 1;
		}
	}

	$content .= "				</div>
							</div>
						</div>
					</div>
				</div>";
	return $content;
}
?>