<h1>Settings</h1>
<p>Welcome <?php echo $_SESSION["name"]; ?>.</p>
</br>
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
