<?php

function generateBanner(){
	date_default_timezone_set('Singapore');
	$currDate = date("Y-m-d");
	$currDateTime = date("Y-m-d H:i:s");
	$currentYear = date('Y');

	$getJobFairQuery = "SELECT * FROM job_fair WHERE status='0'";
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
				//start slide
				$content .= "<div class=\"item\"> 
							<div class=\"row-fluid\">
								<!-- START SLIDER CAPTION -->
								<div class=\"span12\">
									<center>
										<h2>Manila Mega Job Fair</h2>
										<p class=\"quote-caption\">March 26 &#149; 8:00 am-5:00 pm</p>
										<p class=\"text-info\">San Andres Sports Complex, Malate, Manila</p>
									</center>
								</div>
							</div>
						</div><!-- END SLIDER CAPTION -->";
				$content .= "<div class=\"item\"> 
							<div class=\"row-fluid\">
								<!-- START SLIDER CAPTION -->
								<div class=\"span12\">
									<center>
										<h2>Nurturing Tomorrow's Noblest Through A Relevant Career Directions</h2>
										<p class=\"quote-caption\">March 26 &#149; 8:30 am-3:30 pm</p>
										<p class=\"text-info\"> Function Hall, University of Rizal System, Morong, Rizal</p>
									</center>
								</div>
							</div>
						</div><!-- END SLIDER CAPTION -->";
						$content .= "<div class=\"item\"> 
							<div class=\"row-fluid\">
								<!-- START SLIDER CAPTION -->
								<div class=\"span12\">
									<center>
										<h2>PESO GMA</h2>
										<p class=\"quote-caption\">March 6 &#149; 8:00 am-5:00 pm</p>
										<p class=\"text-info\">GMA , Cavite Sports Complex</p>
									</center>
								</div>
							</div>
						</div><!-- END SLIDER CAPTION -->";
					$content .= "<div class=\"item\"> 
							<div class=\"row-fluid\">
								<!-- START SLIDER CAPTION -->
								<div class=\"span12\">
									<center>
										<h2>CAREER/JOB FAIR 2014</h2>
										<p class=\"quote-caption\">March 7 &#149; 8:00 am-5:00 pm</p>
										<p class=\"text-info\">University of Perpetual Help System - GMA Campus</p>
									</center>
								</div>
							</div>
						</div><!-- END SLIDER CAPTION -->";	
				$content .= "<div class=\"item\"> 
							<div class=\"row-fluid\">
								<!-- START SLIDER CAPTION -->
								<div class=\"span12\">
									<center>
										<h2>PESO Calamba</h2>
										<p class=\"quote-caption\">March 19 &#149; 8:00 am-5:00 pm</p>
										<p class=\"text-info\">Calamba City, Metro Manila</p>
									</center>
								</div>
							</div>
						</div><!-- END SLIDER CAPTION -->";
	$content .= "				</div>
							</div>
						</div>
					</div>
				</div>";
	return $content;
}
?>