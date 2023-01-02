jQuery(function($) {
//$('.images-<?php echo $id; ?>').slickLightbox();
$('.images-<?php echo $id; ?>').slick({
  dots: false,
  infinite: false,
  arrows: true,
  speed: 300,
  slidesToShow: 5,
  slidesToScroll: 5,
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 5,
        slidesToScroll: 5,
        infinite: true,
        dots: true
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 3
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
});
});




(function($) {

	$(function() {
		if (typeof $.fn.magnificPopup !== 'undefined') {
			$('.images-<?php echo $id; ?>').magnificPopup({
				delegate: '.image-list a',
				closeBtnInside: false,
				type: 'image',
				gallery: {
					enabled: true,
					navigateByImgClick: true,
				},
				image: {
					titleSrc: 'title' 
					// this tells the script which attribute has your caption
				}
			});
		}
	});

})(jQuery);
