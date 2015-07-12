<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.twomandarins.com
 * @since      1.0.0
 *
 * @package    WP_Block_Referrer_Spam
 * @subpackage WP_Block_Referrer_Spam/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WP_Block_Referrer_Spam
 * @subpackage WP_Block_Referrer_Spam/admin
 * @author     Your Name <email@example.com>
 */
class WP_Block_Referrer_Spam_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Holds the values to be used in the fields callbacks
	 * 
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $options    The current options of this plugin.
	 */
	private $options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WP_Block_Referrer_Spam_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WP_Block_Referrer_Spam_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-block-referrer-spam-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WP_Block_Referrer_Spam_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WP_Block_Referrer_Spam_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-block-referrer-spam-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Initialize Plugin registering its settings
	 *
	 * @since    1.0.0
	 */
	public function admin_init() {

		register_setting(
			'wp_block_referrer_spam_group', // Option group
			'wp_block_referrer_spam', // Option name
			array( $this, 'sanitize' ) // Sanitize
		);

		add_settings_section(
			'wp_block_referrer_spam_section', // ID
			__( 'WP Block Referrer Options' ), // Title
			array( $this, 'print_section_info' ), // Callback
			$this->plugin_name // Page
		);

		add_settings_field(
			'referrer_spam_list', // ID
			__( 'List of Referrers to Block:' ), // Title 
			array( $this, 'referrer_spam_list_callback' ), // Callback
			$this->plugin_name, // Page
			'wp_block_referrer_spam_section' // Section ID
		);

	}

	/**
	 * Initialize Plugin registering its settings
	 *
	 * @since    1.0.0
	 * @param array $input Contains all settings fields as array keys
	 */
	public function sanitize( $input )
	{

		$new_input = array();

		if ( isset( $input['referrer_spam_list'] ) && $input['referrer_spam_list'] != '' ) {
			$list = $input['referrer_spam_list'];

			$new_input['referrer_spam_list'] = maybe_serialize( explode( "\n", str_replace( "\r", "", $list ) ) );
		}

		// Return the array processing any additional functions filtered by this action
		return apply_filters( 'sanitize', $new_input, $input );

	}

	/** 
	 * Print the Section text
	 *
	 * @since    1.0.0
	 */
	public function print_section_info() {

		print __( 'Enter the number of revisions that you want to save, 0 to disable revisions:' );

	}

	/** 
	 * Get the settings option array and print one of its values
	 *
	 * @since    1.0.0
	 */
	public function referrer_spam_list_callback() {

		$list = isset( $this->options['referrer_spam_list'] ) ? esc_attr( $this->options['referrer_spam_list'] ) : array();
		$list = implode( "\n", maybe_unserialize( $list ) );

		printf(
			'<textarea id="referrer_spam_list" name="wp_block_referrer_spam[referrer_spam_list]" rows="5" cols="50">%s</textarea>',
			$list
		);

	}

	/** 
	 * Create menu for Plugin inside Settings menu
	 *
	 * @since    1.0.0
	 */
	public function plugin_menu() {

		add_options_page( __( 'WP Block Referrer Spam' ), __( 'WP Block Referrer Spam' ), 'manage_options', $this->plugin_name, array( $this, 'admin_page' ) );
	
	}

	/** 
	 * Render Plugin Options Page
	 *
	 * @since    1.0.0
	 */
	public function admin_page() {

		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		$wp_config_file = $_SERVER["DOCUMENT_ROOT"] . '/wp-config.php';
		$contents = file_get_contents( $wp_config_file );
		$pattern = "define\(( )?'WP_POST_REVISIONS'";
		$pattern = "/^$pattern.*/m";

		if ( !preg_match_all( $pattern, $contents, $matches ) ) {
			require_once 'partials/wp-block-referrer-spam-admin-display.php';
		} else {
			wp_die( __( 'Constant WP_POST_REVISIONS is already defined in wp-config.php file, remove it to be able to set up a limit for your post revisions.' ) );
		}

	}

	/** 
	 * Add Seetings link on acction links
	 *
	 * @since    1.0.0
	 */
	public function plugin_action_links( $links ) {

		$settings_link = '<a href="' . esc_url( $this->get_page_url() ) . '">' . __( 'Settings' ) . '</a>';
		array_unshift( $links, $settings_link );

		return $links;

	}

	/** 
	 * Helper to load Plugin Option from DB
	 *
	 * @since    1.0.0
	 */
	public function load_options() {

		if ( !isset( $this->options ) ) {
			$this->options = get_option( 'wp_block_referrer_spam' );
		}

		return $this->options;

	}

	/** 
	 * Helper to build Page Options URL
	 *
	 * @since    1.0.0
	 */
	private function get_page_url() {

		return admin_url( 'options-general.php?page=' . $this->plugin_name );

	}

}
