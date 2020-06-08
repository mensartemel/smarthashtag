<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once '../src/config.php';

  $consumer_twitterid = $GET_["id"];

  $sql = "SELECT id FROM consumers WHERE twitter_id = :twitter_id";
  $stmt = $DB->prepare($sql);
  $stmt->bindValue(":twitter_id", $consumer_twitterid);
  $stmt->execute();
  $result = $stmt->fetch();
  $consumerid = $result["id"];

  $sql = "SELECT * FROM consumer_results WHERE consumerid = :consumerid";
  $stmt = $DB->prepare($sql);
  $stmt->bindValue(":consumerid", $consumerid);
  $stmt->execute();
  $result = $stmt;

  $num = $result->rowCount();
  // Check if any posts
  if($num > 0) {
    // Post array
    $posts_arr = array();
    // $posts_arr['data'] = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $post_item = array(
        'resultid' => $row["resultid"],
        'screenname' => $row["screenname"],
        'picture' => $row["picture"],
        'status' => $row["status"],
        'created_at' => $row["created_at"],
      );

      // Push to "data"
      array_push($posts_arr, $post_item);
      // array_push($posts_arr['data'], $post_item);
    }

    // Turn to JSON & output
    echo json_encode($posts_arr);

  } else {
    // No Posts
    echo json_encode(
      array('message' => 'No Posts Found')
    );
  }
