<?php

require('http.php');
require('oauth_client.php');
require('config.php');

//require('config.php');

$client = new oauth_client_class;
$client->debug = 1;
$client->debug_http = 1;
$client->redirect_uri = REDIRECT_URL;
//$client->redirect_uri = 'oob';

$client->client_id = CLIENT_ID;
$application_line = __LINE__;
$client->client_secret = SECRET_KEY;

if (strlen($client->client_id) == 0 || strlen($client->client_secret) == 0)
  die('Please go to Twitter Apps page https://dev.twitter.com/apps/new , ' .
          'create an application, and in the line ' . $application_line .
          ' set the client_id to Consumer key and client_secret with Consumer secret. ' .
          'The Callback URL must be ' . $client->redirect_uri . ' If you want to post to ' .
          'the user timeline, make sure the application you create has write permissions');

if (($success = $client->Initialize())) {
  if (($success = $client->Process())) {
    if (strlen($client->access_token)) {
      $success = $client->CallAPI(
              'https://api.twitter.com/1.1/account/verify_credentials.json', 'GET', array(), array('FailOnAccessError' => true), $user);
    }
  }
  $success = $client->Finalize($success);
}
if ($client->exit)
  exit;
if ($success) {
  // Now check if user exist with same email ID
  $sql = "SELECT COUNT(*) AS count from users where twitter_id = :id";
  try {
    $stmt = $DB->prepare($sql);
    $stmt->bindValue(":id", $user->id);
    $stmt->execute();
    ///$result = $stmt->fetchAll();
	///$result2 = $stmt->fetch(PDO::FETCH_BOTH);
	while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)) {
      $result = $row[0] . "\t" . $row[1] . "\t" . $row[2] . "\n";
    }

    if ($result > 0) {
      // User Exist
	  $userid = $result;
	  $_SESSION["userid"] = $userid;
    $_SESSION["name"] = $user->name;
    $_SESSION["id"] = $user->id;
	  $_SESSION["picture"] = $user->profile_image_url;
	  $_SESSION["bg-picture"] = $user->profile_banner_url;
    $_SESSION["new_user"] = "no";
	  $_SESSION["loggedin"] = true;
    } else {
      // if consumer exist with appkey
      if(isset($_GET['appkey'])){
        $appkey = $_GET['appkey'];

        $sql = "SELECT appid from applications where appkey = :appkey";
        $stmt = $DB->prepare($sql);
        $stmt->bindValue(":appkey", $appkey);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)) {
          $appid = $row[0] . "\t" . $row[1] . "\t" . $row[2] . "\n";
        }

        $sql = "SELECT consumerid from consumers where twitterid = :id";
        $stmt = $DB->prepare($sql);
        $stmt->bindValue(":id", $user->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($stmt->rowCount()){
          $consumerid = $row["consumerid"];
        }
        if ($consumerid > 0) {
            $_SESSION["consumerid"] = $consumerid;
            $_SESSION["appkey"] = $appkey;
            $_SESSION["appid"] = $appid;
            $_SESSION["id"] = $user->id;
        	  $_SESSION["isconsumer"] = true;
          }
        else {
          $sql = "INSERT INTO `consumers` (`twitterid`, `appid`) VALUES " . "(:twitterid, :appid)";
          $stmt = $DB->prepare($sql);
          $stmt->bindValue(":twitterid", $user->id);
          $stmt->bindValue(":appid", $appid);
          $stmt->execute();
          $result = $stmt->rowCount();
          if ($result > 0) {
            $sql = "SELECT consumerid from consumers where twitter_id = :id";
            $stmt = $DB->prepare($sql);
            $stmt->bindValue(":id", $user->id);
            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)) {
              $consumerid = $row[0] . "\t" . $row[1] . "\t" . $row[2] . "\n";
            }
            echo "Consid: " .$consumerid;
            $_SESSION["consumerid"] = $consumerid;
            $_SESSION["appkey"] = $appkey;
            $_SESSION["appid"] = $appid;
            $_SESSION["id"] = $user->id;
        	  $_SESSION["isconsumer"] = true;
          }
        }
        header("location:api.php?key=$appkey");
      }
      else {
        echo "Mevcut Appkey girilmemiş";
      }
      // New user, Insert in database
/*      $sql = "INSERT INTO `users` (`username`, `twitter_id`, `name`, `locale`, `picture`) VALUES " . "( :username, :id, :name, :locale, :picture)";
      $stmt = $DB->prepare($sql);
      $stmt->bindValue(":name", $user->name);
      $stmt->bindValue(":id", $user->id);
	     $stmt->bindValue(":username", $user->screen_name);
       $stmt->bindValue(":picture", $user->profile_image_url);
       $stmt->bindValue(":locale", $user->lang);
      $stmt->execute();
      $result = $stmt->rowCount();

      if ($result > 0) {
		$userid = $DB->lastInsertId();

    $_SESSION["name"] = $user->name;
    $_SESSION["id"] = $user->id;
		$_SESSION["userid"] = $userid;
		$_SESSION["picture"] = $user->profile_image_url;
		$_SESSION["bg-picture"] = $user->profile_banner_url;
    $_SESSION["new_user"] = "yes";
    $_SESSION["e_msg"] = "";
		$_SESSION["loggedin"] = true;
      }
*/    }
  } catch (Exception $ex) {
    $_SESSION["e_msg"] = $ex->getMessage();
  }

  $_SESSION["user_id"] = $user->id;
} else {
  $_SESSION["e_msg"] = $client->error;
}
if ($_SESSION["loggedin"] == true) {
  header("location:index.php");
}
exit;
?>
