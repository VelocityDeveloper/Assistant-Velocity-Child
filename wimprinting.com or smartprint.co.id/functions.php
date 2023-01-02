<?php

// Tambah custom modul
function vsstemmart_modul() {
	if ( class_exists( 'FLBuilder' ) ) {
      require_once( VELOCITY_TOKO_PLUGIN_DIR . 'modul/gallery-carousel/gallery-carousel.php' );
	}
}
add_action( 'init', 'vsstemmart_modul' );




// [cari]
function velocity_search(){
    $html = '<form class="vel-search" method="get" name="searchform" action="'.get_home_url().'">
        <input class="search-input" placeholder="Cari produk..." name="s" value="" type="text" required>
        <input class="search-button" value="" type="submit">
    </form>';
    return $html;
}
add_shortcode('cari', 'velocity_search');
