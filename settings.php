<?php
$emailErr = "";
$email = $update = "";
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (empty($_POST["email"])) {
    $emailErr = "Email is required";
  } else {
    $email = test_input($_POST["email"]);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email format";
    }
    else {
      $sql = "UPDATE users SET email = :email, name = :name, username = :username, picture = :picture WHERE id = :userid";
      $stmt = $DB->prepare($sql);
      $stmt->bindValue(":email", $email);
      $stmt->bindValue(":userid", $_SESSION["userid"]);
      $stmt->bindValue(":name", $_SESSION["name"]);
      $stmt->bindValue(":username", $_SESSION["username"]);
      $stmt->bindValue(":picture", $_SESSION["picture"]);
      $stmt->execute();
      $result = $stmt->rowCount();
      if ($result > 0) {
        $update = "Changes successfully saved";
      } else {
        $update = "An error occurred while saving changes";
      }
    }
  }
}
?>
<form method="post" action="index.php?page=settings">
  <span class="error"> <?php echo $update;?></span>
  <label for="appname">E-Mail:</label><span class="error"> <?php echo $nameErr;?></span>
  <input type="text" placeholder="example@example.com" name="email">
  <button class="appform" type="submit">Apply</button>
</form>
