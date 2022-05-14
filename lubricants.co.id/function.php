<?php

//[vd-page-title]
add_shortcode('vd-page-title', 'vd_page_title');
function vd_page_title($atts){
    ob_start();
  
  	if(is_archive()) {
		echo get_the_archive_title();
    } else {
		echo get_the_title();
    }
  
	return ob_get_clean();
}

//[vd-breadcrumbs]
add_shortcode('vd-breadcrumbs', 'vd_pagebreadcrumbs');
function vd_pagebreadcrumbs($atts){
    ob_start();
  
	echo '<div class="vd-breadcrumbs">';
  		if( function_exists( 'aioseo_breadcrumbs' ) ) {
        	echo do_shortcode('[aioseo_breadcrumbs]');
  		} else {
			echo justg_breadcrumb();
  		}
	echo '</div>';
  
	return ob_get_clean();
}
