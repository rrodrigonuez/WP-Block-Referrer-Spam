<?php

/**
 * Controller class that implements Plugin Admin Notices messages
 *
 * @since      1.0
 * @package    WP_Block_Referrer_Spam
 * @subpackage WP_Block_Referrer_Spam/controllers
 *
 */

if ( ! class_exists( 'WPBRS_Controller_Admin_Notices' ) ) {

	class WPBRS_Controller_Admin_Notices extends WPBRS_Controller {

		/**
		 * Constructor
		 *
		 * @since    1.0
		 */
		protected function __construct() {

			$this->register_hook_callbacks();
			$this->model = WPBRS_Model_Admin_Notices::get_instance();

		}

		/**
		 * Register callbacks for actions and filters
		 *
		 * @since    1.0
		 */
		protected function register_hook_callbacks() {

			WPBRS_Actions_Filters::add_action( 'admin_notices', $this, 'show_admin_notices' );

		}

		/**
		 * WP-Cron job
		 *
		 * @since    1.0
		 */
		public function show_admin_notices() {

			return self::get_model()->show_admin_notices();

		}
	
	}

}