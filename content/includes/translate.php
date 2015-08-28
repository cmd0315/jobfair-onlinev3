<?php
session_start();

$allowed_lang = array('english', 'tagalog');
$lang = $_GET['lang'];


if(isset($lang) === true && in_array($lang, $allowed_lang) === true){
	$_SESSION['lang'] = $lang;
}
else if(isset($lang) === false){
	$lang = "english";
}

include 'lang/' . $lang . '.php';
?>