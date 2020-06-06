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

  $bool = "'defaultOpen'";
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
  <button class="tablinks" onclick="openCity(event, 'Stats')">Stats</button>
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

<div id="Stats" class="tabcontent">
  <?php
	$daycount = 10;
	for ($x = 0; $x <= $daycount; $x++) {
		$today = date('Y-m-d', strtotime('-'.$x.' day'));
		$todayArr[] = $today;
		$today = $today."%";
		$sql = "SELECT COUNT(*) as count FROM consumer_results WHERE appid = :appid AND created_at LIKE :date";
		$stmt = $DB->prepare($sql);
		$stmt->bindValue(":date", $today);
    $stmt->bindValue(":appid", $appid);
		$stmt->execute();
		$result = $stmt->fetch();
		$count = $result["count"];
		$countArr[] = $count;
	};
  ?>
  <?php
    $like = 0;
    $dislike = 0;
    $sql = "SELECT * FROM consumers WHERE appid = :appid";
    $stmt = $DB->prepare($sql);
    $stmt->bindValue(":appid", $appid);
    $stmt->execute();
    foreach ($stmt->fetchAll() as $consid) {
      $consumer_id = $consid["id"];
      $sql = "SELECT likes, dislikes  FROM smarthashtags WHERE consumerid = :consumerid";
      $stmt = $DB->prepare($sql);
      $stmt->bindValue(":consumerid", $consumer_id);
      $stmt->execute();
      $rate = $stmt->fetch();
      $ratelike = $rate["likes"];
      $like += $ratelike;
      $ratedislike = $rate["dislikes"];
      $dislike += $ratedislike;
    }
  ?>
  <div class="graph"><div class="statHeader">Last 10 Day Daily Consumer Results:</div><canvas id="dailyConsumer"></canvas></div>
  <div class="graph"><div class="statHeader">Consumer Result Rates:</div><canvas id="consumerRate"></canvas></div>
  <div class="graph"><div class="statHeader">Keywords used by consumers:</div><div id="keyword">
    <?php
      $sql = "SELECT * FROM consumers WHERE appid = :appid";
      $stmt = $DB->prepare($sql);
      $stmt->bindValue(":appid", $appid);
      $stmt->execute();
      foreach ($stmt->fetchAll() as $consid) {
        $consumer_id = $consid["id"];
        $sql = "SELECT * FROM smarthashtags WHERE consumerid = :consumerid";
        $stmt = $DB->prepare($sql);
        $stmt->bindValue(":consumerid", $consumer_id);
        $stmt->execute();
        $result = $stmt->fetchAll();
        foreach ($result as $sh) {
          $shid = $sh["id"];
          $sql = "SELECT DISTINCT kwid FROM sh_kw WHERE shid = :shid";
          $stmt = $DB->prepare($sql);
          $stmt->bindValue(":shid", $shid);
          $stmt->execute();
          $sh_kw = $stmt->fetchAll();
          foreach ($sh_kw as $kw) {
            $kwid = $kw["kwid"];
            $sql = "SELECT keyword FROM keywords WHERE id = :kwid";
            $stmt = $DB->prepare($sql);
            $stmt->bindValue(":kwid", $kwid);
            $stmt->execute();
            $result = $stmt->fetch();
            echo "<span class='keyword'>".$result["keyword"]."</span>";
          }
        }
      }
    ?>
  </div></canvas></div>
  <div class="graph"><canvas id="myChart"></canvas></div>
  <script>
    var dailyConsumer = document.getElementById('dailyConsumer').getContext('2d');
    var chart = new Chart(dailyConsumer, {
      type: 'line',
      data: {
          labels: ['<?php echo $todayArr[9] ?>', '<?php echo $todayArr[8] ?>', '<?php echo $todayArr[7] ?>', '<?php echo $todayArr[6] ?>', '<?php echo $todayArr[5] ?>', '<?php echo $todayArr[4] ?>', '<?php echo $todayArr[3] ?>', '<?php echo $todayArr[2] ?>','<?php echo $todayArr[1] ?>','<?php echo $todayArr[0] ?>'],
          datasets: [{
            label: 'Last 10 Day Daily Consumer Results',
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
            data: [<?php echo $countArr[9] ?>, <?php echo $countArr[8] ?>, <?php echo $countArr[7] ?>, <?php echo $countArr[6] ?>, <?php echo $countArr[5] ?>, <?php echo $countArr[4] ?>, <?php echo $countArr[3] ?>, <?php echo $countArr[2] ?>,<?php echo $countArr[1] ?>,<?php echo $countArr[0] ?>]
          }]
        },
          options: {}
      });
    var consumerRate = document.getElementById('consumerRate').getContext('2d');
    var chart = new Chart(consumerRate, {
      type: 'pie',
      data: {
          labels: ['Like', 'Dislike'],
          datasets: [{
            label: 'Consumer Result Rates',
            backgroundColor: [
              '#366d38',
              '#BFCEB8',
            ],
            borderColor: '#366d38',
            data: [<?php echo $like; ?>, <?php echo $dislike; ?>]
          }]
        },
          options: {}
      });
</script>
</div>

<div id="Settings" class="tabcontent">
  <span class="error"> <?php echo $message;?></span>
  <div class="formapp">
    <form method="post" action="index.php?page=appdetail&appid=<?php echo $appid; ?>">
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
<?php if ($_SERVER["REQUEST_METHOD"] == "POST") {$bool = "'editOpen'";} else {$bool = "'defaultOpen'";} ?>
  document.getElementById(<?php echo $bool; ?>).click();

  $(document).ready(function () {
      $('button[type="submit"]').attr('disabled', true);
      $('input[type="text"],textarea').on('keyup', function () {
          var text_value = $('input[name="appname"]').val();
          var text_value2 = $('input[name="callbackurl"]').val();
          var text_value3 = $('input[name="desc"]').val();
          $('input[type="submit"]').attr('disabled', true);
          if (text_value != '' || text_value2 != '' || text_value3 != '') {
              $('button[type="submit"]').attr('disabled', false);
          } else {
              $('button[type="submit"]').attr('disabled', true);
          }
      });
  });
</script>
