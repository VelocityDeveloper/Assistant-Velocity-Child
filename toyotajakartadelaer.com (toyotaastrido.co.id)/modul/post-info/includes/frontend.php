<?php global $post;
$post_id = $post->ID;
$tampilan = rwmb_meta( 'tampilan', array( 'size' => 'thumbnail' ) );
$gallery = rwmb_meta( 'gallery', array( 'size' => 'thumbnail' ) );
$harga = get_post_meta( $post_id, 'harga', true );
$post_content = get_the_content($post_id);

if(!empty($harga)){
  echo '<div class="h4 fw-bold mb-4"><i class="fa fa-tag" aria-hidden="true"></i> '.$harga.'</div>';
}

//echo '<pre>'.print_r($tampilan,1).'</pre>';
echo '<div class="row mb-4">';
echo '<div class="col-md-4">';
echo '<div class="row product-info text-center">';
	echo '<div class="col-4 col-md-4 col-lg-4 py-4">';
		echo '<div>';
			echo '<img height="50" src="'.get_stylesheet_directory_uri().'/img/icon1.png">';
			echo '<p class="mb-0 mt-2"><small>TEMPAT DUDUK</small></p>';
			echo '<div class="fw-bold text-primary">'.get_post_meta( $post_id, 'kursi', true ).' kursi</div>';
        echo '</div>';
	echo '</div>';
	echo '<div class="col-4 col-md-4 col-lg-4 py-4">';
		echo '<div>';
			echo '<img height="50" src="'.get_stylesheet_directory_uri().'/img/icon2.png">';
			echo '<p class="mb-0 mt-2"><small>MESIN</small></p>';
			echo '<div class="fw-bold text-primary">'.get_post_meta( $post_id, 'mesin', true ).'</div>';
        echo '</div>';
	echo '</div>';
	echo '<div class="col-4 col-md-4 col-lg-4 py-4">';
		echo '<div>';
			echo '<img height="50" src="'.get_stylesheet_directory_uri().'/img/icon3.png">';
			echo '<p class="mb-0 mt-2"><small>TENAGA</small></p>';
			echo '<div class="fw-bold text-primary">'.get_post_meta( $post_id, 'tenaga', true ).'</div>';
        echo '</div>';
	echo '</div>';
echo '</div>';  
echo '<div class="row product-info product-info-border-top text-center">';
	echo '<div class="col-4 col-md-4 col-lg-4 py-4">';
		echo '<div>';
			echo '<img height="50" src="'.get_stylesheet_directory_uri().'/img/icon4.png">';
			echo '<p class="mb-0 mt-2"><small>TRANSMISI</small></p>';
			echo '<div class="fw-bold text-primary">'.get_post_meta( $post_id, 'transmisi', true ).'</div>';
        echo '</div>';
	echo '</div>';
	echo '<div class="col-4 col-md-4 col-lg-4 py-4">';
		echo '<div>';
			echo '<img height="50" src="'.get_stylesheet_directory_uri().'/img/icon5.png">';
			echo '<p class="mb-0 mt-2"><small>GARDAN</small></p>';
			echo '<div class="fw-bold text-primary">'.get_post_meta( $post_id, 'gardan', true ).'</div>';
        echo '</div>';
	echo '</div>';
	echo '<div class="col-4 col-md-4 col-lg-4 py-4">';
		echo '<div>';
			echo '<img height="50" src="'.get_stylesheet_directory_uri().'/img/icon6.png">';
			echo '<p class="mb-0 mt-2"><small>BAHAN BAKAR</small></p>';
			echo '<div class="fw-bold text-primary">'.get_post_meta( $post_id, 'bahan_bakar', true ).'</div>';
        echo '</div>';
	echo '</div>';
echo '</div>';
echo '</div>';
echo '<div class="col-md-8">';
  if($tampilan){
      echo '<div class="slider-produk mb-3 text-center">';
          echo '<div class="p-2 mb-1 id-'.$id.'" id="parent-container">';
              foreach ( $tampilan as $image ) {
                  $urlbesar = $image['full_url'];
                  echo '<div class="p-1">';
                  echo '<a href="'.$image['full_url'].'">';
                  echo '<img class="lazy" src="'.$urlbesar.'" alt="" data-src="'.$urlbesar.'">';
                  echo '</a></div>';
              }
          echo '</div>';
          echo '<div class="px-4 position-relative navigasi navid-'.$id.'">';
              foreach ( $tampilan as $image ) {
                  $color = $image['description'];
                  echo '<div class="p-1">';
                    echo '<div class="product-color" style="background-color: '.$color.'"></div>';
                  echo '</div>';
              }
          echo '</div>';
      echo '</div>';
  } else if(has_post_thumbnail($post_id)){
      $url            = get_the_post_thumbnail_url($post_id,'full');
      echo '<div class="slider-produk mb-3">';
          echo '<div class="id-'.$id.'" id="parent-container">';
              echo '<div class="p-1"><a href="'.$url.'"><img class="lazy img-float w-100" src="'.$url.'" alt="" data-src="'.$url.'"></a></div>';
          echo '</div>';
      echo '</div>';
  }
echo '</div>';
echo '</div>';


if($gallery){
	echo '<div class="h4 mb-3">';
    echo 'Gallery';
	echo '</div>';
	echo '<div class="post-gallery-'.$id.' mb-4 row">';
  foreach ($gallery as $image) {
    $urlbesar = $image['full_url'];
    $resize = aq_resize($urlbesar,400,400,true,true,true);
    echo '<div class="col-6 col-md-4 mb-4">';
    echo '<a href="'.$image['full_url'].'">';
    echo '<img class="lazy w-100" src="'.$resize.'" alt="" data-src="'.$urlbesar.'">';
    echo '</a></div>';
  }
  echo '</div>';
}


if($post_content){
	echo '<div class="h4 mb-3">';
    echo 'Description';
	echo '</div>';
	echo '<div class="text-muted mb-4">'.$post_content.'</div>';
}

?>
