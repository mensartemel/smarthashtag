<?php
  $sql = "SELECT * FROM `users`";
  $stmt = $DB->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch()) {
    echo $row['username']."</br>";
    echo $row['twitter_id']."</br>";
    echo $row['name']."</br>";
    echo $row['picture'];
    echo "</br>";
  }
  echo "</br>";
  echo $_SESSION["value"];
  echo "</br>";
  echo $_SESSION["secret"];
    echo "</br>";
  foreach($_SESSION['OAUTH_ACCESS_TOKEN'] as $result) {
  	echo $result['value'];
      echo "</br>";
  	echo $result['secret'];
  }
?>
