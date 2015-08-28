<?php

/* START GENERATING VALIDATION CODE*/
//code taken from: http://www.lost-in-code.com/programming/php-code/php-random-string-with-numbers-and-letters/
function genRandomString($length) {
    $characters = '0123456789';
    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters))];
    }
    return $string;
}
/* END GENERATING VALIDATION CODE*/

function checkIfCodeExists($jobCode){
	$codeExistsQuery = "SELECT code FROM job_post WHERE code=$jobCode";
	$checkCodeExists = mysql_query($codeExistsQuery);
	$countCodeExists = mysql_num_rows($checkCodeExists);
	if($countCodeExists > 1) {
		return true;
	}
	else
		return false;
}

function generateCode() {
	$jobPostCode = genRandomString(10);
	if(checkIfCodeExists($jobPostCode)){
		$jobPostCode = generateCode();
	}
	else
		return $jobPostCode;
}
?>