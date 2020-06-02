<?php
  $dir = dirname(__FILE__);
  require_once $dir . '/src/config.php';
  //if ($_SESSION["twitter_id"] != true) {
  //  header("location:login.php");
  //}
  $profile_pic = $_SESSION["picture"];
  $name = $_SESSION["name"];
?>

<!DOCTYPE html>
<html>
<head>
<title>SmartHashtag</title>
<link rel="stylesheet" href="bootstrap/css/bootstrap.css">
<link rel="stylesheet" href="bootstrap/style.css">
<script src="bootstrap/script.js"></script>
</head>
<body>
  <div class="container-fluid">
		<div class="row">
			<div class="col-2">
				<div class="banner">
					<img class="profile-img" src="<?php echo $profile_pic; ?>" alt="Profile Picture";>
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
</body>
</html>
