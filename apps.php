<a href="index.php?page=addapp">Add Application</a>
</br>
<?php
	$userid = 1;
  $sql = "SELECT * from applications where userid = :id";
  $stmt = $DB->prepare($sql);
  $stmt->bindValue(":id", $userid);
  $stmt->execute();
  $result = $stmt->fetchAll();
  foreach($result as $row){
    echo "<h5>".$row["appname"]."</h5>";
    echo "</br>";
    echo "<p>".$row["description"]."</p>";
    echo "</br>";
  }
?>
