<?php

/**
 * Controller class that implements Plugin Settings configurations
 *
 * @since      1.0
 * @package    WP_Block_Referrer_Spam
 * @subpackage WP_Block_Referrer_Spam/controllers
 *
 */

if ( ! class_exists( 'WPBRS_Controller_Settings' ) ) {

	class WPBRS_Controller_Settings extends WPBRS_Controller {

		private static $hook_suffix = '';

		const REQUIRED_CAPABILITY = 'manage_options';


		/**
		 * Constructor
		 *
		 * @since    1.0
		 */
		protected function __construct() {

			self::$hook_suffix = 'tools_page_' . WP_Block_Referrer_Spam::PLUGIN_ID;

			$this->register_hook_callbacks();
			$this->model = WPBRS_Model_Settings::get_instance();

		}

		/**
		 * Register callbacks for actions and filters
		 *
		 * @since    1.0
		 */
		protected function register_hook_callbacks() {

			WPBRS_Actions_Filters::add_action( 'admin_menu', 								$this, 'plugin_menu' );
			WPBRS_Actions_Filters::add_action( 'admin_print_scripts-' . self::$hook_suffix, $this, 'enqueue_scripts' );
			WPBRS_Actions_Filters::add_action( 'load-' . self::$hook_suffix,				$this, 'register_fields' );
			WPBRS_Actions_Filters::add_action( 'wp_ajax_wpbrs_reset_list', 					$this, 'ajax_wpbrs_reset_list_callback' );
			
			WPBRS_Actions_Filters::add_filter(
				'plugin_action_links_' . WP_Block_Referrer_Spam::PLUGIN_ID . '/' . WP_Block_Referrer_Spam::PLUGIN_ID . '.php',
				$this,
				'add_plugin_action_links'
			);

		}

		/** 
		 * Create menu for Plugin inside Settings menu
		 *
		 * @since    1.0
		 */
		public function plugin_menu() {

			self::$hook_suffix = add_management_page(
				__( WP_Block_Referrer_Spam::PLUGIN_NAME ), 	// Page Title
				__( 'Block Referrer Spam' ), 			// Menu Title
				self::REQUIRED_CAPABILITY, 				// Capability
				self::SETTINGS_PAGE_URL, 				// Menu URL
				array( $this, 'markup_settings_page' ) 	// Callback
			);

		}

		/**
		 * Enqueues CSS, JavaScript, etc
		 *
		 * @since    1.0
		 */
		public function enqueue_scripts( $hook ) {

			wp_enqueue_script( 'jquery-form' );
			wp_enqueue_script(
				'wpbrs_admin_js',
				plugins_url( 'views/js/' . WP_Block_Referrer_Spam::PLUGIN_ID . '-admin.js', dirname( __FILE__ ) ),
				array( 'jquery-form' ),
				WP_Block_Referrer_Spam::PLUGIN_VERSION,
				true
			);

		}

		/**
		 * Creates the markup for the Settings page
		 *
		 * @since    1.0
		 */
		public function markup_settings_page() {

			if ( current_user_can( self::REQUIRED_CAPABILITY ) ) {

				echo self::render_template(
					'page-settings/page-settings.php',
					array(
						'page_title' 	=> WP_Block_Referrer_Spam::PLUGIN_NAME,
						'settings_name' => WPBRS_Model_Settings::SETTINGS_NAME
					)
				);

			} else {

				wp_die( __( 'Access denied.' ) );

			}

		}

		/**
		 * Registers settings sections and fields
		 *
		 * @since    1.0
		 */
		public function register_fields() {

			// Add Settings Page Section
			add_settings_section(
				'wpbrs_section', 							// Section ID
				__( 'Settings' ), 							// Section Title
				array( $this, 'markup_section_headers' ), 	// Section Callback
				self::SETTINGS_PAGE_URL 					// Page URL
			);

			// Add Settings Page Field
			add_settings_field(
				'referrer_spam_list', 				// Field ID
				__( 'List of Referrers to Block:' ),// Field Title 
				array( $this, 'markup_fields' ), 	// Field Callback
				self::SETTINGS_PAGE_URL,			// Page
				'wpbrs_section', 					// Section ID
				array( 'label_for' => 'referrer_spam_list' ) // Field Label
			);

		}

		/**
		 * Adds the section introduction text to the Settings page
		 *
		 * @param array $section
		 *
		 * @since    1.0
		 */
		public function markup_section_headers( $section ) {

			echo self::render_template(
				'page-settings/page-settings-section-headers.php',
				array(
					'section' 		=> $section,
					'n_referrers' 	=> self::get_model()->referrer_count()
				)
			);
		
		}

		/**
		 * Delivers the markup for settings fields
		 *
		 * @param array $field
		 *
		 * @since    1.0
		 */
		public function markup_fields( $field ) {

			$options = self::get_model()->get_settings();
			$list = isset( $options['referrer_spam_list'] ) ? $options['referrer_spam_list'] : array();
			$list = implode( "\n", $list );

			echo self::render_template(
				'page-settings/page-settings-fields.php',
				array(
					'list' => $list,
					'settings_name' => WPBRS_Model_Settings::SETTINGS_NAME
				),
				'always'
			);
		
		}

		/**
		 * Adds links to the plugin's action link section on the Plugins page
		 *
		 * @param array $links The links currently mapped to the plugin
		 * @return array
		 *
		 * @since    1.0
		 */
		public function add_plugin_action_links( $links ) {

			$settings_link = '<a href="tools.php?page=' . self::SETTINGS_PAGE_URL . '">' . __( 'Settings' ) . '</a>';
			array_unshift( $links, $settings_link );

			return $links;

		}
	
		/**
		 * Adds links to the plugin's action link section on the Plugins page
		 *
		 * @param array $links The links currently mapped to the plugin
		 * @return array
		 *
		 * @since    1.0
		 */
		public function ajax_wpbrs_reset_list_callback( ) {

			$result = self::get_model()->load_defaults( true );

			$list = isset( $result['referrer_spam_list'] ) ? $result['referrer_spam_list'] : array();
			echo implode( "\n", $list );

			wp_die();

		}

	}

}