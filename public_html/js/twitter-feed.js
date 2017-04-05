$(document).ready(function() {

	$.get("/api/message/", function(reply) {
		console.log(reply);
	});
});