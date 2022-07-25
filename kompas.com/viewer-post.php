<?php
/*
* Add function for counter viewer in single
*/
function mjlah_viewer_post() {
    //if single or page
    if (is_single() || is_page()):
        $key        = 'hit';  
        $post_id    = get_the_ID();
        
        if (!class_exists('WP_Statistics')) {
            $count      = (int) get_post_meta( $post_id, $key, true );    
            $count++;
        } else {
            global $wpdb;
            $table_name = $wpdb->prefix . "statistics_pages";
            $results    = $wpdb->get_results("SELECT sum(count) as result_value FROM $table_name WHERE id = $post_id");
            $count      = $results?$results[0]->result_value:'0';
        }

        update_post_meta( $post_id, $key, $count );
    endif;
}
add_action('wp_head', 'mjlah_viewer_post');
///function get viewer
function get_post_view() {
    $count = get_post_meta( get_the_ID(), 'hit', true );
    $count = $count > 0 ? $count : 0 ;
    return $count;
}
//shortcode [viewers]
function mjlah_postsviews() {
    global $post;
    ob_start();    
    $count = get_post_meta( $post->ID, 'hit', true );
    $count = $count > 0 ? $count : 0 ;
    echo $count.' Views';
    return ob_get_clean();
}
add_shortcode('viewers','mjlah_postsviews');






