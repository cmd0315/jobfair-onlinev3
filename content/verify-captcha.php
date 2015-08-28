<?php
$cryptinstall="./includes/crypt/cryptographp.fct.php";
include $cryptinstall; 

$captchaCode = $_GET['captcha_code'];
$result = "";



if (chk_crypt($captchaCode)) {
	$result = "true";
}
else{
	$result = "false";
}

echo $result;
?>