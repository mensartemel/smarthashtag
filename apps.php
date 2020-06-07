<?php
function generateKey($length = 26) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
if (isset($_POST["addapp"])) {
  $twitter_id = $_SESSION["twitter_id"];
  $appname = $_POST["appname"];
  $desc = $_POST["desc"];
  $key = generateKey();

  $sql = "SELECT id from users where twitter_id = :id";
  $stmt = $DB->prepare($sql);
  $stmt->bindValue(":id", $twitter_id);
  $stmt->execute();
  $result = $stmt->fetch();
  $userid = $result["id"];

  $sql = "INSERT INTO `applications` (`appname`, `description`, `appkey`, `userid`) VALUES " . "( :appname, :description, :appkey, :userid)";
  $stmt = $DB->prepare($sql);
  $stmt->bindValue(":appname", $appname);
  $stmt->bindValue(":description", $desc);
  $stmt->bindValue(":appkey", $key);
  $stmt->bindValue(":userid", $userid);
  $stmt->execute();
  $_SESSION["e_msg"] = "Application created successfully!";
  header("location:index.php?page=apps");
}
if (isset($_POST["deleteapp"])) {
  $appid = $_POST["appid"];
  $sql = "CALL DeleteApp(:appid)";
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
  $count = $stmt->rowCount();
  $result = $stmt->fetchAll();
  foreach($result as $row){
    echo "<div class='app'>";
		echo "<a>".$row['appname']."</a>";
		echo "<div class='appdesc'>".$row['description']."</div>";
		echo "<a href='index.php?page=appdetail&appid=".$row['appid']."'>Show</a>";
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
document.getElementById("defaultOpen").click();
</script>
