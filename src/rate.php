<?php
require('config.php');
$value = $_POST["shid"];
$list = explode('-', $value);
$rate = $list[0];
$smarthashtagid = $list[1];
if ($rate == "like") {
  $sql = "UPDATE smarthashtags SET likes = likes + 1 WHERE id="." :shid";
  $stmt = $DB->prepare($sql);
  $stmt->bindValue(":shid", $smarthashtagid);
  $stmt->execute();
  $result = $stmt->rowCount();
  if ($result > 0) {
    $sql = "SELECT likes, dislikes FROM smarthashtags WHERE id="." :shid";
    $stmt = $DB->prepare($sql);
    $stmt->bindValue(":shid", $smarthashtagid);
    $stmt->execute();
    $result = $stmt->fetch();
    echo "Likes: ".$result["likes"]."Dislikes: ".$result["dislikes"];
  } else {
    echo "SQL Error";
  }
} elseif ($rate == "dislike") {
  $sql = "UPDATE smarthashtags SET dislikes = dislikes + 1 WHERE id="." :shid";
  $stmt = $DB->prepare($sql);
  $stmt->bindValue(":shid", $smarthashtagid);
  $stmt->execute();
  $result = $stmt->rowCount();
  if ($result > 0) {
    $sql = "SELECT likes, dislikes FROM smarthashtags WHERE id="." :shid";
    $stmt = $DB->prepare($sql);
    $stmt->bindValue(":shid", $smarthashtagid);
    $stmt->execute();
    $result = $stmt->fetch();
    echo "Likes: ".$result["likes"]." Dislikes: ".$result["dislikes"];
  } else {
    echo "SQL Error";
  }
} else {
  echo "Error";
}
?>
