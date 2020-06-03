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
?>
<div class="tab">
  <button class="tablinks" onclick="openCity(event, 'Details')" id="defaultOpen">App Details</button>
  <button class="tablinks" onclick="openCity(event, 'Services')">Services</button>
  <button class="tablinks" onclick="openCity(event, 'Settings')">Settings</button>
</div>

<div id="Details" class="tabcontent">
  <?php
    $appid = $_GET['appid'];
    $userid = $_SESSION['userid'];
    $sql = "SELECT * FROM applications WHERE appid = :appid";
    $stmt = $DB->prepare($sql);
    $stmt->bindValue(":appid", $appid);
    $stmt->execute();
    $result = $stmt->fetch();
    echo "Consumer URL:</br>";
    echo "https://smarthashtag.herokuapp.com/src/consumer.php?appkey=".$result["appkey"]."</br>";
    $sql = "SELECT COUNT(*) AS count FROM consumers WHERE appid = :appid";
    $stmt = $DB->prepare($sql);
    $stmt->bindValue(":appid", $appid);
    $stmt->execute();
    $result = $stmt->fetch();
    echo "Consumers: ".$result['count']."";
    $sql = "SELECT COUNT(*) AS count FROM consumer_results WHERE appid = :appid";
    $stmt = $DB->prepare($sql);
    $stmt->bindValue(":appid", $appid);
    $stmt->execute();
    $result = $stmt->fetch();
    echo "Consumer Results: ".$result['count']."";
  ?>
</div>

<div id="Services" class="tabcontent">
  <a>This is the app services page.</a>
</div>

<div id="Settings" class="tabcontent">
  <a>This is the app settings page.</a>
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

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
</script>
