<?php
$TITLE = "Dylan and Tony Fiesta - Home";
?>
<!DOCTYPE html>
<html lang="en">
	<?php require("partials/head.php"); ?>
<body>
	<?php require("partials/header.php"); ?>
	<div class="container">
		<div id="twitterFeed" class="col-md-6 twitter feed">
		</div>
		<div class="col-md-6 instagram feed">
			<?php require("partials/instagram-feed/instagram-feed.php"); ?>
		</div>
	</div>
	<?php require("partials/footer.php"); ?>
</body>
</html>
