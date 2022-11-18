<?php

// jangan lupa buat folder modul yach
function vsstem_modul() {
	if ( class_exists( 'FLBuilder' ) ) {
	    get_template_part('modul/vel-gallery/vel-gallery');
	    get_template_part('modul/gallery-carousel/gallery-carousel');
	    get_template_part('modul/v-testimoni/v-testimoni');
	}
}
add_action( 'init', 'vsstem_modul' );
