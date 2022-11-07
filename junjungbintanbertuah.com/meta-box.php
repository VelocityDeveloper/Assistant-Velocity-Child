<?php

add_filter( 'rwmb_meta_boxes', 'vel_metabox' );
function vel_metabox( $meta_boxes ){
	$prefix = 'velbox_';
	$meta_boxes[] = array(
		'id'         => 'standard',
		'title'      => __( 'Velocity Fields', 'velbox' ),
		'post_types' => array( 'post'),
		'context'    => 'normal',
		'priority'   => 'high',
		'autosave'   => true,
		'fields'     => array(
			array(
				'name'  => __( 'Location', 'velbox' ),
				'id'    => "location",
				'type'  => 'text',
				'clone' => false,
			),
			array(
				'name'  => __( 'Price', 'velbox' ),
				'id'    => "price",
				'type'  => 'text',
				'clone' => false,
			),
			array(
				'name'             => __( 'Gallery', 'velbox' ),
				'id'               => "gallery",
				'type'             => 'image_advanced',
				//'max_file_uploads' => 4,
			),
			array(
				'name'    => __( 'Facilities', 'velbox' ),
				'id'      => "facilities",
				'type'    => 'wysiwyg',
				'raw'     => false,
				'options' => array(
					'textarea_rows' => 4,
					'teeny'         => true,
					'media_buttons' => false,
				),
			),
			array(
				'name'    => __( 'Itinerary', 'velbox' ),
				'id'      => "itinerary",
				'type'    => 'wysiwyg',
				'raw'     => false,
				'options' => array(
					'textarea_rows' => 4,
					'teeny'         => true,
					'media_buttons' => false,
				),
			),
		)
	);

	return $meta_boxes;
}


