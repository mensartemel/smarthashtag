<?php
require('http.php');
require('oauth_client.php');
require('config.php');

if(isset($_GET['appkey'])) {
    $appkey = $_GET['appkey'];
} else {
    $appkey = $_SESSION["appkey"];
}

$sql = "SELECT appid from applications where appkey = :appkey";
$stmt = $DB->prepare($sql);
$stmt->bindValue(":appkey", $appkey);
$stmt->execute();
$result = $stmt->rowCount();

if ($result > 0) {

  $_SESSION["is_consumer"] = true;
  $_SESSION["appkey"] = $appkey;

  $stmt = $DB->prepare($sql);
  $stmt->bindValue(":appkey", $appkey);
  $stmt->execute();
  $result = $stmt->fetch();
  $appid = $result['appid'];

  $_SESSION["appid"] = $appid;

  $client = new oauth_client_class;
  $client->debug = 1;
  $client->debug_http = 1;
  $client->redirect_uri = REDIRECT_URL;

  $client->client_id = CLIENT_ID;
  $application_line = __LINE__;
  $client->client_secret = SECRET_KEY;

  if (strlen($client->client_id) == 0 || strlen($client->client_secret) == 0)
    die('Please go to Twitter Apps page https://dev.twitter.com/apps/new , ' .
            'create an application, and in the line ' . $application_line .
            ' set the client_id to Consumer key and client_secret with Consumer secret. ' .
            'The Callback URL must be ' . $client->redirect_uri . ' If you want to post to ' .
            'the user timeline, make sure the application you create has write permissions');

  if (($success = $client->Initialize())) {
    if (($success = $client->Process())) {
      if (strlen($client->access_token)) {
        $success = $client->CallAPI(
                'https://api.twitter.com/1.1/account/verify_credentials.json', 'GET', array(), array('FailOnAccessError' => true), $user);
      }
    }
    $success = $client->Finalize($success);
  }
  if ($client->exit)
    exit;

  $twUserData = array(
              'oauth_uid'   => $user->id,
              'name'    		=> $user->name,
  			      'username'    => $user->screen_name,
              'locale'      => $user->lang,
              'picture'     => $user->profile_image_url_https,
          );

  if ($success) {
    // Now check if consumer exist with same ID
    try {
      $sql = "SELECT id from consumers where twitter_id = :id";
      $stmt = $DB->prepare($sql);
      $stmt->bindValue(":id", $user->id);
      $stmt->execute();
      $result = $stmt->fetch();
      $consumerid = $result["id"];
      if ($consumerid > 0) {
        // User Exist
        $_SESSION["username"] = $user->screen_name;
        $_SESSION["consumerid"] = $consumerid;
        $_SESSION["twitter_id"] = $user->id;
        $_SESSION["name"] = $user->name;
        $_SESSION["picture"] = $user->profile_image_url;
        $_SESSION["cons_in"] = true;
      } else {
        // New user, Insert in database
        $sql = "INSERT INTO `consumers` (`twitter_id`, `appid`) VALUES " . "( :twitter_id, :appid)";
        $stmt = $DB->prepare($sql);
        $stmt->bindValue(":appid", $appid);
        $stmt->bindValue(":twitter_id", $user->id);
        $stmt->execute();
        $result = $stmt->rowCount();
        if ($result > 0) {
          $_SESSION["username"] = $user->screen_name;
          $_SESSION["consumerid"] = $userid;
          $_SESSION["twitter_id"] = $user->id;
          $_SESSION["name"] = $user->name;
          $_SESSION["picture"] = $user->profile_image_url;
          $_SESSION["cons_in"] = true;
          $_SESSION["e_msg"] = "";
        }
      }
    } catch (Exception $ex) {
      $_SESSION["e_msg"] = $ex->getMessage();
    }

    $_SESSION["user_id"] = $user->id;
  } else {
    $_SESSION["e_msg"] = $client->error;
  }
}
else {
  echo "No application found!";
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title><?php $_SESSION["name"] ?></title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../bootstrap/style.css">
    <script src="../bootstrap/jquery-3.5.1.min.js"></script>
    <script src="../bootstrap/script.js"></script>
  </head>
  <body>
    <div class="shform">
      <form name="add_keyword" id="add_keyword">
				<div class="table-responsive">
					<table class="table" id="dynamic_field">
						<tr>
							<td class="cl-input"><input type="text" name="keyword[]" placeholder="Keyword" class="form-control name_list" /><br></td>
              <td class="cl-chackbox"><input type="checkbox" id="profilesearch" name="profilesearch" value="true"><label for="profilesearch">Profile Search</label><br></td>
							<td class="cl-button"><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>
						</tr>
					</table>
          <span class="error" id="error"></span>
					<input type="button" name="submit" id="submit" class="btn btn-info" value="Submit" />
				</div>
			</form>
    </div>
  </body>
</html>
<script>
$(document).ready(function(){
	var i=1;
	$('#add').click(function(){
		i++;
    if (i <= 5) {
      $('#dynamic_field').append('<tr id="row'+i+'"><td class="cl-input"><input type="text" name="keyword[]" placeholder="Keyword" class="form-control name_list" /></td><td class="cl-chackbox"><input type="checkbox" id="profilesearch" name="profilesearch" value="true"><label for="profilesearch">Profile Search</label><br></td><td class="cl-button"><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');
    } else {
      document.getElementById("error").innerHTML = "You cannot add more than 5 keywords";
    }
	});

	$(document).on('click', '.btn_remove', function(){
		var button_id = $(this).attr("id");
		$('#row'+button_id+'').remove();
    i--;
	});

	$('#submit').click(function(){
		$.ajax({
			url:"name.php",
			method:"POST",
			data:$('#add_keyword').serialize(),
			success:function(data)
			{
				alert(data);
				$('#add_keyword')[0].reset();
			}
		});
	});

});
</script>
