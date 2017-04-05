<?php
$TITLE = "Dylan and Tony Fiesta - Home";
?>
<!DOCTYPE html>
<html lang="en">
	<?php require("partials/head.php"); ?>
<body>
	<?php require("partials/header.php"); ?>
	<div class="container">
		<div class="col-md-6 twitter feed">
			<div class="row">
				<div class="col-md-12">
					<h2 class="section-header">Twitter</h2>
				</div>
			</div>
			<div id="twitterFeed"></div>
		</div>
		<div class="col-md-6 instagram feed">
			<?php require("partials/instagram-feed/instagram-feed.php"); ?>
		</div>
	</div>
	<?php require("partials/footer.php"); ?>
</body>
</html>
