<?php

// buat manggil custom modul
function vsstem_modul() {
	if ( class_exists( 'FLBuilder' ) ) {
	    get_template_part('modul/post-info/post-info');
	}
}
add_action( 'init', 'vsstem_modul' );



// nambah custom post type blog
add_action('init', 'velocity_admin_init');
function velocity_admin_init() {
    register_post_type('blog', array(
        'labels' => array(
            'name' => 'Blog',
            'singular_name' => 'blog',
        ),
        'menu_icon' => 'dashicons-screenoptions',
        'public' => true,
        'has_archive' => true,
        'taxonomies' => array('blog-category'),
        'supports' => array(
            'title',
            'editor',
            'thumbnail',
        ),
    ));
	register_taxonomy(
	'blog-category',
	'blog',
	array(
		'label' => __( 'Blog Categories' ),
		'rewrite' => array( 'slug' => 'blog-category' ),
		'hierarchical' => true,
		'show_admin_column' => true,
	));
}



// custom meta box pakai plugin metabox.io
add_filter( 'rwmb_meta_boxes', 'vel_metabox' );
function vel_metabox( $meta_boxes ){
	$prefix = 'velbox_';
	$meta_boxes[] = array(
		'id'         => 'standard',
		'title'      => __( 'Velocity Fields', 'velbox' ),
		'post_types' => array( 'post' ),
		'context'    => 'normal',
		'priority'   => 'high',
		'autosave'   => true,
		'fields'     => array(
			array(
				'name'             => __( 'Tampilan', 'velbox' ),
				'id'               => "tampilan",
				'type'             => 'image_advanced',
				'desc'  => __( 'Isi dengan kode warna pada Description, contoh: <b>#fcf7f7</b>', 'velbox' ),
			),
			array(
				'name'  => __( 'Harga', 'velbox' ),
				'id'    => "harga",
				'type'  => 'text',
			),
			array(
				'name'  => __( 'Jumlah Kursi', 'velbox' ),
				'id'    => "kursi",
				'type'  => 'text',
			),
			array(
				'name'  => __( 'Mesin', 'velbox' ),
				'id'    => "mesin",
				'type'  => 'text',
			),
			array(
				'name'  => __( 'Tenaga', 'velbox' ),
				'id'    => "tenaga",
				'type'  => 'text',
			),
			array(
				'name'  => __( 'Transmisi', 'velbox' ),
				'id'    => "transmisi",
				'type'  => 'text',
			),
			array(
				'name'  => __( 'Gardan', 'velbox' ),
				'id'    => "gardan",
				'type'  => 'text',
			),
			array(
				'name'  => __( 'Bahan Bakar', 'velbox' ),
				'id'    => "bahan_bakar",
				'type'  => 'text',
			),
			array(
				'name'             => __( 'Gallery', 'velbox' ),
				'id'               => "gallery",
				'type'             => 'image_advanced',
			),
		)
	);

	return $meta_boxes;
}
