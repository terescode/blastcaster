<div class="wrap">
	<?php screen_icon(); ?>
	<h2><?php esc_html_e( 'Add a blast', 'blastcaster' ); ?></h2>

	<form name="blastcaster-form" id="blastcaster-form" method="post">
		<input type="hidden" name="action" value="some-action">
		<?php
			// wp_nonce_field( 'some-action-nonce' );
			wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
			wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
		?>
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-1">

				<div id="post-body-content">
					<!-- #post-body-content -->
				</div>

				<div id="postbox-container-1" class="postbox-container">
					<?php do_meta_boxes( '', 'normal', null ); ?>
					<?php do_meta_boxes( '', 'advanced', null ); ?>
				</div>

			</div> <!-- #post-body -->

		</div> <!-- #poststuff -->

	</form>

</div><!-- .wrap -->
