<?php

require('http.php');
require('oauth_client.php');
require('config.php');

$client = new oauth_client_class;
$client->debug = 1;
$client->debug_http = 1;
$client->redirect_uri = REDIRECT_URL;

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

$twUserData = array(
            'oauth_uid'   => $user->id,
            'name'    		=> $user->name,
			      'username'    => $user->screen_name,
            'locale'      => $user->lang,
            'picture'     => $user->profile_image_url_https,
        );

if ($success) {

  if ($_SESSION["is_consumer"] == true) {
    $sql = "SELECT id from consumers where twitter_id = :id";
    $stmt = $DB->prepare($sql);
    $stmt->bindValue(":id", $user->id);
    $stmt->execute();
    $result = $stmt->fetch();
    $consumerid = $result["id"];
    echo "Consumer ID:".$consumerid."</br>";
    if ($consumerid > 0) {
      // User Exist
      echo "User exists.</br>";
      $_SESSION["username"] = $user->screen_name;
      $_SESSION["consumerid"] = $consumerid;
      $_SESSION["twitter_id"] = $user->id;
      $_SESSION["name"] = $user->name;
      $_SESSION["picture"] = $user->profile_image_url;
      $_SESSION["cons_in"] = true;
    } else {
      // New user, Insert in database
      $sql = "INSERT INTO `consumers` (`twitter_id`, `appid`) VALUES " . "( :twitter_id, :appid)";
      $stmt = $DB->prepare($sql);
      $stmt->bindValue(":appid", $appid);
      $stmt->bindValue(":twitter_id", $user->id);
      $stmt->execute();
      $result = $stmt->rowCount();
      if ($result > 0) {
        echo "New user.</br>";
        $_SESSION["username"] = $user->screen_name;
        $_SESSION["consumerid"] = $userid;
        $_SESSION["twitter_id"] = $user->id;
        $_SESSION["name"] = $user->name;
        $_SESSION["picture"] = $user->profile_image_url;
        $_SESSION["cons_in"] = true;
        $_SESSION["e_msg"] = "";
      }
    }
  }
  // Now check if user exist with same email ID
  $sql = "SELECT id from users where twitter_id = :id";
  try {
    $stmt = $DB->prepare($sql);
    $stmt->bindValue(":id", $user->id);
    $stmt->execute();
    $result = $stmt->fetch();
    $userid = $result["id"];

    if ($userid > 0) {
      // User Exist

      $_SESSION["username"] = $user->screen_name;
      $_SESSION["userid"] = $userid;
      $_SESSION["twitter_id"] = $user->id;
      $_SESSION["name"] = $user->name;
      $_SESSION["picture"] = $user->profile_image_url;
      $_SESSION["logged_in"] = true;
    } else {
      // New user, Insert in database
      $sql = "INSERT INTO `users` (`username`, `twitter_id`, `name`, `picture`) VALUES " . "( :username, :twitter_id, :name, :picture)";
      $stmt = $DB->prepare($sql);
      $stmt->bindValue(":username", $user->name);
      $stmt->bindValue(":twitter_id", $user->id);
      $stmt->bindValue(":name", $user->screen_name);
      $stmt->bindValue(":picture", $user->profile_image_url);
      $stmt->execute();
      $result = $stmt->rowCount();
      if ($result > 0) {
        $sql = "SELECT id from users where twitter_id = :id";
        $stmt = $DB->prepare($sql);
        $stmt->bindValue(":id", $user->id);
        $stmt->execute();
        $result = $stmt->fetch();
        $userid = $result["id"];

        $_SESSION["username"] = $user->screen_name;
        $_SESSION["userid"] = $userid;
        $_SESSION["twitter_id"] = $user->id;
        $_SESSION["name"] = $user->name;
        $_SESSION["picture"] = $user->profile_image_url;
        $_SESSION["logged_in"] = true;
        $_SESSION["e_msg"] = "";
      }
    }
  } catch (Exception $ex) {
    $_SESSION["e_msg"] = $ex->getMessage();
  }

  $_SESSION["user_id"] = $user->id;
} else {
  $_SESSION["e_msg"] = $client->error;
}
header("location: ../index.php");
exit;
?>
