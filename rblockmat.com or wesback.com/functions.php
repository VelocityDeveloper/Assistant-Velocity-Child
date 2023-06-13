<?php

// Create new post type and taxonomy
add_action('init', 'velocity_admin_init');
function velocity_admin_init() {
    register_post_type('produk', array(
        'labels' => array(
            'name' => 'Produk',
            'singular_name' => 'produk',
            'add_new' => 'Tambah Produk Baru',
            'add_new_item' => 'Tambah Produk Baru',
            'edit_item' => 'Edit Produk',
            'view_item' => 'Lihat Produk',
            'search_items' => 'Cari Produk',
            'not_found' => 'Tidak ditemukan',
            'not_found_in_trash' => 'Tidak ada Produk di kotak sampah'
        ),
        'menu_icon' => 'dashicons-screenoptions',
        'public' => true,
        'has_archive' => true,
        'taxonomies' => array('kategori'),
        'supports' => array(
            'title',
            'editor',
            'thumbnail',
        ),
    ));
	register_taxonomy(
	'kategori',
	'produk',
	array(
		'label' => __( 'Kategori Produk' ),
		'rewrite' => array( 'slug' => 'kategori' ),
		'hierarchical' => true,
		'show_admin_column' => true,
	));
}

// Create custom taxonomy filter in admin menu
function velocity_add_taxonomy_filters() {
	global $typenow; 
	// an array of all the taxonomyies you want to display. Use the taxonomy name or slug
	$taxonomies = array('kategori'); 
	// must set this to the post type you want the filter(s) displayed on
	if( $typenow == 'produk' ){ 
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
add_action( 'restrict_manage_posts', 'velocity_add_taxonomy_filters' );


// custom meta box
add_filter( 'rwmb_meta_boxes', 'vel_metabox' );
function vel_metabox( $meta_boxes ){
	$textdomain = 'justg';
	$meta_boxes[] = array(
		'id'         => 'standard',
		'title'      => __( 'Velocity Fields', $textdomain ),
		'post_types' => array( 'produk' ),
		'context'    => 'normal',
		'priority'   => 'high',
		'autosave'   => true,
		'fields'     => array(
			array(
				'name'  => __( 'Harga', $textdomain ),
				'id'    => "harga",
				'type'  => 'number',
				'desc'  => __( 'Isi dengan angka saja, contoh: 150000', $textdomain ),
			),
			array(
				'name'  => __( 'Marketplace Link', $textdomain ),
				'id'    => "marketplace",
				'type'  => 'url',
				'desc'  => __( 'Isi dengan link Tokopedia produk ini.', $textdomain ),
			),
			array(
				'name'             => __( 'Foto Tambahan', $textdomain ),
				'id'               => "gallery",
				'type'             => 'image_advanced',
			),
		)
	);

	return $meta_boxes;
}



/* SHORTCODES */

add_shortcode('harga', 'velocity_harga');
function velocity_harga(){
	global $post;
	if (get_post_meta($post->ID, "harga", true)){
		$harga = get_post_meta( $post->ID, "harga", true );
		$harga = preg_replace('/[^0-9]/', '', $harga);
		$price = number_format( $harga ,0 , ',','.' );
        $html = 'Rp'.$price;	
	} else {
        $html = 'Rp.-';	
	}
    return $html;
}


add_shortcode('tombol-beli', 'velocity_tombol_beli');
function velocity_tombol_beli(){
	global $post;
    $mplink = get_post_meta($post->ID, "marketplace", true);
    $whatsapp = velocitytheme_option('whatsapp_number', '');
    $whatsapp_number = $whatsapp ? preg_replace('/[^0-9]/', '', $whatsapp) : $whatsapp;
    if (substr($whatsapp_number, 0, 1) == 0) {
        $wa    = substr_replace($whatsapp_number, '62', 0, 1);
    }
    $html = '';	
	if (!empty($whatsapp)){
        $wa_link = '';
        $iconwa = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp me-2" viewBox="0 0 16 16">
        <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
      </svg>';
        $html .= '<a class="btn btn-success s-whatsapp text-white py-2 px-4 me-2" href="'.$wa_link.'" target="_blank">'.$iconwa.'Whatsapp</a>';	
	}
	if (!empty($mplink)){
        $icon1 = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-shop me-2" viewBox="0 0 16 16">
        <path d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.371 2.371 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976l2.61-3.045zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0zM1.5 8.5A.5.5 0 0 1 2 9v6h1v-5a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v5h6V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5zM4 15h3v-5H4v5zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-3zm3 0h-2v3h2v-3z"/>
      </svg>';
        $html .= '<a class="btn btn-success text-white py-2 px-4" href="'.$mplink.'" target="_blank">'.$icon1.'Tokopedia</a>';	
	}
    return $html;
}



// [social-share]
function vel_social_buttons($content) {
    global $post,$wp;
    if(is_singular() || is_home()){
        $post_id = $post->ID;
		// Get current URL 
        $sb_url = urlencode(get_permalink($post_id));
		//$sb_url = home_url(add_query_arg(array($_GET), $wp->request));
 
        // Get current web title
        $sb_title = str_replace( ' ', '%20', get_the_title($post_id));
        //$sb_title = wp_title('',false);
         
        // Construct sharing URL without using any script
        $twitterURL = 'https://twitter.com/intent/tweet?text='.$sb_title.'&amp;url='.$sb_url;
        $facebookURL = 'https://www.facebook.com/sharer/sharer.php?u='.$sb_url;
        $linkedInURL = 'https://www.linkedin.com/shareArticle?mini=true&url='.$sb_url.'&amp;title='.$sb_title;
        $pinterestURL = 'https://pinterest.com/pin/create/button/?url='.$sb_url.'&amp;description='.$sb_title;
        $whatsappURL ='https://api.whatsapp.com/send?text='.$sb_title.' '.$sb_url;
        $telegramURL ='https://telegram.me/share/url?url='.$sb_url.'';
        $emailURL ='mailto:?subject=I wanted you to see this site&amp;body='.$sb_title.' '.$sb_url.' ';
        
        //get views and get shares
        //$countviews = get_post_meta($post_id, 'hit', true)?get_post_meta($post_id, 'hit', true):0;
        //$countshare = get_post_meta($post_id, 'post_share_count', true)?get_post_meta($post_id, 'post_share_count', true):0;
 
        // Add sharing button at the end of page/page content
        $content .= '<div class="social-box"><div class="mb-2">Bagikan ini:</div>';
        //$content .= '<div class="btn btn-sm btn-outline-info me-2 mb-1"><span id="datashare" class="font-weight-bold">'.$countshare.'</span> Shares</div>';
        //$content .= '<div class="btn btn-sm btn-outline-secondary me-2 mb-1"><span class="font-weight-bold">'.$countviews.'</span> Views</div>';
        $content .= '<a class="btn btn-sm btn-secondary me-2 mb-1 s-twitter postshare-button" href="'.$twitterURL.'" target="_blank" rel="nofollow" data-id="'.$post_id.'"><span><i class="fa fa-twitter" aria-hidden="true"></i></span></a>';
        $content .= '<a class="btn btn-sm btn-secondary me-2 mb-1 s-facebook postshare-button" href="'.$facebookURL.'" target="_blank" rel="nofollow" data-id="'.$post_id.'"><span><i class="fa fa-facebook-square" aria-hidden="true"></i></span></a>';
        $content .= '<a class="btn btn-sm btn-secondary me-2 mb-1 s-whatsapp postshare-button" href="'.$whatsappURL.'" target="_blank" rel="nofollow" data-id="'.$post_id.'"><span><i class="fa fa-whatsapp" aria-hidden="true"></i></span></a>';
        $content .= '<a class="btn btn-sm btn-secondary me-2 mb-1 s-pinterest postshare-button" href="'.$pinterestURL.'" data-pin-custom="true" target="_blank" rel="nofollow" data-id="'.$post_id.'"><span><i class="fa fa-pinterest" aria-hidden="true"></i></span></a>';
        $content .= '<a class="btn btn-sm btn-secondary me-2 mb-1 s-linkedin postshare-button" href="'.$linkedInURL.'" target="_blank" rel="nofollow" data-id="'.$post_id.'"><span><i class="fa fa-linkedin" aria-hidden="true"></i></span></a>';
        $content .= '<a class="btn btn-sm btn-info me-2 mb-1 s-telegram postshare-button" href="'.$telegramURL.'" target="_blank" rel="nofollow" data-id="'.$post_id.'"><span><i class="fa fa-telegram" aria-hidden="true"></i></span></a>';
        $content .= '<a class="btn btn-sm btn-secondary me-2 mb-1 s-email postshare-button" href="'.$emailURL.'" target="_blank" rel="nofollow" data-id="'.$post_id.'"><span><i class="fa fa-envelope-o" aria-hidden="true"></i></span></a>';
        $content .= '</div>';
        
        return $content;
    } else {
        // if not a post/page then don't include sharing button
        return $content;
    }
};
add_shortcode('social-share','vel_social_buttons');
