<?php
	define('DB_DRIVER', 'mysql');
	define('DB_SERVER', 'us-cdbr-east-05.cleardb.net');
	define('DB_SERVER_USERNAME', 'ba0b040b98b0f5');
	define('DB_SERVER_PASSWORD', 'f87a19f4');
	define('DB_DATABASE', 'heroku_4bc08eac4ea8f88');

	try {
	  $DB = new PDO(DB_DRIVER . ':host=' . DB_SERVER . ';port=3308;dbname=' . DB_DATABASE, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, $dboptions);
	} catch (Exception $ex) {
	  echo $ex->getMessage();
	  die;
	}


	define("CLIENT_ID", "XvlI4EG7NjJbauQs4KK9JMzsA");
	define("SECRET_KEY", "fFuhGfvSZXRcS9GOruuv6xdBpRzYPEdfp2sAkyGt6PFcsTKg81");
	/* make sure the url end with a trailing slash, give your site URL */
	define("SITE_URL", "http://webproje/");
	/* the page where you will be redirected for authorization */
	define("REDIRECT_URL", SITE_URL."twitter_login.php");

	define("LOGOUT_URL", SITE_URL."logout.php");
?>
