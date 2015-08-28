<?php 
  /** START FOR DB **/
include './includes/connect.inc.php';
$link_id = db_connect($dbname);
if(!$link_id) error_message(sql_error());
/** END FOR DB**/

/* GET SESSION DATA*/
session_start();
$username = $_SESSION['SRISearchUser'];

$statusQuery = "SELECT * FROM account WHERE username='$username' ";
$getStatus = mysql_query($statusQuery) or die(mysql_error());
$accountData = mysql_fetch_assoc($getStatus);
$acct_type = $accountData['acct_type'];
if($acct_type == 1){
	$nameQuery = "SELECT * FROM employer WHERE username='$username' ";
	$getName = mysql_query($nameQuery) or die(mysql_error());
	$employerData = mysql_fetch_assoc($getName);
	$name = $employerData['company_name'];
}
else if($acct_type == 2){
	$nameQuery = "SELECT * FROM employee WHERE username='$username' ";
	$getName = mysql_query($nameQuery) or die(mysql_error());
	$employeeData = mysql_fetch_assoc($getName);
	$firstName = $employeeData['first_name'];
	$middleName = $employeeData['middle_name'];
	$lastName = $employeeData['last_name'];
	$fullName = $firstName . " " . $middleName . " " . $lastName;
	$name = $fullName;
}

extract($_POST);

//get currentdate
$currDate = date("Y-m-d");

/*-- START JOB POST CODE GENERATION --*/
/* START GENERATING VALIDATION CODE*/
//code taken from: http://www.lost-in-code.com/programming/php-code/php-random-string-with-numbers-and-letters/
function genRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
	$string = '';
    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters) -1)];
    }
    return $string;
}

/*-- END JOB POST CODE GENERATION --*/

$checkUserHasCodeQuery = "SELECT * FROM verification_code WHERE username='$username'";
$checkUserHasCode = mysql_query($checkUserHasCodeQuery);
$rowNum = mysql_num_rows($checkUserHasCode);
if($rowNum == 0){
	$verificationCode = genRandomString(5);
	$addCodeQuery = "INSERT INTO verification_code(code, username, date_added) VALUES('$verificationCode', '$username', '$currDate')";
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
	$sender = $tocc = "support@jobfair-online.net";
	//Your subject 
	$subject="JobFair-Online.Net: Reset Password Code";
	//From
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers.= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
	$headers.="from: " . $sender;
	//Your message
	$message = "Hi, <strong>$recipient</strong>!";
	$message.= "<br/><br/>";
	$message.="We have received your request to change your log in password. Use this code to reset your password:<br /> \r\n";
	$message .= "Reset Password Code: <strong>$verificationCode</strong>";
	//$message.="Follow this link to reset your password: http://people-link.asia/content/reset-password-via-email.php?code=$verificationCode";
	
	$message.="<br/><br/>---------------------------<br/>";
	$message.="Yours truly,<br/><strong>The JobFair-Online.Net Admin</strong>";
	//send email
	if(($recipient != "")&&($email != "")){
		mail($to,$subject,$message,$headers);
		mail($tocc,$subject,$message,$headers);
		//mail($tobcc,$subject,$message,$headers);
		echo "Message Sent. . .";
		$addLogQuery = "INSERT INTO activity_logs(date_made, username, action, reference_object) VALUES('$currDateTime', '$username', 'SEND CODE FOR PASSWORD RESET', 'email')";
	}
}
else{
	//for PHONE
	 //SEND TEXT VERIFICATION CODE
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,"http://bcdpinpoint.com/sri/content/includes/sms-reset-password.php");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,"number=".$mobile."&verification=".$verificationCode."");
		
	//Response of the POST request 
	$response = curl_exec($ch);
	$updateVerificationTableQuery = "UPDATE verification_code SET status='$response' WHERE username='$username' ";
	$updateVerificationTable = mysql_query($updateVerificationTableQuery) or die(mysql_error());
	curl_close($ch);
	echo "Text Sent. . .";

	$addLogQuery = "INSERT INTO activity_logs(date_made, username, action, reference_object) VALUES('$currDateTime', '$username', 'SEND CODE FOR PASSWORD RESET', 'mobile')";
}

//add log activity
$addLog = mysql_query($addLogQuery) OR die(mysql_error());

mysql_close($link_id);
?>