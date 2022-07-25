jQuery(function($) {
    jQuery(document).ready(function ($) {
        if ($(window).width() > 768){
            const HeaderHeight = $('header.fl-builder-content').height();
            const HeaderTop = $('header.fl-builder-content');
            $(window).scroll(function(){
                if( $(window).scrollTop() > HeaderHeight ) {
                    HeaderTop.addClass('scrolled');
                    $('body').addClass('page-is-scrolled');
                } else {
                    HeaderTop.removeClass('scrolled');
                    $('body').removeClass('page-is-scrolled');
                }
            });
        }
    });
    jQuery('.vd-big-thumb').ready(function ($) {
        $('.vd-big-thumb').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: true,
            dots: false,
            asNavFor: '.vd-nav-thumb'
        });
        $('.vd-nav-thumb').slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            asNavFor: '.vd-big-thumb',
            dots: false,
            arrows: false,
            centerMode: false,
            focusOnSelect: true,
            infinite: true,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 4,
                        infinite: true,
                        arrows: false
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 3,
                        infinite: true,
                        arrows: true
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 2,
                        infinite: true,
                        arrows: true
                    }
                },
            ],
        });
    });
});