<?php
	$userid = $_SESSION["userid"];
  $sql = "SELECT * from applications where userid = :id";
  $stmt = $DB->prepare($sql);
  $stmt->bindValue(":id", $userid);
  $stmt->execute();
  $result = $stmt->fetchAll();
  foreach($result as $row){
    echo "<div class='app'>";
		echo "<a>".$row['appname']."</a>";
		echo "<div class='appdesc'>".$row['description']."</div>";
		echo "<a href='index.php?page=appdetail&appid=".$row['appid']."'><img class='appedit' src='img/icon/stats.png' alt='Edit'></a>";
		echo "<a href='index.php?page=appdetail&appid=".$row['appid']."'><img class='appdetail' src='img/icon/stats.png' alt='Details'></a>";
		echo "</div>";
  }
?>
