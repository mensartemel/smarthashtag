<?php
require('http.php');
require('oauth_client.php');
require('config.php');

$appkey = $_GET['appkey'];
$sql = "SELECT appid from applications where appkey = :appkey";
$stmt = $DB->prepare($sql);
$stmt->bindValue(":appkey", $appkey);
$stmt->execute();
$result = $stmt->rowCount();
echo $result."</br>";

if ($result > 0) {

  $stmt = $DB->prepare($sql);
  $stmt->bindValue(":appkey", $appkey);
  $stmt->execute();
  $result = $stmt->fetch();
  $appid = $result['appid'];

  echo $appid."</br>";

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
    // Now check if consumer exist with same ID
    $sql = "SELECT id from consumers where twitter_id = :id";
    try {
      $stmt = $DB->prepare($sql);
      $stmt->bindValue(":id", $user->id);
      $stmt->execute();
      $result = $stmt->fetch();
      $consumerid = $result["id"];

      if ($userid > 0) {
        // User Exist

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
        $userid = $stmt->lastInsertId();
        $result = $stmt->rowCount();
        if ($result > 0) {
          $_SESSION["username"] = $user->screen_name;
          $_SESSION["consumerid"] = $userid;
          $_SESSION["twitter_id"] = $user->id;
          $_SESSION["name"] = $user->name;
          $_SESSION["picture"] = $user->profile_image_url;
          $_SESSION["cons_in"] = true;
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
  // header("location: ../index.php");
  echo "No application found!</br>";
  exit;
}
else {
  echo "No application found!";
}
?>
