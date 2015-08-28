<?php
include './includes/db.php';
$employer= $_GET['username'];
$content = "";
$booleanChangeAcct = "false";

//get employer info
if(isset($_GET['changeAcctType'])){
	$booleanChangeAcct = $_GET['changeAcctType']; //true if give account options
	$accountType = getAcctInfo($employer, "status");
	$newTitle = "";
	if($accountType === "Employer"){
		$newTitle = "PROMOTE TO BRANCH MANAGER";
	}
	else{
		$newTitle = "DEMOTE TO REGULAR EMPLOYER";
	}
}

$companyName = getEmployerData($employer, "company-name");
$companyPicSrc = getEmployerData($employer, "profile_pic");
if($companyPicSrc === Null || !(file_exists($companyPicSrc))){
	$companyPicSrc = "./img/id.png";
}
$companyDesc = getEmployerData($employer, "company_desc");
$companyDesc = str_replace("\\n","<br />",$companyDesc);
$companyDesc = str_replace("\\r"," ",$companyDesc); 

$companyAddress = getEmployerData($employer, "address");
$cPName= getEmployerData($employer, "contact-person-name");
$cPMobile= getEmployerData($employer, "mobile");
$cPEmail= getEmployerData($employer, "email");

$content .="<div class=\"modal-header\">
						<h4 class=\"content-heading3\">Preview Company Profile</h4>
					</div>
					<div class=\"modal-body\">
						<div class=\"row-fluid\">
							<div class=\"span9\">
								<p><span style=\"font-weight:bold; color:#089DFF;\">Company Name:</span> $companyName</p>
								<p><span style=\"font-weight:bold; color:#089DFF;\">Address:</span></br>$companyAddress</p>
								<p><span style=\"padding-right:20px;\"><span style=\"font-weight:bold; color:#089DFF;\">Company Description:</span> $companyDesc</span></p>
								<p><span style=\"padding-right:20px;\"><span style=\"font-weight:bold; color:#089DFF;\">Contact Person:</span> $cPName </span><span style=\"padding-right:20px;\"></p>
								<div class=\"row-fluid\">
									<div class=\"span6\">
										<p style=\"font-weight:bold; color:#089DFF;\">Contact Numbers:</p>
										<ul>";

										if($cPEmail != ""){
											$content.= "<li>$cPEmail</li>";
										}
										if($cPMobile != ""){
											$cPMobile = substr($cPMobile, 0, -7) . "-" . substr($cPMobile, 4, -4) . "-" . substr($cPMobile, 7);
											$content.= "<li>$cPMobile</li>";
										}
$content .="				</ul>
								</div>
								</div>
							</div>
							<div class=\"span3\">
								<img src='$companyPicSrc'/>
							</div>";
$content.="	</div>
					</div>
					<div class=\"modal-footer\">
						<div class=\"row-fluid\">";
						if($booleanChangeAcct=="true"){
							$content.= "<div class=\"span10 offset1\">
								<button class=\"btn btn-primary span6\" id=\"changeAcctTypeBtn\" name=\"changeAcctTypeBtn\" style=\"font-size:12px;\" onclick=\"changeAccountType('$employer')\">$newTitle</button>
								<button class=\"btn span6\" data-dismiss=\"modal\" style=\"font-size:12px;\">CANCEL</button>
							</div>";
						}
						else{
							$content.= "<div class=\"span4 offset4\">
								<button class=\"btn btn-primary span12\" data-dismiss=\"modal\" style=\"font-size:12px;\">OK</button>
								</div>";
						}
							
$content.="				</div>
					</div>";
echo $content;
?>