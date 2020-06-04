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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
?>
<div class="formapp">
  <form method="post" action="">
    <label for="appname">Application Name:</label>
    <input type="text" placeholder="New Application" name="appname">

    <label for="desc">Description:</label>
    <input type="text" placeholder="Description" name="desc">

    <button type="submit">Add</button>
  </form>
</div>
