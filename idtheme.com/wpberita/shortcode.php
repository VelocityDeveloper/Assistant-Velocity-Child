<?php

//[vd-ticker-news limit=""]
add_shortcode('vd-ticker-news', 'vd_tickernews');
function vd_tickernews($atts){
    ob_start();
    $atribut = shortcode_atts( array(
        'limit'	=> '5', /// count limit
        'cat'	=> '', /// categori id
    ), $atts );
    $limit  = $atribut['limit'];   
    $cat  = $atribut['cat'];  
    
    // The Query
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $limit,
        'cat' => $cat
    );
    $the_query = new WP_Query( $args );

    // The Loop
    if ( $the_query->have_posts() ) {
        echo '<div class="vd-ticker-news overflow-hidden">';
            echo '<div class="ticker d-flex">';
                echo '<div class="ticker__list">';
                while ( $the_query->have_posts() ) {
                    $the_query->the_post();
                    echo '<div class="ticker__item">';
                        echo '<a href="' . get_the_permalink() . '">' . get_the_title() . '</a>';
                    echo '</div>';
                }
                echo '</div>';
            echo '</div>';
        echo '</div>';
    } else {
        // no posts found
    }
    /* Restore original Post Data */
    wp_reset_postdata();

	return ob_get_clean();
}