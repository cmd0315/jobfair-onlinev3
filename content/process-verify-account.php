<?php
include './includes/db.php';

extract($_POST);
$status = getAcctInfo($username, "status");
if($status === "Employee"){
	$name = getEmployeeData($username, "full-name");
}
else if($status === "Employer"){
	$name = getEmployerData($username, "company-name");
}

date_default_timezone_set('Singapore');
$currDateTime = date("Y-m-d H:i:s");

/*-- START JOB POST CODE GENERATION --*/
/* START GENERATING VALIDATION CODE*/
//code taken from: http://www.lost-in-code.com/programming/php-code/php-random-string-with-numbers-and-letters/
function genRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters) -1)];
    }
    return $string;
}
/* END GENERATING VALIDATION CODE*/

function checkIfCodeExists($code){
	$codeExistsQuery = "SELECT code FROM verification_code WHERE code=$code";
	$checkCodeExists = mysql_query($codeExistsQuery);
	$countCodeExists = mysql_num_rows($checkCodeExists);
	if($countCodeExists > 1) {
		return true;
	}
	else
		return false;
}

function generateCode() {
	$verificationCode = genRandomString(5);
	if(checkIfCodeExists($verificationCode)){
		$verificationCode = generateCode();
	}
	else
		return $verificationCode;
}

$verificationCode = generateCode();
/*-- END JOB POST CODE GENERATION --*/

if(isset($username) && isset($verificationCode)){
	//ADD ROW TO VERIFICATION CODE TABLE
	$checkUserHasCodeQuery = "SELECT * FROM verification_code WHERE username='$username'";
	$checkUserHasCode = mysql_query($checkUserHasCodeQuery);
	$rowNum = mysql_num_rows($checkUserHasCode);
	if($rowNum == 0){
		$verificationCode = genRandomString(5);
		$addCodeQuery = "INSERT INTO verification_code(code, username, date_added, status) VALUES('$verificationCode', '$username', '$currDateTime', '0')";
		$addCode = mysql_query($addCodeQuery) or die(mysql_error());
	}
	else{
		$getCodeQuery = "Select code FROM verification_code WHERE username='$username'";
		$getCode = mysql_query($getCodeQuery) or die(mysql_error());
		$verificationCode = mysql_result($getCode,0);
	}

	if($resetPwdHow == "link") {
		//for EMAIL
		//$email = stripslashes($username);
		$recipient = $name;
		$to = $email;
		$sender = $tocc = "support@people-link.asia";
		//Your subject 
		$subject="People-Link.Asia: Verify Account";
		//From
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers.= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
		$headers.="from: " . $sender;
		//Your message
		$message = "Dear <strong>$recipient</strong>";
		$message.= "<br/><br/>";
		$message.="Welcome to People-Link.Asia! <br/> \r\n";
		$message.="Follow this <a href=\"http://people-link.asia/content/verify-account-viaemail.php?username=$username&verificationCode=$verificationCode\">link</a> to verify your account and complete the registration process.<br/><br/> \r\n";
		if($status==="Employee"){
			$message .= "Good luck on your job search!";
		}
		else{
			$message .= "Good luck on your search for future employees!";
		}
		$message.="<br/><br/>---------------------------<br/>";
		$message.="Yours truly,<br/><strong>The People-Link.Asia Admin</strong>";
		//send email
		if(($recipient != "")&&($email != "")){
			mail($to,$subject,$message,$headers);
			mail($tocc,$subject,$message,$headers);
			//mail($tobcc,$subject,$message,$headers);
			echo "Email Sent. . .";
		}
	}
	else{
		//for PHONE
		 //SEND TEXT VERIFICATION CODE
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"http://bcdpinpoint.com/sri/content/includes/sms.php");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,"number=".$mobile."&verification=".$verificationCode."");
			
		//Response of the POST request 
		$response = curl_exec($ch);
		$updateVerificationTableQuery = "UPDATE verification_code SET status='$response' WHERE username='$username' ";
		$updateVerificationTable = mysql_query($updateVerificationTableQuery) or die(mysql_error());
		echo $response;
		curl_close($ch);
	}
}
?>