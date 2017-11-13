<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Lm_Admin_Calendar
 * @subpackage Lm_Admin_Calendar/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Lm_Admin_Calendar
 * @subpackage Lm_Admin_Calendar/admin
 * @author     Leonidas Milosis <leonidas.milosis@gmail.com>
 */
class Lm_Admin_Calendar_Admin {

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
		 * defined in Lm_Admin_Calendar_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Lm_Admin_Calendar_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/lm-admin-calendar-admin.css', array(), $this->version, 'all' );

		wp_enqueue_style(  'fullcalendar', 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.6.2/fullcalendar.min.css', array(), $this->version, false );

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
		 * defined in Lm_Admin_Calendar_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Lm_Admin_Calendar_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/lm-admin-calendar-admin.js', array( 'fullcalendar' ), $this->version, false );

		// Include the clndr library for the implementation of the calendar.
		wp_enqueue_script( 'fullcalendar', 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.6.2/fullcalendar.min.js', array( 'momentjs' ), $this->version, false );

		// Include the moment.js library as the fullcalendar library is dependent on it.
		wp_enqueue_script( 'momentjs', 'http://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Create the Dashboard metabox where the calendar will be displayed.
	 *
	 * @since    1.0.0
	 */
	public function create_dashboard_calendar() {

		wp_add_dashboard_widget(
			'my_admin_calendar_widget',				// Widget slug.
			'My Admin Calendar',					// Title.
			array( $this, 'my_admin_calendar_widget_function' )	// Display function.
		);
	}

	/**
	 * Display the content of the My Admin Calendar widget.
	 *
	 * @since    1.0.0
	 */
	public function my_admin_calendar_widget_function() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/lm-admin-calendar-admin-display.php';
	}


	/**
	 * Load the events to display them in the Calendar Widget.
	 *
	 * @since    1.0.0
	 */
	public function load_events() {
	    $response = array();

	    $args = array( 'posts_per_page' => -1, 'post_type' => 'post' );

		$myposts = get_posts( $args );

		if($myposts) {
			echo json_encode(array('success' => true, 'result' => $myposts));
		}
		else {
			wp_send_json_error();
	    }

	    wp_die(); 
	}

}
