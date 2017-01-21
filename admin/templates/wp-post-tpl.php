<?php
$image_data = $blast->get_image_data();
$description = $blast->get_description();
$link = $blast->get_link();
$link_text = $blast->get_link_text();
$link_intro = $blast->get_link_intro();
if ( null !== $image_data ) {
?><img src="<?php echo $image_data['url']; ?>" width="100%" /><?php
}?><p><?php echo $description; ?></p><?php
if ( null !== $link ) {
	if ( null !== $link_intro ) {
	?><span><?php echo $link_intro ?></span><?php
	}?><a href="<?php echo $link ?>"><?php echo null !== $link_text ? $link_text : $link ?></a><?php
}?>
