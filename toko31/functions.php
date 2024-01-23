<?php


// TAMBAHAN SHORTCODE

add_shortcode('tombol-detail-beli', function($atts) {
	global $post;
    $atribut = shortcode_atts( array(
        'post_id' 	=> $post->ID
    ), $atts );
    $post_id = $atribut['post_id'];	
    $wa = velocitytheme_option('whatsapp_number', '');
	$html = '<div class="row">';    
        $html .= '<div class="col-7 pe-1 text-end">';
            $html .= '<a class="btn btn-sm btn-primary rounded-0 px-3" href="'.get_the_permalink($post_id).'">Detail</a>';
        $html .= '</div>';
        $html .= '<div class="col-5 ps-1 text-start">';
            $html .= do_shortcode('[beli post_id="'.$post_id.'"]');
        $html .= '</div>';
	$html .= '</div>';    
    return $html;
});
