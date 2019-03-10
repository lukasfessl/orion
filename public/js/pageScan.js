$(function() {

	$('#scanPage').on('click', function() {

		$.get("/settings/tile/scan", {
			url: $("#tile_link").val()
		})
		.done(function(data) {
			$("#tile_name").val(data["title"])
			$("#tile_icon").val(data["favicon"])
		});

		return false;
	});

});