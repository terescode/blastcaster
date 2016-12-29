<?php
$image_data = $blast->get_image_data();
if ( null !== $image_data ) {
?><img src="<?php echo $image_data['url']; ?>" width="100%" /><?php
}?><p><?php echo $blast->get_description(); ?></p>
<a href="">Continue reading this story at:</a>
