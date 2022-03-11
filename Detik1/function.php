<?php
/**
 * Child theme functions
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development
 * and http://codex.wordpress.org/Child_Themes), you can override certain
 * functions (those wrapped in a function_exists() call) by defining them first
 * in your child theme's functions.php file. The child theme's functions.php
 * file is included before the parent theme's file, so the child theme
 * functions would be used.
 *
 * Text Domain: justg
 * @link http://codex.wordpress.org/Plugin_API
 *
 */

/**
 * Load other required files
 *
 */
// require_once('inc/meta-box.php');

/**
 * Load the parent style.css file
 *
 * @link http://codex.wordpress.org/Child_Themes
 */
if( ! function_exists( 'justg_child_enqueue_parent_style') ) {
	function justg_child_enqueue_parent_style() {
		// Dynamically get version number of the parent stylesheet (lets browsers re-cache your stylesheet when you update your theme)
		$parenthandle = 'parent-style'; 
        $theme = wp_get_theme();
		
		// Load the stylesheet
        wp_enqueue_style( $parenthandle, get_template_directory_uri() . '/style.css', 
            array(),  // if the parent theme code has a dependency, copy it to here
            $theme->parent()->get('Version')
        );
        
        $css_version = $theme->parent()->get('Version') . '.' . filemtime( get_stylesheet_directory() . '/css/custom.css' );
        // wp_enqueue_style( 'slick-style', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css', $css_version);
        // wp_enqueue_style( 'slick-style-theme', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css', $css_version);
        wp_enqueue_style( 'custom-style', get_stylesheet_directory_uri() . '/css/custom.css', 
            array(),  // if the parent theme code has a dependency, copy it to here
            $css_version
        );
        
        wp_enqueue_style( 'child-style', get_stylesheet_uri(),
            array( $parenthandle ),
            $theme->get('Version')
        );
        
        $js_version = $theme->parent()->get('Version') . '.' . filemtime( get_stylesheet_directory() . '/js/custom.js' );
        // wp_enqueue_script( 'slick-scripts', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js', array(), $js_version, true );
        wp_enqueue_script( 'justg-custom-scripts', get_stylesheet_directory_uri() . '/js/custom.js', array(), $js_version, true );

	}
}

add_action( 'after_setup_theme', 'velocitychild_theme_setup', 9 );

function velocitychild_theme_setup() {
	
	// Load justg_child_enqueue_parent_style after theme setup
	add_action( 'wp_enqueue_scripts', 'justg_child_enqueue_parent_style', 20 );
	
	if (class_exists('Kirki')):
		
		/**
		* Customizer control in child themes
		* Sample Panel
		* 
		*/ 
		Kirki::add_panel('panel_home', [
			'priority'    => 10,
			'title'       => esc_html__('Home', 'justg'),
			'description' => esc_html__('', 'justg'),
		]);

		/**
		* Sample section
		* 
		*/ 
		Kirki::add_section('home_slider', [
			'panel'    => 'panel_home',
			'title'    => __('Slider', 'justg'),
			'priority' => 10,
		]);

		/**
		* Sample Field
		* 
		*/ 
		// Kirki::add_field( 'justg_config', [
		// 	'type'        => 'repeater',
		// 	'label'       => esc_html__( 'Slider Home', 'justg' ),
		// 	'section'     => 'home_slider',
		// 	'priority'    => 10,
		// 	'row_label' => [
		// 		'type'  => 'text',
		// 		'value' => esc_html__( 'Slide', 'justg' ),
		// 	],
		// 	'button_label' => esc_html__('Tambah Slide', 'justg' ),
		// 	'settings'     => 'home_slider_setting',
		// 	'fields' => [
		// 		'image' => [
		// 			'type'        => 'image',
		// 			'label'       => esc_html__( 'Gambar', 'justg' ),
		// 			'description' => esc_html__( 'gunakan gambar dengan ukuran sama', 'justg' ),
		// 			'default'     => '',
		// 		],
		// 		'link_url'  => [
		// 			'type'        => 'text',
		// 			'label'       => esc_html__( 'Url slide', 'justg' ),
		// 			'description' => esc_html__( 'link saat gambar di klik', 'justg' ),
		// 			'default'     => '',
		// 		],
		// 	]
		// ] );
		
	endif;
}

