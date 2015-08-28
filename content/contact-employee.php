<?php
include './includes/db.php';

/* GET SESSION DATA*/
session_start();
$username = $_SESSION['SRIUsername'];

//GET EMPLOYER INFO
$status = getAcctInfo($username, "status");
$coName = getEmployerData($username, "company-name");

$applicant= $_GET['username'];
//get applicant nfo
$applicantName = getEmployeeData($applicant, "full-name");
$applicantMobile = getEmployeeData($applicant, "mobile");
$applicantEmail = getEmployeeData($applicant, "email");
$allApplicants = getAllEmployees();
$content = "";
$content .="<div class=\"modal-header\">
						<h4 class=\"content-heading3\">Contact Employee</h4>
					</div>
					<div class=\"modal-body\">
						<div class=\"row-fluid form-horizontal\">
							<div class=\"span10\">
								 <div class=\"control-group\">
									<label class=\"control-label\">To</label>
										<div class=\"controls\">
											<select class=\"span11\" id=\"contactApplicant\" name=\"contactApplicant\" required>";
												foreach ($allApplicants as $a){
													if($a == $applicantName){
														$content.= "<option value=\"$a\" selected=\"selected\">$a</option>";
													}
													else{
														$content.= "<option value=\"$a\">$a</option>";
													}
												}
					$content .=   "</select>
											<p class=\"help-block\"></p>
										</div>
									</div>
								</div>
								 <div class=\"control-group\">
									<label class=\"control-label\">From</label>
									<div class=\"controls\">
										<input class=\"span11\" type=\"text\" id=\"employer\" name=\"employer\" value=\"$coName\"/>
									  <p class=\"help-block\"></p>
									</div>
								</div>
								<div class=\"control-group\">
									<label class=\"control-label\">Message</label>
									<div class=\"controls\">
										<textarea class=\"input-block-level\" id=\"msgContent\" name=\"msgContent\" placeholder=\"Enter message here\" required></textarea>
									  <p class=\"help-block\"></p>
									</div>
							</div>
							</div>";
$content.="	</div>
					</div>
					<div class=\"modal-footer\">
						<div class=\"row-fluid\">
							<div class=\"span4\">
								<button class=\"btn span12\" id=\"editBtn\" name=\"editBtn\" data-dismiss=\"modal\" style=\"font-size:12px;\">CANCEL</button>
							</div>
							<div class=\"span4\">
								<button class=\"btn-primary span12\" id=\"deleteBtn\" name=\"deleteBtn\" onclick=\"sendSMS('$applicant');\" style=\"font-size:12px;\">SEND SMS</button>
							</div>
							<div class=\"span4\">
								<button class=\"btn-primary span12\" id=\"deleteBtn\" name=\"deleteBtn\" onclick=\"sendEmail('$applicant');\" style=\"font-size:12px;\">SEND EMAIL</button>
							</div>
						</div>
					</div>";
echo $content;

?>