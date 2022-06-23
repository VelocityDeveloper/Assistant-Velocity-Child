<?php
//[vd-breadcrumbs]
add_shortcode('vd-breadcrumbs','vdget_breadcrumb');
function vdget_breadcrumb() {
    ob_start();
  
  	if( function_exists( 'aioseo_breadcrumbs' ) ):
  		echo do_shortcode('[aioseo_breadcrumbs]');
  	else:
    	echo justg_breadcrumb();
  	endif;
  
    return ob_get_clean();
}
