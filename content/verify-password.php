<?php
$pwd1 = $_GET['pwd1'];
$pwd2 = $_GET['pwd2'];
$result = "";

if($pwd1 == ""){
		$result = "<span class=\"text-error\" style=\"font-weight:bold; margin-left:10px;\">Type old password.</span>";
}
$pwd1 = md5($pwd1);

if($pwd1 == $pwd2){
	echo "<span class=\"text-success\" style=\"font-weight:bold; margin-left:10px;\">Matched!</span>";
}
else{
		echo "<span class=\"text-error\" style=\"font-weight:bold; margin-left:10px;\">Does not matched!</span>";
}
?>