
add_action('init', 'velocity_admin_init');
function velocity_admin_init() {
    register_post_type('armada', array(
        'labels' => array(
            'name' => 'Armada',
            'singular_name' => 'armada',
        ),
        'menu_icon' => 'dashicons-car',
        'public' => true,
        'has_archive' => true,
        'taxonomies' => array('kategori-armada'),
        'supports' => array(
            'title',
            'editor',
            'thumbnail',
        ),
    ));
	register_taxonomy(
	'kategori-armada',
	'armada',
	array(
		'label' => __( 'Kategori Armada' ),
		'rewrite' => array( 'slug' => 'kategori-armada' ),
		'hierarchical' => true,
		'show_admin_column' => true,
	));
}



function pippin_add_taxonomy_filters() {
	global $typenow; 
	// an array of all the taxonomyies you want to display. Use the taxonomy name or slug
	$taxonomies = array('kategori-armada'); 
	// must set this to the post type you want the filter(s) displayed on
	if( $typenow == 'armada' ){ 
		foreach ($taxonomies as $tax_slug) {
			$tax_obj = get_taxonomy($tax_slug);
			$tax_name = $tax_obj->labels->name;
			$terms = get_terms($tax_slug);
			if(count($terms) > 0) {
				echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
				echo "<option value=''>Semua $tax_name</option>";
				foreach ($terms as $term) { 
					echo '<option value='. $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>'; 
				}
				echo "</select>";
			}
		}
	}
}
add_action( 'restrict_manage_posts', 'pippin_add_taxonomy_filters' );


//metabox
function add_custom_meta_box() {
	$screens = array( 'armada' );
	foreach ( $screens as $screen ) {
		add_meta_box(
			'myplugin_sectionid',
			__( 'Detail Armada', 'velprodukdetail' ),
			'vel_meta_box_callback',
			$screen
		);
	}
}
add_action( 'add_meta_boxes', 'add_custom_meta_box' );


function vel_meta_box_callback( $post ) {

	wp_nonce_field( 'vel_metabox', 'myplugin_meta_box_nonce' );

	$harga = get_post_meta( $post->ID, 'harga', true );
	$lokasi = get_post_meta( $post->ID, 'lokasi', true );
	$fasilitas = get_post_meta( $post->ID, 'fasilitas', true );
	
	echo '<table class="form-table" role="presentation">';
	echo '<tbody><tr>';
	echo '<th><label for="vel_harga">';
	_e( 'Harga', 'detailanggota' );
	echo '</label></th>';
	echo '<td><input type="number" id="vel_harga" name="vel_harga" value="' . esc_attr( $harga ) . '" size="25" />
    <br/><small>Isikan angka saja tanpa karakter khusus, contoh: <b>450000</b></small></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th><label for="vel_lokasi">';
	_e( 'Lokasi', 'detailanggota' );
	echo '</label></th>';
	echo '<td><input type="text" id="vel_lokasi" name="vel_lokasi" value="' . esc_attr( $lokasi ) . '" size="25" /></td>';
	echo '</tr>';

	echo '<tr>';
	echo '<th><label for="vel_fasilitas">';
	_e( 'Fasilitas', 'detailanggota' );
	echo '</label></th>';
	echo '<td><input type="text" id="vel_fasilitas" name="vel_fasilitas" value="' . esc_attr( $fasilitas ) . '" size="25" />
    <br/><small>Pisahkan dengan tanda koma, contoh: <b>Driver,BBM,Food</b></small></td>';
	echo '</tr>';

	echo '</tbody></table>';

}


