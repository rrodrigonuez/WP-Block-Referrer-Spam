<?php

/**
 * The core plugin class.
 *
 * @since      1.0
 * @package    WP_Block_Referrer_Spam
 * @subpackage WP_Block_Referrer_Spam/includes
 *
 */

if ( ! class_exists( 'WP_Block_Referrer_Spam' ) ) {

	class WP_Block_Referrer_Spam {

		/**
		 *
		 * @since    1.0
		 * @access   private
		 * @var      WP_Block_Referrer_Spam    $instance    Instance of this class.
		 */
		private static $instance;

		/**
		 * The modules variable holds all modules of the plugin.
		 *
		 * @since    1.0
		 * @access   protected
		 * @var      object    $modules    Maintains all modules of the plugin.
		 */
		private static $modules = array();

		/**
		 * Main plugin path /wp-content/plugins/<plugin-folder>/.
		 *
		 * @since    1.0
		 * @access   protected
		 * @var      string    $plugin_main_path    Main path.
		 */
		public static $plugin_path;


		/**
		 * The unique identifier of this plugin.
		 *
		 * @since    1.0
		 */
		const PLUGIN_ID 		= 'wp-block-referrer-spam';

		/**
		 * The name identifier of this plugin.
		 *
		 * @since    1.0
		 */
		const PLUGIN_NAME 		= 'WP Block Referrer Spam';


		/**
		 * The current version of the plugin.
		 *
		 * @since    1.0
		 */
		const PLUGIN_VERSION 	= '1.1';

		/**
		 * The plugin prefix to referenciate classes inside the plugin
		 *
		 * @since    1.0
		 */
		const CLASS_PREFIX 		= 'WPBRS_';

		/**
		 * The plugin prefix to referenciate files and prefixes inside the plugin
		 *
		 * @since    1.0
		 */
		const PLUGIN_PREFIX 	= 'wpbrs-';
		
		/**
		 * Provides access to a single instance of a module using the singleton pattern
		 *
		 * @return object
		 *
		 * @since    1.0
		 */
		public static function get_instance() {

			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;

		}

		/**
		 * Define the core functionality of the plugin.
		 *
		 * Load the dependencies, define the locale, and set the hooks for the admin area and
		 * the public-facing side of the site.
		 *
		 * @since    1.0
		 */
		public function __construct() {

			self::$plugin_path = plugin_dir_path( dirname( __FILE__ ) );

			require_once( self::$plugin_path . 'includes/' . self::PLUGIN_PREFIX . 'loader.php' );

			self::$modules['WPBRS_Loader'] 						= WPBRS_Loader::get_instance();
			self::$modules['WPBRS_Controller_Settings'] 		= WPBRS_Controller_Settings::get_instance();
			self::$modules['WPBRS_Controller_Blocker'] 			= WPBRS_Controller_Blocker::get_instance();
			self::$modules['WPBRS_Controller_Cron'] 			= WPBRS_Controller_Cron::get_instance();
			self::$modules['WPBRS_Controller_Admin_Notices'] 	= WPBRS_Controller_Admin_Notices::get_instance();
			
			WPBRS_Actions_Filters::init_actions_filters();

		}

	}

}