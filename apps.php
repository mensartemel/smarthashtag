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
<div class="app">
	<a>Appname</a>
	<div class="appdesc">Lorem ipsum dolor set amet</div>
	<img class="appedit" src="img/icon/stats.png" alt="Edit">
	<img class="appdetail" src="img/icon/stats.png" alt="Details">
</div>
