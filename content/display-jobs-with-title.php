<?php
include './includes/db.php';

$jobPosition = $_GET['jobPosition'];
$jobPositionID = getJobPositionID($jobPosition);
//$locationId = $_GET['locationId'];
$content = "";

// session_start();
// $_SESSION['SRIJobPosition'] = $jobPosition;

$jobPostQuery = "SELECT * FROM job_post  WHERE job_position='$jobPositionID' AND status1='0' AND status2='0' ";
$getJobPost = mysql_query($jobPostQuery) or die(mysql_error());
$jobPostsCount  = mysql_num_rows($getJobPost);
$content .="
			<div class=\"span12\">
				<div class=\"row-fluid\">
					<div class=\"span10 offset1\">
						<div class=\"span12\" id=\"zoomed-map-caption\">";
						
						if($jobPostsCount > 0){
							$content.="<p style=\"font-size:15px;\">You are looking at job availabilities for <span id=\"offered-position\" name=\"offered-position\"> $jobPosition </span> position.";
						}
						else{
							$content.="<p class=\"text-error\" style=\"font-size:15px;\">Sorry no job position for <span id=\"offered-position\" name=\"offered-position\"> $jobPosition </span> is available.";
						}
						$content.="</div>
					</div>
				</div><br/>
				<div class=\"row-fluid\">
					<div class=\"span10 offset1\">";
							while($jobPostData = mysql_fetch_assoc($getJobPost)){
								$i += 1;
								$jobPostId = $jobPostData['id'];
								$jpId = "jobId" . $i;
								$checkJobBtn = "checkJobBtn" . $i;
								$postCode = $jobPostData['code'];
								$jobEmployer = $jobPostData['employer_username'];
								$jobAddress = $jobPostData['location_id'];
								$jobAddress = getJobLocation($jobAddress);
								//$jobPosition = $jobPostData['job_position'];
								$jobPosition = getJobPost($postCode, "job-pos-name");
								$jobDesc = $jobPostData['job_desc'];
								//get employer infp
								$employerDataQuery = "SELECT * FROM employer WHERE username='$jobEmployer' ";
								$getEmployerData = mysql_query($employerDataQuery) or die(mysql_error());
								$employerData = mysql_fetch_assoc($getEmployerData);
								$employerName = $employerData['company_name'];
								$employerDesc = $employerData['company_desc'];
								$employerAddress = $employerData['city1'] . ", " . $employerData['province1'];
								$employerMobile = $employerData['mobile_num'];
								if($employerMobile == ""){
									$employerMobile = "-";
								}
								else{
									$employerMobile = substr($employerMobile, 0, -7) . "-" . substr($employerMobile, 4, -4) . "-" . substr($employerMobile, 7);
								}
								$employerEmail = $employerData['email'];
								$employerLandline = $employerData['tel_num'];
								if($employerLandline == ""){
									$employerLandline = "-";
								}
								else{
									$employerLandline = substr($employerLandline, 0, -4) . "-" . substr($employerLandline, 3, -2) . "-" . substr($employerLandline, 5);
								}
								if($i == 1){
									$content.="<ul class=\"thumbnails\">";
								}
								$content.= "<li class=\"span3\">
									<div class=\"thumbnail\">
										<div class=\"row-fluid\">
											<div class=\"span1\">
												<span class=\"job-post-num\" id=\"$jpId\" name=\'$jpId\'>$jobPostId</span>
											</div>
											<div class=\"span11\" style=\"padding-left:10px;\">
												<div class=\"row-fluid\">
													<div class=\"span12\" style=\"text-align:center;\">
														<span class=\"company-name\" rel=\"popover\" data-placement=\"right\" data-content=\"$employerLandline -- $employerMobile -- $employerEmail\" title=\"Contact Information:\" style=\"cursor:pointer;\">$employerName</span>
														<p class=\"job-post-desc\">$jobPosition</p>
														<p class=\"job-post-location\">$jobAddress</p>
													</div>
												</div>
												<div class=\"row-fluid\">
													<div class=\"span12\">
														<button type=\"submit\" class=\"btn btn-info span10 offset1\" id=\"$checkJobBtn\" name=\"$checkJobBtn\" onclick=\"checkJob2('$postCode');\">APPLY</button>
													</div>
												</div>
											</div>
										</div>
									</div></li>";
								if(($i>1) && ($i%4 === 0)){
									$content .="</ul><ul class=\"thumbnails\">";
								}
							}
$content.="</div>
				</div>
			</div>";
echo $content;
?>