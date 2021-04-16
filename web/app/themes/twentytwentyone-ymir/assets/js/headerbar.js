(function($) {
    $(document).ready(function() {
        $(window).on('scroll', function () {
            if ($(window).scrollTop() > 400)
                $('#headerbar').animate({'height': '91px'}, 500);
            else
                $('#headerbar').stop(true).animate({'height': '0'}, 500);
        });
    });
})(jQuery);
