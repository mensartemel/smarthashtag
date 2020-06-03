<?php
if (isset($_POST["deleteapp"])) {
  $appid = $_POST["appid"];
  $sql = "DELETE FROM applications WHERE appid = :appid";
  $stmt = $DB->prepare($sql);
  $stmt->bindValue(":appid", $appid);
  $stmt->execute();
  $result = $stmt->rowCount();
  if ($result > 0) {
    $_SESSION["e_msg"] = "Application deleted successfully!";
    header("location:index.php?page=apps");
  }
  else {
    $_SESSION["e_msg"] = "An error occured while deleting!";
    header("location:index.php?page=apps");
  }
}
?>
<div class="panel panel-default" id="alert" onclick="hideContent()">
    <?php
			if ($_SESSION["e_msg"] != null) {
				echo "<div class='appalert'>".$_SESSION["e_msg"]."</div>";
				$_SESSION["e_msg"] = null;
			}
		?>
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
		echo "<a href='index.php?page=appdetail&appid=".$row['appid']."'><img class='appdetail' src='img/icon/stats.png' alt='Details'><div class='overlay'><div class='text'>Hello World</div></div></a>";
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
