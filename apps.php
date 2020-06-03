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
		echo "<div class='appdesc'>".$row['description']."</div>";
		echo "<a href='index.php?page=appdetail&appid=".$row['appid']."'>Show</a>";
		echo "</div>";
  }
?>
<div id="myModal2" class="modal2">
  <div class="modal-content2">
    <div class="modal-header2">
      <span class="close2">&times;</span>
      <h2>Add Application</h2>
    </div>
    <div class="modal-body2">
      <form method="post" action="index.php?page=apps">
      <input type="text" class="appname" placeholder="New Application" name="appname">
      <input type="text" class="appinfo" placeholder="Description" name="desc">
      <button type="submit" class="addapp">Add</button>
    </div>
  </div>
</div>
<script>
var modal2 = document.getElementById("myModal2");
var btn2 = document.getElementById("myBtn2");
var span2 = document.getElementsByClassName("close2")[0];
btn2.onclick = function() {
  modal2.style.display = "block";
}
span2.onclick = function() {
  modal2.style.display = "none";
}
window.onclick = function(event) {
  if (event.target == modal2) {
    modal2.style.display = "none";
  }
}
</script>
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
