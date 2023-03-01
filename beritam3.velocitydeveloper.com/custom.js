jQuery(function($) {    
  // Untuk shortcode cari
    $(".tombols").click(function() {
        var id = $(this).attr("id");
          $(".form-"+id).toggle();
          $("#"+id).toggleClass("collapsed");
    });
});
