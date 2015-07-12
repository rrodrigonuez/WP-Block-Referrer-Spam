<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.twomandarins.com
 * @since      1.0.0
 *
 * @package    WP_Block_Referrer_Spam
 * @subpackage WP_Block_Referrer_Spam/admin/partials
 */
?>

<div class="wrap">
	<h2>WP Revisions Limit</h2>
	<div id="save_message" class="updated settings-error" style="display:none;"></div>
	<form id="wp_block_referrer_spam" method="post" action="options.php">
		<?php
		// This prints out all hidden setting fields
		settings_fields( 'wp_block_referrer_spam_group' );
		do_settings_sections( $this->plugin_name );
		submit_button();
		?>
	</form>
</div>
