<?php

namespace Terescode\BlastCaster;

require_once BC_PLUGIN_DIR . 'admin/controllers/class-add-blast-controller.php'

?>
<div class="wrap">
	<h2><?php $wph->esc_html( __( 'Add a blast', 'blastcaster' ) ); ?></h2>

	<form name="blastcaster-form" id="blastcaster-form" method="post" action="<?php echo $wph->admin_url( 'admin-post.php' ); ?>">
		<input type="hidden" name="action" value="<?php echo BcAddBlastController::BC_ADD_BLAST_POST_ACTION; ?>">
		<?php
			$wph->wp_nonce_field( 'add_blast', 'add_blast_nonce', false );
			$wph->wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
			$wph->wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
		?>
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-1">

				<div id="post-body-content">
					<!-- #post-body-content -->
				</div>

				<div id="postbox-container-1" class="postbox-container">
					<?php
						$wph->do_meta_boxes( '', 'normal', null );
						$wph->do_meta_boxes( '', 'advanced', null );
						$wph->submit_button(
							__( 'Add blast', 'blastcaster' ),
							'primary',
							'add-blast'
						);
					?>
				</div>

			</div> <!-- #post-body -->

		</div> <!-- #poststuff -->

	</form>

</div><!-- .wrap -->
