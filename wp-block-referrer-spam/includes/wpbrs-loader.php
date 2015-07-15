<?php

/**
 * Loader class that includes and loads dependencies and implements activation and desactivation methods
 *
 * @since      1.0
 * @package    WP_Block_Referrer_Spam
 * @subpackage WP_Block_Referrer_Spam/includes
 *
 */

if ( ! class_exists( 'WPBRS_Loader' ) ) {

	class WPBRS_Loader {

		/**
		 *
		 * @since    1.0
		 * @access   private
		 * @var      WPBRS_Loader    $instance    Instance of this class.
		 */
		private static $instance;

		/**
		 * Provides access to a single instance of a module using the singleton pattern
		 *
		 * @since    1.0
		 * @return object
		 */
		public static function get_instance() {

			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;

		}

		/**
		 * Constructor
		 *
		 * @since    1.0
		 */
		protected function __construct() {

			spl_autoload_register( array( &$this, 'load_dependencies' ) );

			$this->register_hook_callbacks();

		}

		/**
		 * Register callbacks for actions and filters
		 *
		 */
		public function register_hook_callbacks() {

			WPBRS_Actions_Filters::add_action( 'wpmu_new_blog', $this, 'activate_new_site' );
			WPBRS_Actions_Filters::add_action( 'plugins_loaded', $this, 'load_plugin_textdomain' );

			register_activation_hook(   WP_Block_Referrer_Spam::$plugin_path . WP_Block_Referrer_Spam::PLUGIN_ID . '.php', array( $this, 'activate' ) );
			register_deactivation_hook( WP_Block_Referrer_Spam::$plugin_path . WP_Block_Referrer_Spam::PLUGIN_ID . '.php', array( $this, 'deactivate' ) );

		}

		/**
		 * Loads all Plugin dependencies
		 *
		 * @since    1.0
		 */
		private function load_dependencies( $class ) {

			if ( false !== strpos( $class, WP_Block_Referrer_Spam::CLASS_PREFIX ) ) {

				$className = str_replace( '_', '-', strtolower( $class ) );

				if ( false !== strpos( $class, WP_Block_Referrer_Spam::CLASS_PREFIX . 'Controller' ) ) {
					$path = WP_Block_Referrer_Spam::$plugin_path . 'controllers/' . $className . '.php';
					require_once( $path );
				} elseif ( false !== strpos( $class, WP_Block_Referrer_Spam::CLASS_PREFIX . 'Model' ) ) {
					$path = WP_Block_Referrer_Spam::$plugin_path . 'models/' . $className . '.php';
					require_once( $path );
				} else {
					$path = WP_Block_Referrer_Spam::$plugin_path . 'includes/' . $className . '.php';
					require_once( $path );
				}

			}

		}

		/**
		 * Load the plugin text domain for translation.
		 *
		 * @since    1.0
		 */
		public function load_plugin_textdomain( $domain ) {

			load_plugin_textdomain(
				WP_Block_Referrer_Spam::PLUGIN_ID,
				false,
				WP_Block_Referrer_Spam::$plugin_path . 'languages/'
			);

		}

		/**
		 * Prepares sites to use the plugin during single or network-wide activation
		 *
		 * @since    1.0
		 * @param bool $network_wide
		 */
		public function activate( $network_wide ) {

			if ( $network_wide && is_multisite() ) {
				$sites = wp_get_sites( array( 'limit' => false ) );
				foreach ( $sites as $site ) {
					switch_to_blog( $site['blog_id'] );
					$this->single_activate( $network_wide );
					restore_current_blog();
				}
			} else {
				$this->single_activate( $network_wide );
			}

		}

		/**
		 * Runs activation code on a new WPMS site when it's created
		 *
		 * @since    1.0
		 * @param int $blog_id
		 */
		public function activate_new_site( $blog_id ) {

			switch_to_blog( $blog_id );
			$this->single_activate( true );
			restore_current_blog();

		}

		/**
		 * Prepares a single blog to use the plugin
		 *
		 * @since    1.0
		 * @param bool $network_wide
		 */
		private function single_activate( $network_wide ) {

			WPBRS_Model_Settings::load_defaults();
			WPBRS_Controller_Blocker::filter_referrers_htaccess();
			WPBRS_Controller_Cron::register_cron_jobs();

		}

		/**
		 * Rolls back activation procedures when de-activating the plugin
		 *
		 * @since    1.0
		 */
		public function deactivate() {

			WPBRS_Controller_Cron::unregister_cron_jobs();
			WPBRS_Controller_Blocker::filter_referrers_htaccess( true ); //remove WP Block Referrer Spam rules
			WPBRS_Model_Admin_Notices::remove_admin_notices();

			flush_rewrite_rules();

		}

		/**
		 * Fired when user uninstalls the plugin, called in unisntall.php file
		 *
		 * @since    1.0
		 */
		public static function uninstall_plugin() {

			require_once dirname( plugin_dir_path( __FILE__ ) ) . '/includes/class-wp-block-referrer-spam.php';
			require_once dirname( plugin_dir_path( __FILE__ ) ) . '/models/wpbrs-model.php';
			require_once dirname( plugin_dir_path( __FILE__ ) ) . '/models/wpbrs-model-settings.php';
			
			WPBRS_Model_Settings::delete_settings();

		}

	}

}