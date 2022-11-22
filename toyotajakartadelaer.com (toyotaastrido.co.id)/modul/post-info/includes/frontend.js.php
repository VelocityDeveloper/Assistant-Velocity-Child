    jQuery(function ($) {
        $('.id-<?php echo $id;?>').slick({
          slidesToShow: 1,
          slidesToScroll: 1,
          asNavFor: '.navid-<?php echo $id;?>',
          autoplay: false,
          arrows: true,
          //appendDots: '.navid-<?php echo $id;?>',
          //dots: true,
        });
        $('.navid-<?php echo $id;?>').slick({
          slidesToShow: 20,
          slidesToScroll: 1,
          asNavFor: '.id-<?php echo $id;?>',
          focusOnSelect: true,
          autoplay: false,
          responsive: [
            {
              breakpoint: 1024,
              settings: {
                slidesToShow: 20,
              }
            },
            {
              breakpoint: 600,
              settings: {
                slidesToShow: 10,
              }
            },
            {
              breakpoint: 480,
              settings: {
                slidesToShow: 5,
              }
            }
          ],
        });
		$('.post-gallery-<?php echo $id;?>').magnificPopup({
		  delegate: 'a', // child items selector, by clicking on it popup will open
		  type: 'image',
      gallery:{
        enabled:true
      }
		  // other options
		});

    
});