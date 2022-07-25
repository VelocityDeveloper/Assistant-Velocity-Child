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

//[vd-big-slidepost limit='4']
add_shortcode('vd-big-slidepost','vdget_big_slidepost');
function vdget_big_slidepost() {
    ob_start();
    $atribut = shortcode_atts( array(
        'limit'      => '4',
    ), $atts );
    $limit      = $atribut['limit'];
    $datapost   = [];

    //query
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $limit,
        'orderby' => 'date',
        'order' => 'DESC',
    );
    $the_query = new WP_Query( $args );
    // The Loop
    if ( $the_query->have_posts() ) {
        while ( $the_query->have_posts() ) {
            $the_query->the_post();
            $datapost[] = [
                'id'        => get_the_ID(),
                'title'     => get_the_title(),
                'permalink' => get_the_permalink(),
                'date'      => get_the_date(),
            ];
        }
    }
    /* Restore original Post Data */
    wp_reset_postdata();
    ?> 	
    <div class="vd-big-slidepost">
        <?php if($datapost): ?>
            <div class="vd-big-thumb">
                <?php foreach($datapost as $post): ?>
                    <div class="vd-big-thumb-item position-relative">
                        <div class="vd-big-thumb-img">
                            <?php echo do_shortcode('[ratio-thumbnail size="large" ratio="16:9" id="'.$post['id'].'"]'); ?>
                        </div>
                        <div class="vd-big-thumb-text">
                            <a class="vd-big-thumb-title text-white" href="<?php echo $post['permalink']; ?>" title="<?php echo $post['title']; ?>">
                                <?php echo $post['title']; ?>
                            </a>
                            <div class="vd-big-thumb-date">
                                <small>
                                <?php echo $post['date']; ?>
                                </small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>                                     
            </div> 
            <div class="vd-nav-thumb">
                <?php foreach($datapost as $post): ?>
                    <div class="vd-nav-thumb-item bg-dark text-white">
                        <div class="vd-nav-thumb-img">
                            <?php echo do_shortcode('[ratio-thumbnail linked="false" size="medium" ratio="3:2" id="'.$post['id'].'"]'); ?>
                        </div>
                        <div class="vd-nav-thumb-text p-2">
                            <div class="vd-nav-thumb-title">
                                <?php echo $post['title']; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>                                     
            </div> 
        <?php endif; ?>     
    </div>
    <?php
    return ob_get_clean();
}