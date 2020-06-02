<?php
  require_once 'config.php';
  //if ($_SESSION["twitter_id"] != true) {
  //  header("location:login.php");
  //}
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
					<img class="profile-img" src="pic.jpg" alt="Profile Picture";>
					<a>User</a>
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
        <?php
        	$sql = "SELECT * FROM `users`";
        	$stmt = $DB->prepare($sql);
        	$stmt->execute();
        	while ($row = $stmt->fetch()) {
        		echo $row['username']."</br>";
            echo $row['twitter_id']."</br>";
            echo $row['name']."</br>";
            echo $row['picture'];
        	}
        ?>
    </div>
			</div>
		</div>

	</div>
</body>
</html>
