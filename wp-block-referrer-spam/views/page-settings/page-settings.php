<style type="text/css">
	form#wp_block_referrer_spam input#submit { margin-right: 20px; }
</style>

<div class="wrap">

	<h2><?php esc_html_e( __( $page_title ) ); ?></h2>

	<div id="message_update" class="updated notice is-dismissible" style="display:none;">
		<p>
			<strong><?php echo __( 'Referrer Spam List Updated' ); ?>.</strong>
		</p>
	</div>

	<div id="message_reset" class="notice notice-info is-dismissible" style="display:none;">
		<p>
			<strong><?php echo __( 'Referrer Spam List Reseted' ); ?>.</strong>
		</p>
	</div>
	
	<form id="wp_block_referrer_spam" method="post" action="options.php">
		<?php
			settings_fields( $settings_name );
			do_settings_sections( $settings_name );
			submit_button( _( 'Save Referrers List' ), 'primary', 'submit', false );
			submit_button( __( 'Reset to Default Values' ), 'secondary', 'reset', false, array( 'id' => 'reset' ) );
		?>
	</form>

</div> <!-- .wrap -->