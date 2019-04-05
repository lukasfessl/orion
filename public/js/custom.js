$(function() {
	$(document).click(function(event) {
		$(".dropdown-menu" ).hide();

	});

	$(".dropdown-toggle").click(function(event ) {
		$(".dropdown-menu" ).toggle();
		event.stopPropagation();
	});

});

