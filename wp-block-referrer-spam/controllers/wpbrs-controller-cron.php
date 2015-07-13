<?php

/**
 * Controller class that implements Plugin Cron Job tasks
 *
 * @since      1.0
 * @package    WP_Block_Referrer_Spam
 * @subpackage WP_Block_Referrer_Spam/controllers
 *
 */

if ( ! class_exists( 'WPBRS_Controller_Cron' ) ) {

	class WPBRS_Controller_Cron extends WPBRS_Controller {

		const WEEKLY_CRON 			= 'wpbrs_weekly';
		const CHECK_UPDATES_TASK 	= 'check_referrers_updates';


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

			WPBRS_Actions_Filters::add_action( 'check_referrers_updates', 	$this, 'check_referrers_updates_cron_job' );
			WPBRS_Actions_Filters::add_action( 'cron_schedules', 			__CLASS__, 'add_custom_cron_intervals' );

		}

		/**
		 * WP-Cron job
		 *
		 * @since    1.0
		 */
		public function check_referrers_updates_cron_job() {

			return self::get_model()->update_from_URL();

		}

		/**
		 * Adds custom intervals to the cron schedule.
		 *
		 * @since    1.0
		 * @param array $schedules
		 * @return array
		 */
		public static function add_custom_cron_intervals( $schedules ) {

			// add custom weekly interval
			$schedules[ self::WEEKLY_CRON ] = array(
				'interval' => 60 * 10080,
				'display'  => __( 'Once Weekly' )
			);

			return $schedules;

		}

		/**
		 * Register plugin cron jobs.
		 *
		 * @since    1.0
		 */
		public static function register_cron_jobs() {

			if( !wp_next_scheduled( self::CHECK_UPDATES_TASK ) ) {
				add_action( 'cron_schedules', array( __CLASS__, 'add_custom_cron_intervals' ) );
				wp_schedule_event( time(), self::WEEKLY_CRON, self::CHECK_UPDATES_TASK );
			}

		}

		/**
		 * Unregister plugin cron jobs.
		 *
		 * @since    1.0
		 */
		public static function unregister_cron_jobs() {

			if( wp_next_scheduled( self::CHECK_UPDATES_TASK ) ) {
				wp_clear_scheduled_hook( self::CHECK_UPDATES_TASK );
			}

		}
	
	}

}