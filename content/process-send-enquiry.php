<?php
extract($_POST);

$mobile = $mobile1. $mobile2. $mobile3;
$landline = $landline1 . $landline2 . $landline3;

$to = 'support@jobfair-online.net';
$tocc = 'jazel.deleon@serviceresourcesinc.com; charissedalida@gmail.com';

$subject = "JobFair-Online.Net Enquiry";

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
$headers .="from: " . $email;

$message = "<br><br><br>
			Enquiry: $enquiry<br>
			Contact Person: $name<br>
			Mobile Number: $mobile<br>
			Landline Number: $landline<br>
			Email Address: $email<br>
			Message: $msg<br><br>";
			

if(($enquiry != "")&&($name != "")){
	mail($to,$subject,$message,$headers);
	mail($tocc,$subject,$message,$headers);
	//mail($tobcc,$subject,$message,$headers);
	echo "Message Sent. . .";
}

?>