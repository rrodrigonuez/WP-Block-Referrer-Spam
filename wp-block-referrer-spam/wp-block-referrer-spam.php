<?php

/**
 *
 * @link              http://www.twomandarins.com
 * @since             1.0.0
 * @package           WP_Block_Referrer_Spam
 *
 * @wordpress-plugin
 * Plugin Name:       WP Block Referrer Spam
 * Plugin URI:        http://www.twomandarins.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Roger Rodrigo
 * Author URI:        http://www.twomandarins.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-block-referrer-spam
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-block-referrer-spam-activator.php
 */
function activate_wp_block_referrer_spam() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-block-referrer-spam-activator.php';
	WP_Block_Referrer_Spam_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-block-referrer-spam-deactivator.php
 */
function deactivate_wp_block_referrer_spam() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-block-referrer-spam-deactivator.php';
	WP_Block_Referrer_Spam_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_block_referrer_spam' );
register_deactivation_hook( __FILE__, 'deactivate_wp_block_referrer_spam' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-block-referrer-spam.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_block_referrer_spam() {

	$plugin = new WP_Block_Referrer_Spam();
	$plugin->run();

}
run_wp_block_referrer_spam();
