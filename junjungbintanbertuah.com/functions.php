<?php

// Buat folder modul ya bun lalu custom modulnya ditaruh disana
function vsstem_modul() {
	if ( class_exists( 'FLBuilder' ) ) {
	    get_template_part('modul/vel-gallery/vel-gallery');
	    get_template_part('modul/post-info/post-info');
	    get_template_part('modul/gallery-carousel/gallery-carousel');
	}
}
add_action( 'init', 'vsstem_modul' );
