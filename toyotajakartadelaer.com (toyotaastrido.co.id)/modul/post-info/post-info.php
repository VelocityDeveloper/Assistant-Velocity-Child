<?php


class FLvPostInfo extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(array(
			'name'          	=> __('Post Info', 'fl-builder'),
			'description'   	=> __('Post Info by Velocity Developer', 'fl-builder'),
			'category'      	=> __('Media', 'fl-builder'),
			'editor_export' 	=> false,
			'partial_refresh'	=> true
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
FLBuilder::register_module('FLvPostInfo', array(
	'slider'      => array(
		'title'         => __('No Settings', 'fl-builder'),
		'sections'      => array(
			'general'       => array(
				'title'         => '',
				'fields'        => array(
				)
			),

		)
	),
));


add_action( 'wp_ajax_nopriv_pesan_event', 'pesan_event_ajax' );
add_action('wp_ajax_pesan_event', 'pesan_event_ajax');
function pesan_event_ajax() {
	$data = $_POST;
	$to = $data['emailpenerima'];
	$subject = 'Pemesanan Event';
	if(!empty($data['pesan'])){
		$pesantambahan = 'Pesan: '.$data['pesan'].'<br>';
	} else {
		$pesantambahan = '';
	}
	echo '<div class="alert alert-success" role="alert">Email terkirim.</div>';
	//echo '<pre>'.print_r($_POST,1).'</pre>';
	$message = '
	<html>
	<head>
	<title>'.get_bloginfo('name').' (Pemesanan Event)</title>
	</head>
	<body>
	Nama: '.$data['nama'].'<br>
	Email: '.$data['email-pemesanan'].'<br>
	Alamat: '.$data['alamat'].'<br>
	Telepon: '.$data['telepon'].'<br>
	'.$pesantambahan.'
	Event: '.$data['event'].'<br>
	</body>
	</html>';
	// Always set content-type when sending HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	// More headers
	$headers .= 'From: '.get_bloginfo('name').' <no-reply@mitrapromosi.id>' . "\r\n";
	mail($to,$subject,$message,$headers);
	exit();
}
