 
function vsstem_modul() {
	if ( class_exists( 'FLBuilder' ) ) {
	    get_template_part('modul/v-post-grid/v-post-grid');
	    get_template_part('modul/post-tabs/post-tabs');
	}
}
add_action( 'init', 'vsstem_modul' );


 // [cari]
function cariform() {
    $id = rand(9,9999);
    $html = '<div class="vel-cari position-relative">
       <span class="tombols" id="'.$id.'"></span>
       <form action="'.get_home_url().'" method="get" class="form-'.$id.'" id="formsearchvel" style="display: none;">
        <input class="search-input" name="s" placeholder="Search.." type="text" required>
        <button class="search-button" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
       </form>
    </div>';
    return $html;
}
add_shortcode ('cari', 'cariform');



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
	   $jml = '0';
   } else {
	   $jml = $view;
   }
   return $jml;
}
add_shortcode( 'hits', 'hits' );



// [social-share]
function vel_social_buttons($content) {
    global $post,$wp;
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
        $content .= '<div class="social-box"><span class="mb-1 mb-sm-0 me-2 d-block d-sm-inline-block">Bagikan:</span>';
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
		$args['posts_per_page'] = 4;
		$args['post__not_in'] = array ( $idp );
	}
	$wpex_query = new wp_query( $args );
	if($wpex_query->have_posts ()):
	echo '<div class="row">';
		while($wpex_query->have_posts()): $wpex_query->the_post(); ?>
		<div class="col-md-6 mb-3">
        <div class="row">
            <div class="col-4 pe-0">
                <div class="fl-post-image">
                    <?php echo do_shortcode('[resize-thumbnail width="300" height="250" linked="true" class="w-100 mb-2"]'); ?>
                </div>
            </div>

            <div class="fl-post-text col-8">
			    <div class="fw-bold mb-2"><a class="text-dark" href="<?php echo get_the_permalink($post->ID); ?>"><b><?php echo get_the_title($post->ID); ?></b></a></div>
                <div class="text-uppercase text-muted">
                    <small><i class="fa fa-clock-o"></i> <?php echo get_the_date('F j, Y',$post->ID);?></small>
                </div>
            </div>
        </div>
		</div>
	<?php endwhile;
	echo '</div>';
	endif;
	wp_reset_postdata();
	return ob_get_clean();
}
add_shortcode('related-post', 'relatedpost');
