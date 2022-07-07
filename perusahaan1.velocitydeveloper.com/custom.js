jQuery(function($) {
    if($('.widget_recent_entries .post-date').length !== 0){
      $('.widget_recent_entries ul').addClass('ul_widget_recent_entries');
      $('.widget_recent_entries ul > li').each(function(ind,el){
          var math = Math.floor(100000 + Math.random() * 900000);
            $(this).addClass('image-post image-post-'+math);
          var title = $(this).find('a').html();
          $.ajax({url: opt.restUrl+'wp/v2/posts?search='+title, success: function(result){
            if(typeof result[0].featured_media !== 'undefined') {
              $.ajax({url: opt.restUrl+'wp/v2/media/'+result[0].featured_media, success: function(data){
                $('.image-post-'+math).addClass('image-post-available').prepend('<img src="'+data.media_details.sizes.thumbnail.source_url+'" />');
              }});
            }
          }});
      });
    }
    if($('.wp-block-gallery .wp-block-image').length !== 0){
      var myLightbox = GLightbox({
          'selector': '.wp-block-image img'
      });
    }
  });