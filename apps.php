<div class="panel panel-default" id="alert" onclick="hideContent()">
    <?php echo $_SESSION["e_msg"]; ?>
</div>
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
		echo "<div class='appdesc'>".$row['description']."</div>";
		echo "<a href='index.php?page=appdetail&appid=".$row['appid']."'><img class='appedit' src='img/icon/stats.png' alt='Edit'></a>";
		echo "<a href='index.php?page=appdetail&appid=".$row['appid']."'><img class='appdetail' src='img/icon/stats.png' alt='Details'></a>";
		echo "</div>";
  }
?>
<script>
function hideContent() {
	var x = document.getElementById("alert");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
</script>
