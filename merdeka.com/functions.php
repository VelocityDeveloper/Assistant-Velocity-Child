
function vsstem_modul() {
	if ( class_exists( 'FLBuilder' ) ) {
	    get_template_part('modul/vrunning-post/vrunning-post');
	    get_template_part('modul/vpost-carousel/vpost-carousel');
	}
}
add_action( 'init', 'vsstem_modul' );



// Update jumlah pengunjung dengan plugin WP-Statistics
function velocity_allpage() {
    global $wpdb,$post;
    $postID = $post->ID;
    $count_key = 'hit';
    if(empty($post))
    return false;
    $table_name = $wpdb->prefix . "statistics_pages";
    $results    = $wpdb->get_results("SELECT sum(count) as result_value FROM $table_name WHERE id = $postID");
    $count = $results?$results[0]->result_value:'0';
    if($count=='') {
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    } else {
        update_post_meta($postID, $count_key, $count);
    }
}
add_action( 'wp', 'velocity_allpage' );

// Menampilkan jumlah pengunjung: [hits]
function hits($atts) {
   global $post;
    $atribut = shortcode_atts( array(
        'post_id'     => $post->ID,
    ), $atts );
    $post_id	    = $atribut['post_id'];
   $view = get_post_meta($post_id,'hit',true);
   if(empty($view)){
	   $jml = '0 Dilihat';
   } else {
	   $jml = $view.' Dilihat';
   }
   return $jml;
}
add_shortcode( 'hits', 'hits' );



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
        $content .= '<div class="social-box">';
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



// [related-post]
function relatedpost(){
	ob_start();
	$idp = get_the_ID();
	$cats = wp_get_post_terms( get_the_ID(), 'category' ); 
	$cats_ids = array();  
	foreach( $cats as $wpex_related_cat ) {
		$cats_ids[] = $wpex_related_cat->term_id; 
	}
	if ( ! empty( $cats_ids ) ) {
		$args['category__in'] = $cats_ids;
		$args['posts_per_page'] = 3;
		$args['post__not_in'] = array ( $idp );
	}
	$wpex_query = new wp_query( $args );
	if($wpex_query->have_posts ()):
	echo '<div class="row">';
		while($wpex_query->have_posts()): $wpex_query->the_post(); ?>
		<div class="col-md-4 col-sm-6 mb-3">
			<?php echo do_shortcode('[resize-thumbnail width="300" height="200" linked="true" class="w-100 mb-2 rounded"]'); ?>
			<div class="fw-bold"><a class="text-dark" href="<?php echo get_the_permalink($post->ID); ?>"><?php echo get_the_title($post->ID); ?></a></div>
			<div class="text-secondary"><small><?php echo do_shortcode('[tanggal-pos waktu="no"]');?></small></div>
		</div>
	<?php endwhile;
	echo '</div>';
	endif;
	wp_reset_postdata();
	return ob_get_clean();
}
add_shortcode('related-post', 'relatedpost');




// Tanggal Pos
function tglpos($atts) {
ob_start();
global $post;
$atribut = shortcode_atts( array(
    'post_id' => $post->ID,
    'hari' => 'yes',
    'waktu' => 'yes',
), $atts );
$post_id = $atribut['post_id'];
$att_hari = $atribut['hari'];
$att_waktu = $atribut['waktu'];
	$day = get_the_date('N',$post_id);
	$tgl = get_the_date('j',$post_id);
	$month = get_the_date('n',$post_id);
	$year = get_the_date('Y',$post_id);
	$hari = array ( 1 =>    'Senin',
				'Selasa',
				'Rabu',
				'Kamis',
				'Jumat',
				'Sabtu',
				'Minggu'
			);			
	$bulan = array (1 =>   'Januari',
				'Februari',
				'Maret',
				'April',
				'Mei',
				'Juni',
				'Juli',
				'Agustus',
				'September',
				'Oktober',
				'November',
				'Desember'
			);
  if($att_hari == 'yes'){
    $tampil_hari = $hari[$day].', ';
  } else {
    $tampil_hari = '';
  }
  if($att_waktu == 'yes'){
    $tampil_waktu = ' '.get_the_date('h:i',$post_id).' WIB';
  } else {
    $tampil_waktu = '';
  }
  
	echo $tampil_hari.$tgl.' '.$bulan[$month].' '.$year.$tampil_waktu;
	return ob_get_clean();
}
add_shortcode ('tanggal-pos', 'tglpos');

