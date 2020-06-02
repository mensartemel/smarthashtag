<?php
require_once './config.php';
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
  // user already logged in the site
  header("location:login.php");
}
$profile_pic_temp = $_SESSION["picture"];
$profile_pic = substr($profile_pic_temp,0,-11);
$profile_bg = $_SESSION["bg-picture"];
?>
<!DOCTYPE html>
<html>
<head>
<title>SmartHashtag</title>
<link rel="stylesheet" href="bootstrap/css/bootstrap.css">
<link rel="stylesheet" href="style.css">
<style>
	.banner {
		background-image: url("<?php echo $profile_bg; ?>");
		background-size: cover;
		background-position: center;
	}
</style>
<script src="script.js"></script>
</head>
<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-2">
				<div class="banner">
					<img class="profile-img" src="<?php echo $profile_pic; ?>.jpg" alt="Profile Picture";>
					<a><?php echo $_SESSION["name"]; ?></a>
				</div>
				<div class="vertical-menu">
					<a href="index.php" class="menu-btn"><img class="menu-icon" src="img/icon/dashboard.png";>Dashboard</a>
					<a href="index.php?page=apps" class="menu-btn"><img class="menu-icon" src="img/icon/dashboard.png";>My Applications</a>
					<a href="index.php?page=service" class="menu-btn"><img class="menu-icon" src="img/icon/stats.png";>Services</a>
					<a href="index.php?page=search" class="menu-btn"><img class="menu-icon" src="img/icon/stats.png";>Search Example</a>
					<a href="index.php?page=settings" class="menu-btn"><img class="menu-icon" src="img/icon/settings.png";>Settings</a>
					<a href="logout.php" class="menu-btn bottom active"><img class="menu-icon" src="img/icon/logout.png";>Log Out</a>
				</div>
			</div>
			<div class="col-10">
				<?php if ($_SESSION["e_msg"] <> "") { ?>
    <div class="alert alert-dismissable alert-danger">
      <button data-dismiss="alert" class="close" type="button">x</button>
      <p><?php echo $_SESSION["e_msg"]; ?></p>
    </div>
  <?php } ?>
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
</body>
</html>
