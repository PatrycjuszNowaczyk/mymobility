jQuery( document ).ready( function( $ ) {
	
    menu = $('nav.main > div').html();
    $('body').append('<div id="mobile-menu"><div class="content">'+menu+'</div><a class="menu-link" href="#menu-link" rel="nofollow"><span></span></a></div>');
//	$('#mobile-menu div > ul').append($('#menu-spolecznosciowe-ikonki').html());
//	var social = $('.menu-social-container').html();
//	$('#mobile-menu div > ul').append('<li class="lang">' + lang + '</li>');


	$(document).on('click','.menu-link', function(e) {
		e.preventDefault();
		$('.menu-link').toggleClass('active'); 
		$('header.main nav.main ul').toggleClass('active');
		$('body').toggleClass('mobile-menu-active');		
		$('body').toggleClass('shadow');
	});


});
