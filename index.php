<?php
  $dir = dirname(__FILE__);
  require_once $dir . '/src/config.php';
  if ($_SESSION["logged_in"] != true) {
    header("location:login.php");
  }
  $profile_pic = $_SESSION["picture"];
  $name = $_SESSION["name"];
?>
<!DOCTYPE html>
<html>
<head>
<title>SmartHashtag</title>
<link rel="stylesheet" href="bootstrap/css/bootstrap.css">
<link rel="stylesheet" href="bootstrap/style.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="bootstrap/script.js"></script>
</head>
<body>
  <div class="container-fluid">
		<div class="row">
			<div class="col-2">
				<div class="banner">
					<img class="profile-img" src="<?php echo $profile_pic; ?>" alt="Profile Picture";>
          <a>Welcome</a></br>
					<a><?php echo $name; ?></a>
				</div>
				<div class="vertical-menu">
					<a href="index.php" class="menu-btn"><img class="menu-icon" src="img/icon/dashboard.png";>Dashboard</a>
					<a href="index.php?page=apps" class="menu-btn"><img class="menu-icon" src="img/icon/dashboard.png";>My Applications</a>
					<a href="index.php?page=services" class="menu-btn"><img class="menu-icon" src="img/icon/stats.png";>Services</a>
					<a href="index.php?page=search" class="menu-btn"><img class="menu-icon" src="img/icon/stats.png";>Search Example</a>
					<a href="index.php?page=settings" class="menu-btn"><img class="menu-icon" src="img/icon/settings.png";>Settings</a>
					<a href="logout.php" class="menu-btn bottom active"><img class="menu-icon" src="img/icon/logout.png";>Log Out</a>
				</div>
			</div>
			<div class="col-10">
        <div class="header">
          <?php
            if(isset($_GET['page']))
            {
              if ($_GET['page'] == "apps") {
                echo "<div class='title'><a>Applications</a></div><div class='titleside'><a class='button' href='index.php?page=addapp'>Add Application</a></div>";
              }
              elseif ($_GET['page'] == "addapp") {
                echo "<div class='title'><a>Add New Application</a></div>";
              }
              elseif ($_GET['page'] == "services") {
                echo "<div class='title'><a>Services</a></div>";
              }
              elseif ($_GET['page'] == "settings") {
                echo "<div class='title'><a>Settings</a></div>";
              }
              elseif ($_GET['page'] == "appdetail") {
                $appid = $_GET['appid'];
                $sql = "SELECT * from applications where appid = :id";
                $stmt = $DB->prepare($sql);
                $stmt->bindValue(":id", $appid);
                $stmt->execute();
                $result = $stmt->fetch();
                $appname = $result["appname"];
                $appid = $result["appid"];
                echo "<div class='title'><a>".$appname." Details</a></div><div class='titleside'><button id='myBtn' type='submit' name='deleteapp' class='deleteapp'>Delete</button></div>";
              }
              else {
                echo "<div class='title'><a>Unnamed Title</a></div>";
              }
            }
            else
            {
              echo "<div class='title'><a>Dashboard</a></div>";
            }
          ?>
        </div>
        <div class="content">
          <?php
  					if(isset($_GET['page']))
  					{
  						$page = $_GET['page'];
  						$display = $page.'.php';
  						include($display);
  					}
  					else
  					{
  						$display = 'dashboard.php';
  						include($display);
  					}
  				?>
        </div>
    </div>
			</div>
		</div>

	</div>
<div id="myModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <span class="close">&times;</span>
      <h2>Warning</h2>
    </div>
    <div class="modal-body">
      <a>If you delete this application, all consumers and data will be deleted. This action can't be undone.</a>
    </div>
    <div class="modal-footer">
      <?php
      $appid = $_GET['appid'];
      $sql = "SELECT * from applications where appid = :id";
      $stmt = $DB->prepare($sql);
      $stmt->bindValue(":id", $appid);
      $stmt->execute();
      $result = $stmt->fetch();
      $appname = $result["appname"];
      $appid = $result["appid"];
      echo "<div class='titleside buttondanger'><form action='index.php?page=apps' method='post'><input name='appid' type='hidden' value='".$appid."'><button type='submit' name='deleteapp' class='deleteapp'>I understand, delete anyway</button></form></div>";
      ?>
    </div>
  </div>
</div>
</body>
</html>
<script>
var modal = document.getElementById("myModal");
var btn = document.getElementById("myBtn");
var span = document.getElementsByClassName("close")[0];
btn.onclick = function() {
  modal.style.display = "block";
}
span.onclick = function() {
  modal.style.display = "none";
}
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>
