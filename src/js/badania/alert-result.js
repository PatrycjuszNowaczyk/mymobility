$('document').ready(function() {
    $(document).on('click', '#page-badanie .result-hide span', function(e) {
        const result = $(this).closest('.result');
        result.toggleClass('active');
        $('html, body').animate({
            scrollTop: result.offset().top - 120	
        }, 500);
    });

    $(document).on('click', '#page-badanie .result-show span', function(e) {
        const result = $(this).closest('.result');
        result.toggleClass('active');
    });
});