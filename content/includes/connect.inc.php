<?php
	error_reporting(0);
	
	//from http://www.webhostingtalk.com/showthread.php?t=508596
	
	// Database Variables - CHANGE THESE ACCDNG TO DATABASE SETTINGS
	$dbhost = "jobfairdb.db.11942744.hostedresource.com";
	$dbuser = "jobfairdb";
	$dbpass = "j0bFa1rSr!14";
	$dbname = "jobfairdb";
 
	$MYSQL_ERRNO = "";
	$MYSQL_ERROR = "";
 
	// Connect To Database
	function db_connect() {
		global $dbhost, $dbuser, $dbpass, $dbname;
		global $MYSQL_ERRNO, $MYSQL_ERROR;
 
		$link_id = mysql_connect($dbhost, $dbuser, $dbpass);
 
		if(!$link_id) {
			$MYSQL_ERRNO = 0;
			$MYSQL_ERROR = "Connection failed to $dbhost.";
			return 0;
		}
		else if(!mysql_select_db($dbname)) {
			$MYSQL_ERRNO = mysql_errno();
			$MYSQL_ERROR = mysql_error();
			return 0;
		}
		else return $link_id;
	}		
 
	// Handle Errors
	function sql_error() {
		global $MYSQL_ERRNO, $MYSQL_ERROR;
 
		if(empty($MYSQL_ERROR)) {
			$MYSQL_ERRNO = mysql_errno();
			$MYSQL_ERROR = mysql_error();
		}
		return "$MYSQL_ERRNO: $MYSQL_ERROR";
	}
 
	// Print Error Message
	function error_message($msg) {
		printf("Error: %s", $msg);
		exit;
	}
											
?>