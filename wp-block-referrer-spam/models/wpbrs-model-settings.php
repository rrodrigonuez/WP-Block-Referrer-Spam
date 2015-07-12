<?php

/**
 * Model class that implements Plugin Settings
 *
 * @since      1.0
 * @package    WP_Block_Referrer_Spam
 * @subpackage WP_Block_Referrer_Spam/models
 *
 */

if ( ! class_exists( 'WPBRS_Model_Settings' ) ) {

	class WPBRS_Model_Settings extends WPBRS_Model {

		const GIT_LIST_URL = 'https://raw.githubusercontent.com/piwik/referrer-spam-blacklist/master/spammers.txt';


		/**
		 * Constructor
		 *
		 * @since    1.0
		 */
		protected function __construct() {

			$this->register_hook_callbacks();
			self::get_settings();

		}

		/**
		 * Register callbacks for actions and filters
		 *
		 * @since    1.0
		 */
		protected function register_hook_callbacks() {

			WPBRS_Actions_Filters::add_action( 'admin_init', $this, 'register_settings' );

		}

		/**
		 * Register settings
		 *
		 * @since    1.0
		 */
		public function register_settings() {

			// The settings container
			register_setting(
				self::SETTINGS_NAME, 	// Option group Name
				self::SETTINGS_NAME,	// Option Name
				array( $this, 'sanitize' ) // Sanitize
			);

		}

		/**
		 * Validates submitted setting values before they get saved to the database.
		 *
		 * @since    1.0
		 * @param array $settings
		 * @return array
		 */
		public function sanitize( $settings ) {

			$new_settings = null;
			if ( isset( $settings['referrer_spam_list'] ) && $settings['referrer_spam_list'] != '' ) {

				$list = $settings['referrer_spam_list'];
				if ( !is_array( $list ) ) {
					$list = array_unique( array_map( 'trim', preg_split( "/[\n,]+/", str_replace( "\r", "", $list ) ) ) );
				}
				
				$new_settings = array( 'referrer_spam_list' => $list );

			}

			self::$settings = $new_settings;

			WPBRS_Controller_Blocker::filter_referrers_htaccess();

			return $new_settings;

		}

		/**
		 * Return number of referrels stored in settings variable
		 *
		 * @since    1.0
		 */
		public function referrer_count() {

			$settings = self::get_settings();
			if ( isset( $settings['referrer_spam_list'] ) || !empty( $settings['referrer_spam_list'] ) ) {
				return count( $settings['referrer_spam_list'] );
			}

			return false;

		}

		/**
		 * Delete all plugin setings
		 *
		 * @since    1.0
		 */
		public static function delete_settings() {

			return delete_option( self::SETTINGS_NAME );

		}

		/**
		 * Load defaults values of plugin settings in case settings do not exist on the data base
		 *
		 * @since    1.0
		 * @param bool $reset
		 * @return array
		 */
		public static function load_defaults( $reset = false ) {

			$settings = self::get_settings();
			if ( $reset || !isset( $settings ) || empty( $settings ) || $settings == '' ) {
				self::update_from_URL( $reset );
			}

			return self::get_settings();

		}

		/** 
		 * Download and update Referrer Spam list from URL
		 *
		 * @since    1.0
		 * @param bool $reset
		 * @return bool
		 */
		public static function update_from_URL( $reset = false ) {

			$contextOptions = array(
				"ssl"=>array(
					"verify_peer"=>false,
					"verify_peer_name"=>false,
				)
			);

			$new_list = file( self::GIT_LIST_URL, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES, stream_context_create( $contextOptions ) );
			$settings = self::get_settings();

			if ( !$reset && $settings && !empty( $new_list ) ) {
				if ( isset( $settings['referrer_spam_list'] )
					&& $settings['referrer_spam_list'] != ''
					&& $settings['referrer_spam_list'] != $new_list
				) {
					$new_list = array_unique( array_merge( $new_list, $settings['referrer_spam_list'] ) );
				}
			}

			$settings['referrer_spam_list'] = array_merge( array(), $new_list );

			$result = self::update_settings( $settings );

			WPBRS_Controller_Blocker::filter_referrers_htaccess();

			return $result;

		}

	}

}