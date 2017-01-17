<?php
$image_data = $blast->get_image_data();
$description = $blast->get_description();
$url = $blast->get_url();
$prompt = $blast->get_prompt();
if ( null !== $image_data ) {
?><img src="<?php echo $image_data['url']; ?>" width="100%" /><?php
}?><p><?php echo $description; ?></p><?php
if ( null !== $url ) {
?><a href="<?php echo $url ?>"><?php echo null !== $prompt ? $prompt : $url ?></a><?php
}?>
