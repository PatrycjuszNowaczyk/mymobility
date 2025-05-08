$('document').ready(function() {

	$('.steps-nav li a').click(function(e){
		e.preventDefault();
	});

	$('.accordions-item h3').click(function(e){
		$(this).closest('.accordions-item').toggleClass('active'); 
	});


	function scroll_header() { 
		const heightSlider = 120;	 

		$(window).scroll(function(){
			if ($(window).scrollTop() > heightSlider){ 
				$('header.main').addClass('scroll');
			}
			if ($(window).scrollTop() < heightSlider){
				$('header.main').removeClass('scroll');
			}
		});
	}
	
	scroll_header();

	$( window ).resize(function() { 
		scroll_header(); 
	});

	$('#publikacje .line-btn a.link-more').click(function(e) {
		e.preventDefault();
		$(this).closest('.item').addClass('active');
	});

	$('#publikacje .line-btn a.link-less').click(function(e) {
		e.preventDefault();
		let link = $(this);
		link.closest('.item').removeClass('active');
		$('html, body').animate({
			scrollTop: link.closest('.item').offset().top - 120	
		}, 500);		
	});
});
