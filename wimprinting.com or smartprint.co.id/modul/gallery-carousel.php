<?php

/**
 * @class vFLGalleryCarousel
 */
class vFLGalleryCarousel extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(array(
			'name'          	=> __( 'Gallery Carousel', 'fl-builder' ),
			'description'   	=> __( 'Display Gallery carousel by Velocity Developer.', 'fl-builder' ),
			'category'      	=> __( 'Media', 'fl-builder' ),
			'editor_export' 	=> false,
			'partial_refresh'	=> true,
			'icon'				=> 'format-gallery.svg',
		));
		$this->add_styles_scripts();
	}
	
	/**
	 * @method add_styles_scripts()
	 */
	public function add_styles_scripts() {
		$this->add_js( 'jquery-wookmark' );
		$this->add_js( 'jquery-mosaicflow' );
		$this->add_js( 'imagesloaded' );

		$override_lightbox = apply_filters( 'fl_builder_override_lightbox', false );
		if ( ! $override_lightbox ) {
			$this->add_js( 'jquery-magnificpopup' );
			$this->add_css( 'jquery-magnificpopup' );
		} else {
			wp_dequeue_script( 'jquery-magnificpopup' );
			wp_dequeue_style( 'jquery-magnificpopup' );
		}
	}

}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module('vFLGalleryCarousel', array(
	'layout'        => array(
		'title'         => __( 'Images ', 'fl-builder' ),
		'sections'      => array(
			'content'       => array(
				'title'         => __( 'Images', 'fl-builder' ),
				'fields'        => array(
                    'gallery'    => array(
                        'type'          => 'multiple-photos',
                        'label'         => __('Gallery', 'fl-builder')
                    ),
					'style'   => array(
						'type'    => 'select',
						'label'   => __( 'Select Style', 'fl-builder' ),
						'default' => 'style1',
						'options' => array(
							'style1' => __( 'Style 1', 'fl-builder' ),
							'style2' => __( 'Style 2', 'fl-builder' ),
						),
					),
				),
			),
		),
	),
));
