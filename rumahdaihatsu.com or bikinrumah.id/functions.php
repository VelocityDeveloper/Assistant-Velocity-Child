
function vsstem_modul() {
	if ( class_exists( 'FLBuilder' ) ) {
	    get_template_part('modul/vel-gallery/vel-gallery');
	    get_template_part('modul/gallery-carousel/gallery-carousel');
	}
}
add_action( 'init', 'vsstem_modul' );


// [cari]
function cariform() {
$html = '<div class="vel-cari">
   <span class="tombols"></span>
   <form action="'.get_home_url().'" method="get" id="formsearchvel" style="display: none;">
	<input class="search-input" name="s" placeholder="Search.." type="text">
	<button class="search-button" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
   </form>
</div>';
return $html;
}
add_shortcode ('cari', 'cariform');




// Custom meta box
add_action("admin_init", "admin_init");
function admin_init(){
	add_meta_box("harga", "Harga", "harga_function", "post", "side", "low");
}
function harga_function(){
	global $post;
	$custom = get_post_custom($post->ID);
	$harga = $custom["harga"][0]; ?>
	<label>Harga:</label>
	<input name="harga" value="<?php echo $harga; ?>" />
<?php }
add_action('save_post', 'save_details');
function save_details(){
	global $post;
	update_post_meta($post->ID, "harga", $_POST["harga"]);
}
