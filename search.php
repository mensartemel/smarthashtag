<?php
require_once 'src/twitter.class.php';
foreach($_SESSION['OAUTH_ACCESS_TOKEN'] as $result) {
	$accessToken = $result['value'];
	$accessTokenSecret = $result['secret'];
}
$consumerKey = "XvlI4EG7NjJbauQs4KK9JMzsA";
$consumerSecret = "fFuhGfvSZXRcS9GOruuv6xdBpRzYPEdfp2sAkyGt6PFcsTKg81";
// ENTER HERE YOUR CREDENTIALS (see readme.txt)
$twitter = new Twitter($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

// $results = $twitter->search('corona');
$results = $twitter->search(['q' => 'harvard', 'lang' => 'tr', 'result_type' => 'mixed', 'count' => '100']);

?>
<!doctype html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Twitter search demo</title>

<ul>
<?php foreach ($results as $status): ?>
	<li><a href="https://twitter.com/<?php echo $status->user->screen_name ?>"><img src="<?php echo htmlspecialchars($status->user->profile_image_url_https) ?>">
		<?php echo htmlspecialchars($status->user->name) ?></a>:
		<?php echo $status->user->description) ?>
		<small>at <?php echo date('j.n.Y H:i', strtotime($status->created_at)) ?></small>
	</li>
<?php endforeach ?>
</ul>
