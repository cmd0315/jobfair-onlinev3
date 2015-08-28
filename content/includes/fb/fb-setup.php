<?php
/**START FACEBOOK **/
$facebook = new Facebook(array(
  'appId'  => '319756228171293',
  'secret' => 'e0c7635245d420b2f1c0a55cd90acfa9',
  'cookie' => true,
));

// See if there is a user from a cookie
$user = $facebook->getUser();
if ($user) {
	try {
		// Proceed knowing you have a logged in user who's authenticated.
		$user_profile = $facebook->api('/me', 'GET');
	} catch (FacebookApiException $e) {
		//echo '<pre>'.htmlspecialchars(print_r($e, true)).'</pre>';
		$user = null;
	}
}

$fbStatus = "true";
/**END FACEBOOK **/

?>