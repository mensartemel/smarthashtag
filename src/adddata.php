<?php
require('config.php');
$number = count($_POST["keyword"]);
if($number >= 1)
{
  $errorCount = 0;
  $lang = $_POST["lang"];
  $consumerid = $_SESSION["consumerid"];
  if (isset($_POST["profilesearch"])) {
    $profilesearch = "true";
  } else {
    $profilesearch = "false";
  }
  $sql = "INSERT INTO smarthashtags (lang, profilesearch, consumerid) VALUES (:lang, :profilesearch, :consumerid)";
  $stmt = $DB->prepare($sql);
  $stmt->bindValue(":lang", $lang);
  $stmt->bindValue(":profilesearch", $profilesearch);
  $stmt->bindValue(":consumerid", $consumerid);
  $stmt->execute();
  $smarthashtagid = $DB->lastInsertId();
  if ($smarthashtagid > 0) {
    for($i=0; $i<$number; $i++)
    {
      if(trim($_POST["keyword"][$i] != ''))
      {
        $sql = "SELECT * FROM keywords WHERE keyword ="." :keyword";
        $stmt = $DB->prepare($sql);
        $stmt->bindValue(":keyword", $_POST["keyword"][$i]);
        $stmt->execute();
        $result4 = $stmt->fetch();
        $keywordid[$i] = $result4["id"];
        if ($result4 > 0) {
          $sql = "INSERT INTO sh_kw (shid, kwid) VALUES"." (:shid, :kwid)";
          $stmt = $DB->prepare($sql);
          $stmt->bindValue(":shid", $smarthashtagid);
          $stmt->bindValue(":kwid", $keywordid[$i]);
          $stmt->execute();
          $result3 = $stmt->rowCount();
          if ($result3 > 0) {
          } else {
            $errorCount += 1;
          }
        }
        else {
          $sql = "INSERT INTO keywords (keyword) VALUES "."(:keyword)";
          $stmt = $DB->prepare($sql);
          $stmt->bindValue(":keyword", $_POST["keyword"][$i]);
          $stmt->execute();
          $keywordid[$i] = $DB->lastInsertId();
          if ($keywordid[$i] > 0) {
            $sql = "INSERT INTO sh_kw (shid, kwid) VALUES"." (:shid, :kwid)";
            $stmt = $DB->prepare($sql);
            $stmt->bindValue(":shid", $smarthashtagid);
            $stmt->bindValue(":kwid", $keywordid[$i]);
            $stmt->execute();
            $result5 = $stmt->rowCount();
            if ($result5 > 0) {
            } else {
              $errorCount += 1;
            }
          } else {
            $errorCount += 1;
          }
        }
      }
    }
  } else {
    $errorCount += 1;
  }
  if ($errorCount <= 0) {
    require_once '../src/twitter.class.php';
    foreach($_SESSION['OAUTH_ACCESS_TOKEN'] as $key) {
    	$accessToken = $key['value'];
    	$accessTokenSecret = $key['secret'];
    }
    $consumerKey = "XvlI4EG7NjJbauQs4KK9JMzsA";
    $consumerSecret = "fFuhGfvSZXRcS9GOruuv6xdBpRzYPEdfp2sAkyGt6PFcsTKg81";
    $twitter = new Twitter($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

    for($i=0; $i<$number; $i++) {
      if(trim($_POST["keyword"][$i] != '')) {
        $results = $twitter->search(['q' => $_POST["keyword"][$i], 'lang' => $lang, 'result_type' => 'mixed', 'count' => '5']);
        foreach ($results as $status) {
          echo $status->user->text . "</br>";
          $sql = "INSERT INTO consumer_results (screenname, picture, status, created_at, consumerid, keywordid, appid, shid) VALUES "." (:screenname, :picture, :status, NOW(), :consumerid, :keywordid, :appid, :shid)";
          $stmt = $DB->prepare($sql);
          $stmt->bindValue(":screenname", $status->user->screen_name);
          $stmt->bindValue(":picture", $status->user->profile_image_url_https);
          $stmt->bindValue(":status", $status->user->text);
          $stmt->bindValue(":consumerid", $_SESSION["consumerid"]);
          $stmt->bindValue(":keywordid", $keywordid[$i]);
          $stmt->bindValue(":appid", $_SESSION["appid"]);
          $stmt->bindValue(":shid", $smarthashtagid);
          $stmt->execute();
          $result2 = $stmt->rowCount();
          if ($result2 > 0) {
            echo $status->user->text . "</br>";
          } else {
            $errorCount += 1;
          }
          if (strpos($status->user->description, $_POST["keyword"][$i])) {
            $sql = "INSERT INTO user_results (screenname, location, description, keywordid, consumerid, shid) VALUES "." (:screenname, :location, :description, :keywordid, :consumerid, :shid)";
            $stmt = $DB->prepare($sql);
            $stmt->bindValue(":screenname", $status->user->screen_name);
            $stmt->bindValue(":location", $status->user->location);
            $stmt->bindValue(":description", $status->user->description);
            $stmt->bindValue(":keywordid", $keywordid[$i]);
            $stmt->bindValue(":consumerid", $_SESSION["consumerid"]);
            $stmt->bindValue(":shid", $smarthashtagid);
            $stmt->execute();
            $result3 = $stmt->rowCount();
            if ($result3 > 0) {
              echo $status->user->description . "</br>";
            } else {
              $errorCount += 1;
            }
          } else {
            echo "No profile search result</br>";
          }
        }
      }
    }
    if ($errorCount > 0) {
      echo $errorCount;
    }
  }
} else {
  echo "Page Not Found";
}
