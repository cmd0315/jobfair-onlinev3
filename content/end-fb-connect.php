<?php
require "includes/fb/facebook.php";
/**START FOR FACEBOOK **/
// $facebook = new Facebook(array(
  // 'appId'  => '319756228171293',
  // 'secret' => 'e0c7635245d420b2f1c0a55cd90acfa9',
// ));

//See if there is a user from a cookie
// $user = $facebook->getUser();
// if ($user) {
	// try {
		//Proceed knowing you have a logged in user who's authenticated.
		// $user_profile = $facebook->api('/me', 'GET');
		// $logoutUrl = $facebook->getLogoutUrl();
	// } catch (FacebookApiException $e) {
		//echo '<pre>'.htmlspecialchars(print_r($e, true)).'</pre>';
		// $user = null;
	// }
// }

// /**END FOR FACEBOOK **/
// $site = "http://bcdpinpoint.com/sri/content/index.php"
// $token = $facebook->getAccessToken();
// $url = 'https://www.facebook.com/logout.php?next=' . $site .
  // '&access_token='.$token;
// session_destroy();

// header('Location: '.$url);
header("Location: ./index.php");
?>