/*
* Add function for counter viewer in single
*/
function mjlah_viewer_post() {
    //if single or page
    if (is_single() || is_page()):
        $key        = 'post_views_count';
        
        if (!class_exists('WP_Statistics')) {
            $post_id    = get_the_ID();
            $count      = (int) get_post_meta( $post_id, $key, true );    
            $count++; 
        } else {
            global $post,$wpdb;
            $table_name = $wpdb->prefix . "statistics_pages";
            $results    = $wpdb->get_results("SELECT sum(count) as result_value FROM $table_name WHERE id = $post->ID");
            $count      = $results?$results[0]->result_value:'0';
        }
         
        update_post_meta( $post_id, $key, $count );
    endif;
}
add_action('wp_head', 'mjlah_viewer_post');
///function get viewer
function get_post_view() {
    $count = get_post_meta( get_the_ID(), 'post_views_count', true );
    $count = $count > 0 ? $count : 0 ;
    return $count;
}
//shortcode [vd-viewers]
function mjlah_postsviews() {
    global $post;
    ob_start();    
    $count = get_post_meta( $post->ID, 'post_views_count', true );
    $count = $count > 0 ? $count : 0 ;
    echo $count.' Views';
    return ob_get_clean();
}
add_shortcode('vd-viewers','mjlah_postsviews');

///set column to dashboard
// function mjlah_posts_column_views( $columns ) {
//     $columns['post_views'] = 'Views';
//     return $columns;
// }
// function mjlah_posts_custom_column_views( $column ) {
//     if ( $column === 'post_views') {
//         echo get_post_view();
//     }
// }
// add_filter( 'manage_posts_columns', 'mjlah_posts_column_views' );
// add_action( 'manage_posts_custom_column', 'mjlah_posts_custom_column_views' );
// add_filter( 'manage_page_posts_columns', 'mjlah_posts_column_views' );
// add_action( 'manage_page_posts_custom_column', 'mjlah_posts_custom_column_views' );

//[resize-thumbnail width="300" height="150" linked="true" class="w-100"]
add_shortcode('resize-thumbnail', 'resize_thumbnail');
function resize_thumbnail($atts) {
    ob_start();
	global $post;
    $atribut = shortcode_atts( array(
        'output'	=> 'image', /// image or url
        'width'    	=> '300', ///width image
        'height'    => '150', ///height image
        'crop'      => 'false',
        'upscale'   => 'true',
        'linked'   	=> 'true', ///return link to post	
        'class'   	=> 'w-100', ///return class name to img	
    ), $atts );

    $output			= $atribut['output'];
    $width          = $atribut['width'];
    $height         = $atribut['height'];
    $crop           = $atribut['crop'];
    $upscale        = $atribut['upscale'];
    $linked        	= $atribut['linked'];
    $class        	= $atribut['class']?'class="'.$atribut['class'].'"':'';
	$urlimg			= get_the_post_thumbnail_url($post->ID,'full');

	if($urlimg):
		$urlresize      = aq_resize( $urlimg, $width, $height, $crop, true, $upscale );
		if($output=='image'):
			if($linked=='true'):
				echo '<a href="'.get_the_permalink($post->ID).'" title="'.get_the_title($post->ID).'">';
			endif;
			echo '<img src="'.$urlresize.'" width="'.$width.'" height="'.$height.'" loading="lazy" '.$class.'>';
			if($linked=='true'):
				echo '</a>';
			endif;
		else:
			echo $urlresize;
		endif;
	else:
		echo '<img src="https://via.placeholder.com/'.$width.'x'.$height.'?text=no+image" width="'.$width.'" height="'.$height.'" loading="lazy" '.$class.'>';
	endif;

	return ob_get_clean();
}

//[excerpt count="150"]
add_shortcode('excerpt', 'vd_getexcerpt');
function vd_getexcerpt($atts){
    ob_start();
	global $post;
    $atribut = shortcode_atts( array(
        'count'	=> '150', /// count character
    ), $atts );

    $count		= $atribut['count'];
    $excerpt	= get_the_content();
    $excerpt 	= strip_tags($excerpt);
    $excerpt 	= substr($excerpt, 0, $count);
    $excerpt 	= substr($excerpt, 0, strripos($excerpt, " "));
    $excerpt 	= ''.$excerpt.'...';

    echo $excerpt;

	return ob_get_clean();
}

//[vd-breadcrumbs]
add_shortcode('vd-breadcrumbs','vdget_breadcrumb');
function vdget_breadcrumb() {
    ob_start();    
    echo justg_breadcrumb();
    return ob_get_clean();
}

