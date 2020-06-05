<?php
$betaErr = $beta = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["betacode"])) {
    $betaErr = "Betocode is required";
  } else {
    $beta = test_input($_POST["betacode"]);
    // check if name only contains letters and whitespace
    if ($beta != "estu2020") {
      $betaErr = "The code you entered does not match";
    } else {
		header("location:index.php?page=apps");
	}
  }
}
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Login via Twitter</title>
<link rel="stylesheet" href="bootstrap/css/bootstrap.css">
<link rel="stylesheet" href="bootstrap/style.css">
<script src="bootstrap/script.js"></script>
</head>
<body>
<div class="login-page">
  <div class="form">
    <form method="post" action="">
      <label for="appname">Beta Code:</label></br><span class="error"><?php echo $betaErr;?></span>
      <input type="text" placeholder="New Application" name="betacode">
      <button class="appform" type="submit">Login via Twitter</button>
    </form>
  </div>
</div>
</body>
</html>
