<?php

/**
 *
 * @link              http://www.twomandarins.com
 * @since             1.0
 * @package           WP_Block_Referrer_Spam
 *
 * @wordpress-plugin
 * Plugin Name:       WP Block Referrer Spam
 * Plugin URI:        http://www.twomandarins.com
 * Description:       Blocks referrer spammers from a community-contributed list. Keep your analytics data clean and accurate.
 * Version:           1.1
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

define( 'WPBRS_REQUIRED_PHP_VERSION', '5.3' ); 	// because of get_called_class()
define( 'WPBRS_REQUIRED_WP_VERSION',  '3.1' ); 	// because of esc_textarea()

/**
 * Checks if the system requirements are met
 *
 * @since    1.0
 * @return bool True if system requirements are met, false if not
 */
function wpbrs_requirements_met() {

	global $wp_version;

	if ( version_compare( PHP_VERSION, WPBRS_REQUIRED_PHP_VERSION, '<' ) ) {
		return false;
	}
	if ( version_compare( $wp_version, WPBRS_REQUIRED_WP_VERSION, '<' ) ) {
		return false;
	}
	return true;

}

/**
 * Prints an error that the system requirements weren't met.
 *
 * @since    1.0
 */
function wpbrs_requirements_error() {

	global $wp_version;
	require_once( dirname( __FILE__ ) . '/views/errors/requirements-error.php' );

}

function run_wp_block_referrer_spam() {

	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-block-referrer-spam.php';

	/**
	 * Begins execution of the plugin.
	 *
	 * Since everything within the plugin is registered via hooks,
	 * then kicking off the plugin from this point in the file does
	 * not affect the page life cycle.
	 *
	 * @since    1.0
	 */
	$plugin = WP_Block_Referrer_Spam::get_instance();

}

/**
 * Check requirements and load main class
 * The main program needs to be in a separate file that only gets loaded if the plugin requirements are met.
 * Otherwise older PHP installations could crash when trying to parse it.
 * 
 * @since    1.0
 */
if ( wpbrs_requirements_met() ) {

	run_wp_block_referrer_spam();

} else {

	add_action( 'admin_notices', 'wpbrs_requirements_error' );
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	deactivate_plugins( plugin_basename( __FILE__ ) );

}
