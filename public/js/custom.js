$(function() {

	$(document).click(function(event) {
		$(".dropdown-menu").removeClass("show");

	});

	$(".dropdown-toggle").on('click', function() {
		if ($(this).next().hasClass("show")) {
			$(this).next().removeClass("show");
		} else {
			$(this).next().addClass("show");
		}
		event.stopPropagation();
	})
});
