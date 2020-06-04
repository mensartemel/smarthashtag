<?php
require('config.php');

$number = count($_POST["keyword"]);
if($number >= 1)
{
	for($i=0; $i<$number; $i++)
	{
		if(trim($_POST["keyword"][$i] != ''))
		{
      $sql = "SELECT * FROM keywords WHERE keyword ="." :keyword";
      $stmt = $DB->prepare($sql);
      $stmt->bindValue(":keyword", $_POST["keyword"][$i]);
      $stmt->execute();
      $result = $stmt->fetch();
      if ($result > 0) {
        // code...
      }
      else {
        $sql = "INSERT INTO keywords ('keyword') VALUES (:keyword)";
        $stmt = $DB->prepare($sql);
        $stmt->bindValue(":keyword", $_POST["keyword"][$i]);
        $stmt->execute();
        $result = $stmt->rowCount();
      }
		}
	}
}
else
{
	echo "Please Enter Keyword";
}
