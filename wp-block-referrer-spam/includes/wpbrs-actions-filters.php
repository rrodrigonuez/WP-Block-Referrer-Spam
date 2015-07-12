<?php

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 *
 * @since      1.0
 * @package    WP_Block_Referrer_Spam
 * @subpackage WP_Block_Referrer_Spam/includes
 *
 */

if ( ! class_exists( 'WPBRS_Actions_Filters' ) ) {

	class WPBRS_Actions_Filters {

		/**
		 * The array of actions registered with WordPress.
		 *
		 * @since    1.0
		 * @access   protected
		 * @var      array    $actions    The actions registered with WordPress to fire when the plugin loads.
		 */
		protected static $actions = array();

		/**
		 * The array of filters registered with WordPress.
		 *
		 * @since    1.0
		 * @access   protected
		 * @var      array    $filters    The filters registered with WordPress to fire when the plugin loads.
		 */
		protected static $filters = array();

		/**
		 * Add a new action to the collection to be registered with WordPress.
		 *
		 * @since    1.0
		 * @param      string               $hook             The name of the WordPress action that is being registered.
		 * @param      object               $component        A reference to the instance of the object on which the action is defined.
		 * @param      string               $callback         The name of the function definition on the $component.
		 * @param      int      Optional    $priority         The priority at which the function should be fired.
		 * @param      int      Optional    $accepted_args    The number of arguments that should be passed to the $callback.
		 */
		public static function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {

			self::$actions = self::add( self::$actions, $hook, $component, $callback, $priority, $accepted_args );
		
		}

		/**
		 * Add a new filter to the collection to be registered with WordPress.
		 *
		 * @since    1.0
		 * @param      string               $hook             The name of the WordPress filter that is being registered.
		 * @param      object               $component        A reference to the instance of the object on which the filter is defined.
		 * @param      string               $callback         The name of the function definition on the $component.
		 * @param      int      Optional    $priority         The priority at which the function should be fired.
		 * @param      int      Optional    $accepted_args    The number of arguments that should be passed to the $callback.
		 */
		public static function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {

			self::$filters = self::add( self::$filters, $hook, $component, $callback, $priority, $accepted_args );
		
		}

		/**
		 * A utility function that is used to register the actions and hooks into a single
		 * collection.
		 *
		 * @since    1.0
		 * @access   private
		 * @param      array                $hooks            The collection of hooks that is being registered (that is, actions or filters).
		 * @param      string               $hook             The name of the WordPress filter that is being registered.
		 * @param      object               $component        A reference to the instance of the object on which the filter is defined.
		 * @param      string               $callback         The name of the function definition on the $component.
		 * @param      int      Optional    $priority         The priority at which the function should be fired.
		 * @param      int      Optional    $accepted_args    The number of arguments that should be passed to the $callback.
		 * @return   type                                   The collection of actions and filters registered with WordPress.
		 */
		private static function add( $hooks, $hook, $component, $callback, $priority, $accepted_args ) {

			$hooks[] = array(
				'hook'          => $hook,
				'component'     => $component,
				'callback'      => $callback,
				'priority'      => $priority,
				'accepted_args' => $accepted_args
			);

			return $hooks;

		}

		/**
		 * Register the filters and actions with WordPress.
		 *
		 * @since    1.0
		 */
		public static function init_actions_filters() {

			if ( !isset( self::$filters ) ) {
				self::$filters = array();
			}
			if ( !isset( self::$actions ) ) {
				self::$actions = array();
			}

			foreach ( self::$filters as $hook ) {
				add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
			}

			foreach ( self::$actions as $hook ) {
				add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
			}

		}

	}

}