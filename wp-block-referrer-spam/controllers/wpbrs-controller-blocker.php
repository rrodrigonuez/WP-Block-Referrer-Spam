<?php

/**
 * Controller class that implements blocker functionality
 *
 * @since      1.0
 * @package    WP_Block_Referrer_Spam
 * @subpackage WP_Block_Referrer_Spam/controllers
 *
 */

if ( ! class_exists( 'WPBRS_Controller_Blocker' ) ) {

	class WPBRS_Controller_Blocker extends WPBRS_Controller {


		const SESSION_NAME 		= 'WP_BLOCK_REFERRER_SPAM';
		const HTACCESS_MARKER 	= 'WPBlockReferrerSpam';

		/**
		 * Constructor
		 *
		 * @since    1.0
		 */
		protected function __construct() {

			$this->register_hook_callbacks();
			$this->model = WPBRS_Model_Settings::get_instance();

		}

		/**
		 * Register callbacks for actions and filters
		 *
		 * @since    1.0
		 */
		protected function register_hook_callbacks() {

			WPBRS_Actions_Filters::add_action( 'init', $this, 'filter_referrers_no_htaccess', 0, 0 );

		}

		/**
		 * Block unwanted referrers for no apache web servers.
		 *
		 * @since    1.0
		 */
		public function filter_referrers_no_htaccess() {

			if ( false === stripos( $_SERVER['SERVER_SOFTWARE'], 'apache' )
				|| is_user_logged_in()
				|| !isset( $_SERVER['HTTP_REFERER'] )
				|| empty( $_SERVER['HTTP_REFERER'] )
			) {
				return true;
			}

			if ( isset( $_SESSION[ self::SESSION_NAME ] )
				|| false === stripos( $_SERVER['HTTP_REFERER'], $_SERVER['SERVER_NAME'] ) // Whitelist server hostname
			) {
				return true;
			}

			$referrer = preg_replace( "/(http|https)?:\/\/(www\.)?/", '', rtrim( $_SERVER['HTTP_REFERER'], '/' ) );
			$referrer = str_replace( '.', '\.', $referrer );

			$options = $this->get_model()->get_settings();
			if ( isset( $options['referrer_spam_list'] ) && preg_grep( "/$referrer/i", $options['referrer_spam_list'] ) ) {
				header( "HTTP/1.0 403 Forbidden" );
				die();
			}
			
			$_SESSION[ self::SESSION_NAME ] = true;

		}

		/**
		 * Block unwanted referrers for apache based web servers.
		 *
		 * @since    1.0
		 */
		public static function filter_referrers_htaccess( $remove = false ) {

			if ( !preg_match( "/apache/i", $_SERVER['SERVER_SOFTWARE'] ) ) {
				return true;
			}

			return self::update_htaccess( $remove );

		}

		/**
		 * Modify .htaccess file wit new values
		 *
		 * @since    1.0
		 */
		private static function update_htaccess( $remove = false ) {

			if ( !function_exists( 'get_home_path' ) )
				require_once( WP_Block_Referrer_Spam::$plugin_path . '../../../wp-admin/includes/file.php' );

			$htaccess_path = trailingslashit( get_home_path() ) . '.htaccess';

			self::remove_marker( $htaccess_path, self::HTACCESS_MARKER ); // remove current WP Block Referrer Spam rules to update them

			if ( $remove ) {
				return true;
			}

			$rules = self::build_rules();
			if ( !empty( $rules ) ) {
				if ( !function_exists( 'insert_with_markers' ) )
					require_once( WP_Block_Referrer_Spam::$plugin_path . '../../../wp-admin/includes/misc.php' );
				
				if ( !insert_with_markers( $htaccess_path, self::HTACCESS_MARKER, $rules ) ) {
					self::htacess_error_admin_notice();
					return false;
				}
			} else {
				return false;
			}

			return true;

		}

		/**
		 * Remove markers from .htaccess file
		 *
		 * @since    1.0
		 */
		private static function remove_marker( $filename,  $marker ) {

			if ( !file_exists( $filename ) || self::is_writeable_ACLSafe( $filename ) ) {
				if ( !file_exists( $filename ) ) {
					return false;
				} else {
					$markerdata = explode( "\n", implode( '', file( $filename ) ) );
				}

				$f = fopen( $filename, 'w' );

				if ( $markerdata ) {
					$state = true;
					foreach ( $markerdata as $n => $markerline ) {
						if ( strpos( $markerline, '# BEGIN ' . $marker ) !== false)
							$state = false;
						if ( $state ) {
							if ( $markerline !== '' && $n + 1 < count( $markerdata ) )
								fwrite( $f, "{$markerline}\n" );
							else
								fwrite( $f, "{$markerline}" );
						}
						if ( strpos( $markerline, '# END ' . $marker ) !== false ) {
							$state = true;
						}
					}
				}
				
				fclose( $f );
				return true;
			} else {
				return false;
			}

		}

		/**
		 * Shows htacess file error on backend.
		 *
		 * @since    1.0
		 */
		public static function htacess_error_admin_notice() {

			$notice = self::render_template( 'errors/htaccess-error.php' );
			return WPBRS_Model_Admin_Notices::add_admin_notice( $notice );

		}

		/**
		 * Check if a file is writable
		 * PHP's is_writable does not work with Win32 NTFS
		 *
		 * @since    1.0
		 */
		private static function is_writeable_ACLSafe( $path ) {

			if ( $path{strlen( $path ) - 1} == '/' ) { // recursively return a temporary file path
				return self::is_writeable_ACLSafe( $path . uniqid( mt_rand() ) . '.tmp' );
			} else if ( is_dir( $path ) ) {
				return self::is_writeable_ACLSafe( $path . '/' . uniqid( mt_rand() ) . '.tmp' );
			}

			// check tmp file for read/write capabilities
			$rm = file_exists( $path );
			$f = @fopen( $path, 'a' );

			if ( $f === false ) {
				return false;
			}

			fclose( $f );
			if ( !$rm ) {
				unlink( $path );
			}
			return true;

		}
	
		/**
		 * Build rules for Referrer Spammers
		 *
		 * @since    1.0
		 */
		private static function build_rules() {

			$settings = self::get_model()->get_settings();

			if ( !isset( $settings['referrer_spam_list'] ) || empty( $settings['referrer_spam_list'] ) ) {
				return false;
			}

			$rules = array();
			$rule_prefix = "\tSetEnvIfNoCase Referer ";
			$rule_suffix = " spammer=yes";

			foreach ( $settings['referrer_spam_list'] as $key => $value ) {
				if ( trim( $value ) ) {
					$rules[] = $rule_prefix . str_replace( '.', '\.', $value ) . $rule_suffix;
				}
			}
			if ( !empty($rules ) ) {
				$rules[] = "\t";
				$rules[] = "\tOrder Allow,Deny";
				$rules[] = "\tAllow from all";
				$rules[] = "\tDeny from env=spammer";
			}

			return $rules;

		}

	}

}