//[vd-post-tag]
add_shortcode('vd-post-tag', 'vd_posttag');
function vd_posttag($atts){
    ob_start();
	global $post;
    $atribut = shortcode_atts( array(
        'idpost' => '', /// count character
    ), $atts );

    $idpost = $atribut['idpost']?$atribut['idpost']:$post->ID;
    
    echo '<div>';
        $terms_tag = get_the_terms( $idpost , 'post_tag' );
        if($terms_tag):
            foreach($terms_tag as $term):
                echo '<span class="btn btn-sm btn-secondary mb-1 mr-1">';
                    echo $term->name;
                echo '</span>';
            endforeach;
        endif;
    echo '</div>';
    
	return ob_get_clean();
}

///wpcf7 captcha
if (class_exists('WPCF7')) { 
    add_action( 'wpcf7_init', 'custom_add_form_tag_bws_google_captcha' ); 
    function custom_add_form_tag_bws_google_captcha() {
        wpcf7_add_form_tag( 'bws_google_captcha', 'custom_bws_google_captcha_tag_handler' ); 
    } 
    function custom_bws_google_captcha_tag_handler( $tag ) {
        return do_shortcode('[bws_google_captcha]');
    }
}

//[bigheadline-archive]
add_shortcode('bigheadline-archive', 'vd_bigheadline_archive');
function vd_bigheadline_archive($atts){
    ob_start();
	global $post;
    $atribut = shortcode_atts( array(
        'count'	=> '3', /// count character
    ), $atts );

    $count		= $atribut['count'];
    $getcat     = get_queried_object();
    $getcatid   = !empty($getcat)&&isset($getcat->term_id)?$getcat->term_id:'';
    
    // The Query
    $argsx = array(
        'post_type' => 'post',
        'cat' => $getcatid,
        'posts_per_page'=> 1
    );
    $the_query = new WP_Query( $argsx );
    ?>
    <div class="bigheadline-archive">
        <?php if ( $the_query->have_posts() ):?>
            <div class="bigheadline-archive-loop">
                <?php while ( $the_query->have_posts() ): $the_query->the_post(); ?>
                    <div <?php post_class('bigheadline-item'); ?>>
                        <div class="position-relative">
                            <div class="bigheadline-img image-zoom">
                                <?php echo do_shortcode('[resize-thumbnail width="700" height="390" linked="true" class="w-100"]'); ?>
                            </div>
                            <div class="bigheadline-text">
                                <div class="bigheadline-tag">
                                    <small>
                                    <?php 
                                    $terms_tag = get_the_terms( get_the_ID() , 'post_tag' );
                                    if($terms_tag):
                                        echo $terms_tag[0]->name;
                                    endif;
                                    ?>
                                    </small>
                                </div> 
                                <a href="<?php echo get_the_permalink(); ?>" class="bigheadline-title font-weight-bold">
                                    <?php echo get_the_title(); ?>
                                </a>
                                <div class="bigheadline-info">
                                    <small>
                                        <span class="bigheadline-term">
                                            <?php 
                                            $terms_cat = get_the_terms( get_the_ID() , 'category' );
                                            if($terms_cat):
                                                echo $terms_cat[0]->name;
                                            endif;
                                            ?>
                                        </span> | 
                                        <span class="bigheadline-date">
                                            <?php echo get_the_date(); ?>
                                        </span>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php
                    $terms          = get_the_terms( get_the_ID(), 'category' );
                    $term_list      = wp_list_pluck( $terms, 'slug' );
                    $related_args   = array(
                    	'post_type' => 'post',
                    	'posts_per_page' => 2,
                    	'post_status' => 'publish',
                    	'post__not_in' => array( get_the_ID() ),
                    	'orderby' => 'rand',
                    	'tax_query' => array(
                    		array(
                    			'taxonomy' => 'category',
                    			'field' => 'slug',
                    			'terms' => $term_list
                    		)
                    	)
                    );
                    $rel_query = new WP_Query( $related_args );
                    if ( $rel_query->have_posts() ) {
                        echo '<div class="bigheadline-archive-related">';
                            echo '<div class="bg-primary p-3 text-white">';
                                echo '<div class="bigheadline-arel-headline text-warning font-weight-bold mb-2">Berita terkait</div>';
                                echo '<div class="row">';
                                    while ( $rel_query->have_posts() ): $rel_query->the_post();
                                        echo '<div class="col-md-6 bigheadline-arel-item mb-1">';
                                            echo '<a class="text-white" href="'.get_the_permalink().'">';
                                                echo get_the_title();
                                            echo '</a>';
                                        echo '</div>';
                                    endwhile;
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    }
                    ?>
                    
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
    /* Restore original Post Data */
    wp_reset_postdata();
	return ob_get_clean();
}
