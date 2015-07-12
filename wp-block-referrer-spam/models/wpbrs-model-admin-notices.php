<?php

/**
 * Model class that implements Plugin Admin Notices messages
 *
 * @since      1.0
 * @package    WP_Block_Referrer_Spam
 * @subpackage WP_Block_Referrer_Spam/models
 *
 */

if ( ! class_exists( 'WPBRS_Model_Admin_Notices' ) ) {

	class WPBRS_Model_Admin_Notices extends WPBRS_Model {

		const ADMIN_NOTICES_SETTINGS_NAME = 'admin_notices';


		/**
		 * Constructor
		 *
		 * @since    1.0
		 */
		protected function __construct() {

		}

		/**
		 * Register callbacks for actions and filters
		 *
		 * @since    1.0
		 */
		protected function register_hook_callbacks() {

		}

		/**
		 * Register settings
		 * 
		 * @since    1.0
		 */
		public function register_settings() {

		}

		/** 
		 * Show admin notices if plugin activation had any error
		 *
		 * @since    1.0
		 */
		public static function show_admin_notices() {

			if ( $notices = self::get_admin_notices() ) {

				foreach ( $notices as $notice ) {
					echo $notice;
				}
				self::remove_admin_notices();

			}

		}

		/** 
		 * Helper to add Plugin Admin Notices
		 *
		 * @since    1.0
		 */
		public static function add_admin_notice( $notice ) {

			if ( !$admin_notices = self::get_admin_notices() ) {
				$admin_notices = array();
			}

			$admin_notices[] = $notice;

			$settings = self::get_settings();
			$settings[ self::ADMIN_NOTICES_SETTINGS_NAME ] = $admin_notices;

			return self::update_settings( $settings );

		}

		/** 
		 * Helper to remove all Plugin Admin Notices
		 *
		 * @since    1.0
		 */
		public static function remove_admin_notices() {

			if ( $admin_notices = self::get_admin_notices() ) {
				$settings = self::get_settings();
				unset( $settings[ self::ADMIN_NOTICES_SETTINGS_NAME ] );
				return self::update_settings( $settings );
			}

			return true;

		}

		/** 
		 * Helper to get Plugin Admin Notices
		 *
		 * @since    1.0
		 */
		private static function get_admin_notices() {

			$settings = self::get_settings();
			if ( isset( $settings[ self::ADMIN_NOTICES_SETTINGS_NAME ] ) ) {
				return $settings[ self::ADMIN_NOTICES_SETTINGS_NAME ];
			}

			return false;

		}

	}

}