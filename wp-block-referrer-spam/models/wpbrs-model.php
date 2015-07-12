<?php

/**
 * Abstract class to define/implement base methods for model classes
 *
 * @since      1.0
 * @package    WP_Block_Referrer_Spam
 * @subpackage WP_Block_Referrer_Spam/models
 *
 */

if ( ! class_exists( 'WPBRS_Model' ) ) {

	abstract class WPBRS_Model {

		private static $instances = array();
		protected static $settings;

		const SETTINGS_NAME = WP_Block_Referrer_Spam::PLUGIN_ID;


		/**
		 * Provides access to a single instance of a module using the singleton pattern
		 *
		 * @since    1.0
		 * @return object
		 */
		public static function get_instance() {

			$classname = get_called_class();

			if ( ! isset( self::$instances[ $classname ] ) ) {
				self::$instances[ $classname ] = new $classname();
			}
			return self::$instances[ $classname ];

		}

		/**
		 * Retrieves all of the settings from the database
		 *
		 * @since    1.0
		 * @return array
		 */
		public static function get_settings() {
			
			if ( !isset( static::$settings ) ) {
				static::$settings = is_multisite() ? get_site_option( self::SETTINGS_NAME, array() ) : get_option( self::SETTINGS_NAME, array() );
			}
			return static::$settings;
			
		}

		/** 
		 * Helper to update Plugin Settings
		 *
		 * @since    1.0
		 */
		protected static function update_settings( $new_value ) {

			if ( isset( $new_value ) && $new_value != '' ) {
				static::$settings = $new_value;
				
				return is_multisite() ? update_site_option( self::SETTINGS_NAME, $new_value ) : update_option( self::SETTINGS_NAME, $new_value );
			}

			return false;

		}

		/**
		 * Constructor
		 *
		 * @since    1.0
		 */
		abstract protected function __construct();

		/**
		 * Register callbacks for actions and filters
		 *
		 * @since    1.0
		 */
		abstract protected function register_hook_callbacks();

		/**
		 * Registers settings on the database
		 *
		 * @since    1.0
		 */
		abstract public function register_settings();

	}

}