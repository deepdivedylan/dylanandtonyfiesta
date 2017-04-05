$(document).ready(function() {

	$.get("/api/message/", function(reply) {
		$("#twitterFeed").html(reply);
	});
});