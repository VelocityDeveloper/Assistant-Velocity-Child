<?php $images = $settings->gallery;
$style = $settings->style;
echo '<div id="image-carousel" class="px-5 images-'.$id.'">';
if ($images) {
foreach($images as $image){
	$caption = wp_get_attachment_caption($image);
    echo '<div class="p-2"><div class="image-list"><a href="'.wp_get_attachment_url($image).'" target="_blank" title="'.$caption.'">';
	echo '<div class="imm-thumb imm-'.$style.'" style="background-image:url('.wp_get_attachment_url($image).');"></div>';
	if($caption){
		echo '<div class="mt-2">'.$caption.'</div>';
	}
    echo '</a></div></div>';
}
}
echo '</div>';?>
