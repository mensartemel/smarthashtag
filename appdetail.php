<?php
  $appid = $_GET['appid'];
  $userid = $_SESSION['userid'];
  $sql = "SELECT * FROM applications, users WHERE applications.appid = :appid AND users.id = :userid AND applications.userid = users.id";
  $stmt = $DB->prepare($sql);
  $stmt->bindValue(":appid", $appid);
  $stmt->bindValue(":userid", $userid);
  $stmt->execute();
  $result = $stmt->rowCount();
  if ($result == 0) {
    $_SESSION["e_msg"] = "Application not found!";
    header("location:index.php?page=apps");
  }

  $sql = "SELECT * FROM applications WHERE appid = :appid";
  $stmt = $DB->prepare($sql);
  $stmt->bindValue(":appid", $appid);
  $stmt->execute();
  $result = $stmt->fetch();
  $appkey = $result["appkey"];
  $appname = $result["appname"];
  $appdesc = $result["description"];
  $appurl = $result["callbackurl"];

  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  $nameErr = $descErr = $urlErr = "";
  $name = $desc = $url = "";
  $message = "";

  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["appname"])) {
      $name = $appname;
    } else {
      $name = test_input($_POST["appname"]);
      // check if name only contains letters and whitespace
      if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
        $nameErr = "Only letters and space allowed";
      }
    }

    if (empty($_POST["callbackurl"])) {
      $url = $appurl;
    } else {
      $url = test_input($_POST["callbackurl"]);
      // check if URL address syntax is valid
      if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$url)) {
        $urlErr = "Invalid URL";
      }
    }

    if (empty($_POST["desc"])) {
      $desc = $appdesc;
    } else {
      $desc = test_input($_POST["desc"]);
      // check if name only contains letters and whitespace
      if (!preg_match("/^[a-zA-Z ]*$/",$desc)) {
        $descErr = "Only letters and white space allowed";
      }
    }
    if ($nameErr == "" && $urlErr == "" && $descErr == "") {
      $twitter_id = $_SESSION["twitter_id"];
      $sql = "UPDATE applications SET appname = :appname, description = :appdesc, callbackurl = :appurl WHERE appid = :appid";
      $stmt = $DB->prepare($sql);
      $stmt->bindValue(":appname", $name);
      $stmt->bindValue(":appdesc", $desc);
      $stmt->bindValue(":appurl", $url);
      $stmt->bindValue(":appid", $appid);
      $stmt->execute();
      $count = $stmt->rowCount();
      if ($count > 0) {
        $message = "Changes successfully saved";
      }
      else {
        $message = "An error occurred while saving changes";
      }
    }
  }
?>
<div class="tab">
  <button class="tablinks" onclick="openCity(event, 'Details')" id="defaultOpen">App Details</button>
  <button class="tablinks" onclick="openCity(event, 'Services')">Services</button>
  <button class="tablinks" onclick="openCity(event, 'Settings')" id="editOpen">Settings</button>
</div>

<div id="Details" class="tabcontent">
  <?php
    echo "Consumer URL:</br>";
    echo "https://smarthashtag.herokuapp.com/src/consumer.php?appkey=".$appkey."</br>";
    $sql = "SELECT COUNT(*) AS count FROM consumers WHERE appid = :appid";
    $stmt = $DB->prepare($sql);
    $stmt->bindValue(":appid", $appid);
    $stmt->execute();
    $result = $stmt->fetch();
    echo "Consumers: ".$result['count']."</br>";
    $sql = "SELECT COUNT(*) AS count FROM consumer_results WHERE appid = :appid";
    $stmt = $DB->prepare($sql);
    $stmt->bindValue(":appid", $appid);
    $stmt->execute();
    $result = $stmt->fetch();
    echo "Consumer Results: ".$result['count']."</br>";
  ?>
</div>

<div id="Services" class="tabcontent">
  <a>This is the app services page.</a>
</div>

<div id="Settings" class="tabcontent">
  <span class="error"> <?php echo $message;?></span>
  <div class="formapp">
    <form method="post" action="">
      <label for="appname">Application Name:</label><span class="error"> <?php echo $nameErr;?></span>
      <input type="text" placeholder="<?php echo $appname; ?>" name="appname">
      <label for="appname">Callbaclk URL:</label><span class="error"> <?php echo $urlErr;?></span>
      <input type="text" placeholder="<?php echo $appurl; ?>" name="callbackurl">
      <label for="desc">Description:</label><span class="error"> <?php echo $descErr;?></span>
      <input type="text" placeholder="<?php echo $appdesc; ?>" name="desc">
      <button class="appform" type="submit">Change</button>
    </form>
  </div>
</div>

<script>
function openCity(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}
<?php if ($_SERVER["REQUEST_METHOD"] == "POST") {$bool = true;} else {$bool = false;} ?>
var bool = <?php echo $bool; ?>;
if (bool) {
  document.getElementById("editOpen").click();
}
document.getElementById("defaultOpen").click();

</script>