function vel_metabox( $post_id ) {

	if ( ! isset( $_POST['myplugin_meta_box_nonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( $_POST['myplugin_meta_box_nonce'], 'vel_metabox' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	if ( ! isset( $_POST['vel_harga'] ) ) {
		return;
	}
	if ( ! isset( $_POST['vel_lokasi'] ) ) {
		return;
	}
	if ( ! isset( $_POST['vel_fasilitas'] ) ) {
		return;
	}

	$vel_harga = sanitize_text_field( $_POST['vel_harga'] );
	$vel_lokasi = sanitize_text_field( $_POST['vel_lokasi'] );
	$vel_fasilitas = sanitize_text_field( $_POST['vel_fasilitas'] );

	// Update the meta field in the database.
	update_post_meta( $post_id, 'harga', $vel_harga );
	update_post_meta( $post_id, 'lokasi', $vel_lokasi );
	update_post_meta( $post_id, 'fasilitas', $vel_fasilitas);
}
add_action( 'save_post', 'vel_metabox' );




// [post-loop post_id='']
function velocity_armada_loop($atts) {
    global $post;
    $atribut = shortcode_atts(array(
        'post_id'     	=> $post->ID,
    ), $atts);
    $post_id = $atribut['post_id'];
	$harga = get_post_meta($post_id,'harga',true);
	$lokasi = get_post_meta($post_id,'lokasi',true);
	$fasilitas = get_post_meta($post_id,'fasilitas',true);
	$no_wa	= get_theme_mod('nomor_whatsapp',0);
        if (substr($no_wa, 0, 1) === '0') {
            $no_wa  = '62' . substr($no_wa, 1);
        } else if (substr($no_wa, 0, 1) === '+') {
            $no_wa  = '' . substr($no_wa, 1);
        }
        $no_wa  = str_replace(" ","",$no_wa);
    $html = '<div class="velocity-post-list text-center">';
		$html .= '<div class="velocity-post-top">';
			$thumb_url   = get_the_post_thumbnail_url($post_id, 'full');
			//$html	.= '<a href="'.get_the_permalink($post_id).'">';
				$html	.= '<div class="velocity-post-thumbnail" style="background-image:url('.$thumb_url.')"></div>';
			//$html	.= '</a>';
			$html .= '<div class="h6 fw-bold text-dark">'.get_the_title($post_id).'</div>';
		$html .= '</div>';
		$html .= '<div class="velocity-post-bottom">';
			$html .= '<div class="row m-0 py-2">';
				$html .= '<div class="col-6 p-0 pr-1">';
					if(!empty($harga)){				
						$harga = preg_replace('/[^0-9]/', '', $harga);
						$html .= '<div class="py-2 bgcolor-primary text-white w-100 btn-sm rounded-0"><i class="fa fa-money" aria-hidden="true"></i> Rp'.number_format($harga ,0 , ',','.' ).'</div>';
					}
				$html .= '</div>';
				$html .= '<div class="col-6 p-0 pl-1">';
					$html .= '<div class="py-2 bg-dark text-white w-100 btn-sm rounded-0"><i class="fa fa-map-o" aria-hidden="true"></i> '.$lokasi.'</div>';
				$html .= '</div>';
			$html .= '</div>';
			$html .= '<div class="pb-1 text-center">';
				if(!empty($fasilitas)){
					$facilities = explode(',',$fasilitas);
					foreach($facilities as $facility){
						$html .= '<small class="d-inline-block bg-success py-1 px-3 text-white me-1 mb-1"><i class="fa fa-check fa-lg me-2"></i>'.$facility.'</small>';
					}
				}
			$html .= '</div>';
			$html .= '<div class="wa-sewa">';
				$html .= '<a class="py-2 btn btn-secondary btn-sm rounded-0 w-100" href="https://wa.me/'.$no_wa.'?text=Hallo, Saya ingin menyewa '.get_the_title($post_id).'" target="_blank">
			<i class="fa fa-whatsapp" aria-hidden="true"></i> <span>SEWA SEKARANG</span></a>';
			$html .= '</div>';
		$html .= '</div>';
    $html .= '</div>';
    return $html;
}
add_shortcode('armada-loop', 'velocity_armada_loop');


