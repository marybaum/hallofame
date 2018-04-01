jQuery(function( $ ){

	// Enable parallax and fade effects on homepage sections
	$(window).scroll(function(){

		scrolltop = $(window).scrollTop()
		scrollwindow = scrolltop + $(window).height();

		$(".home-section-1").css("backgroundPosition", "0px " + -(scrolltop/6) + "px");

		if( scrollwindow > $(".home-section-3").offset().top ) {

			// Enable parallax effect
			backgroundscroll = scrollwindow - $(".home-section-3").offset().top;
			$(".home-section-3").css("backgroundPosition", "0px " + -(backgroundscroll/6) + "px");

		}

		if( scrollwindow > $(".home-section-5").offset().top ) {

			// Enable parallax effect
			backgroundscroll = scrollwindow - $(".home-section-5").offset().top;
			$(".home-section-5").css("backgroundPosition", "0px " + -(backgroundscroll/6) + "px");

		}

	});

});