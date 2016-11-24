<?php

namespace Terescode\BlastCaster;

require_once BC_PLUGIN_DIR . 'admin/class-add-blast-page.php'

?>
<div class="wrap">
	<h1><?php echo $wph->esc_html( $plugin_helper->string( BcStrings::ABF_BLAST_MENU_TITLE ) ); ?></h1>

	<form name="blastcaster-form" id="blastcaster-form" method="post">
		<input type="hidden" name="action" value="<?php echo BcAddBlastPage::BC_ADD_BLAST_POST_ACTION; ?>">
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
							$plugin_helper->string( BcStrings::ABF_BLAST_BUTTON_LABEL ),
							'primary',
							'add-blast'
						);
					?>
				</div>

			</div> <!-- #post-body -->

		</div> <!-- #poststuff -->

	</form>

</div><!-- .wrap -->
