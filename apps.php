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
<div class="app">
	<a>Appname</a>
	<p>Lorem ipsum dolor set amet</p>
	<img class="appedit" src="img/icon/stats.png" alt="Edit">
	<img class="appdetail" src="img/icon/stats.png" alt="Details">
</div>
