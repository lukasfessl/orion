$(function() {
	$(document).click(function(event) {
		$(".dropdown-menu" ).hide();
		$(".sub-nav").hide();
	});

	$(".dropdown-toggle").click(function(event ) {
		$(".dropdown-menu" ).toggle();
		event.stopPropagation();
	});


	$(".button-sub-nav").click(function(event) {
		$(this).next().show();
		console.log($(this).parent());
		event.preventDefault();
		return false;
	});

});

