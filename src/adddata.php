<?php
require('config.php');

$number = 1;//count($_POST["keyword"]);
if($number >= 1)
{
  $profilesearch = $_POST["profilesearch"];
  $lang = $_POST["lang"];
  $consumerid = $_SESSION["consumerid"];

  $sql = "INSERT INTO smarthashtags ('lang', 'profilesearch', 'consumerid') VALUES"." (:lang, :profilesearch, :consumerid)";
  $stmt = $DB->prepare($sql);
  $stmt->bindValue(":lang", $lang);
  $stmt->bindValue(":profilesearch", $profilesearch);
  $stmt->bindValue(":consumerid", $consumerid);
  $stmt->execute();
  $smarthashtagid = $DB->lastInsertId();

	for($i=0; $i<$number; $i++)
	{
		if(trim($_POST["keyword"][$i] != ''))
		{
      $sql = "SELECT * FROM keywords WHERE keyword ="." :keyword";
      $stmt = $DB->prepare($sql);
      $stmt->bindValue(":keyword", $_POST["keyword"][$i]);
      $stmt->execute();
      $result = $stmt->fetch();
      $keywordid = $result["id"];
      if ($result > 0) {
        $sql = "INSERT INTO sh_kw ('shid', 'kwid') VALUES"." (:shid, :kwid)";
        $stmt = $DB->prepare($sql);
        $stmt->bindValue(":shid", $smarthashtagid);
        $stmt->bindValue(":kwid", $keywordid);
        $stmt->execute();
        $result = $stmt->rowCount();
        if ($result > 0) {
          echo "New hashtag, new keyword inserted";
        }
        echo "New hashtag, error while keyword inserted";
      }
      else {
        $sql = "INSERT INTO keywords ('keyword') VALUES "."(:keyword)";
        $stmt = $DB->prepare($sql);
        $stmt->bindValue(":keyword", $_POST["keyword"][$i]);
        $stmt->execute();
        $keywordid = $DB->lastInsertId();
        if ($keywordid > 0) {
          $sql = "INSERT INTO sh_kw ('shid', 'kwid') VALUES"." (:shid, :kwid)";
          $stmt = $DB->prepare($sql);
          $stmt->bindValue(":shid", $smarthashtagid);
          $stmt->bindValue(":kwid", $keywordid);
          $stmt->execute();
          $result = $stmt->rowCount();
          if ($result > 0) {
            echo "New hashtag, existing keyword inserted";
          }
          echo "New hashtag, error while existing keyword inserted";
        }
      }
		}
	}
}
else
{
	echo "Please Enter Keyword";
}
