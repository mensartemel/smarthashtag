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
$nameErr = $descErr = $urlErr = "";
$name = $desc = $url = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (empty($_POST["appname"])) {
    $nameErr = "Name is required";
  } else {
    $name = test_input($_POST["name"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
      $nameErr = "Only letters and white space allowed";
    }
  }

  if (empty($_POST["callbackurl"])) {
    $urlErr = "Callback URL is required";
  } else {
    $url = test_input($_POST["callbackurl"]);
    // check if URL address syntax is valid
    if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$url)) {
      $urlErr = "Invalid URL";
    }
  }

  if (empty($_POST["desc"])) {
    $descErr = "Description is required";
  } else {
    $desc = test_input($_POST["desc"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z ]*$/",$desc)) {
      $descErr = "Only letters and white space allowed";
    }
  }
  if ($nameErr == $descErr == $urlErr == "") {
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
}
?>
<div class="formapp">
  <form method="post" action="">
    <label for="appname">Application Name:</label><span class="error">* <?php echo $nameErr;?></span>
    <input type="text" placeholder="New Application" name="appname">
    <label for="appname">Callbaclk URL:</label><span class="error">* <?php echo $nameErr;?></span>
    <input type="text" placeholder="http://..." name="callbackurl">
    <label for="desc">Description:</label><span class="error">* <?php echo $nameErr;?></span>
    <input type="text" placeholder="Description" name="desc">

    <button class="appform" type="submit">Add</button>
  </form>
</div>
