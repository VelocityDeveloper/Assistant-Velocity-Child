<?php

//[the_title_primary]
add_shortcode('the_title_primary', 'vd_the_title_primary');
function vd_the_title_primary($atts){
    ob_start();
	global $post;
  
    $html = get_the_title(); 
  
    if(is_archive()) {
		$html = get_the_archive_title();
    }

    echo $html;

	return ob_get_clean();
}
