<?php
	error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
	ob_start();
	session_start();

	define('PROJECT_NAME', 'Login System with Twitter using OAuth PHP and Mysql - www.thesoftwareguy.in');

	define('DB_DRIVER', 'mysql');
	define('DB_SERVER', 'localhost');
	define('DB_SERVER_USERNAME', 'root');
	define('DB_SERVER_PASSWORD', '');
	define('DB_DATABASE', 'webproje');

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
