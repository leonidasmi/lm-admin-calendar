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
	 * @uses 	wp_enqueue_style()
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
    	wp_enqueue_style( 'jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 * @uses 	wp_enqueue_script()
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/lm-admin-calendar-admin.js', array( 'fullcalendar', 'jquery-ui-core', 'jquery' ), $this->version, false );

		wp_enqueue_script('jquery-ui-dialog');

		// Include the fullcalendar library for the implementation of the calendar.
		wp_enqueue_script( 'fullcalendar', 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.6.2/fullcalendar.min.js', array( 'momentjs' ), $this->version, false );

		// Include the moment.js library as the fullcalendar library is dependent on it.
		wp_enqueue_script( 'momentjs', 'http://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Create the Dashboard metabox where the calendar will be displayed.
	 *
	 * @since    1.0.0
	 * @uses 	wp_add_dashboard_widget()
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
	 * @access 	public
	 */
	public function my_admin_calendar_widget_function() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/lm-admin-calendar-admin-display.php';
	}


	/**
	 * Load the events to display them in the Calendar Widget.
	 *
	 * @since    1.0.0
	 * @access 	public
	 */
	public function load_events() {
	    $response = array();

	    $args = array( 'posts_per_page' => -1, 'post_type' => 'lmac_event' );

		$all_events = get_posts( $args );

		$events = array();
		$category_list = array();
		$i = 0;
		foreach ($all_events as $event) {
			if ( get_post_meta($event->ID, 'lmac_event_user', true) == get_current_user_id() ) {
				
				$events[$i]['id'] = $event->ID;
				$events[$i]['title'] = $event->post_title;
				$events[$i]['description'] = $event->post_content;
				$events[$i]['start'] = get_post_meta($event->ID, 'lmac_event_date', true);

				$category_list = wp_get_post_terms($event->ID, 'lmac_event_category', array("fields" => "names"));	
				$events[$i]['categories'] = '';
				foreach ( $category_list as $category ) {
					$events[$i]['categories'] .= $category . ', ';
				}
				$events[$i]['categories'] = trim( $events[$i]['categories'] , ', ');
			}
			$i++;
		}

		if($all_events) {
			echo json_encode(array('success' => true, 'result' => $events));
		}
		else {
			wp_send_json_error();
	    }

	    wp_die(); 
	}

	/**
	 * Create Post Type: Events
	 *
	 * @since 	1.0.0
	 * @access 	public
	 * @uses 	register_post_type()
	 */
	public static function new_cpt_event() {
		$plural 	= 'Events';
		$single 	= 'Event';
		$cpt_name 	= 'lmac_event';
		$opts['can_export']								= TRUE;
		$opts['description']							= 'Events are a custom post type that is used for the calendar widget in the admin dashboard';
		$opts['menu_icon']								= 'dashicons-calendar';
		$opts['public']									= TRUE;
		$opts['rewrite']								= FALSE;
		$opts['register_meta_box_cb']					= '';
		$opts['show_in_admin_bar'] 						= TRUE;
		$opts['show_in_menu'] 							= TRUE;
		$opts['show_in_nav_menu'] 						= TRUE;
		$opts['supports']								= array( 'title', 'editor' );
		$opts['taxonomies']								= array();
		$opts['labels']['add_new']						= esc_html__( "Add New {$single}", 'lm-admin-calendar' );
		$opts['labels']['add_new_item']					= esc_html__( "Add New {$single}", 'lm-admin-calendar' );
		$opts['labels']['all_items']					= esc_html__( $plural, 'lm-admin-calendar' );
		$opts['labels']['edit_item']					= esc_html__( "Edit {$single}" , 'lm-admin-calendar' );
		$opts['labels']['menu_name']					= esc_html__( $plural, 'lm-admin-calendar' );
		$opts['labels']['name']							= esc_html__( $plural, 'lm-admin-calendar' );
		$opts['labels']['name_admin_bar']				= esc_html__( $single, 'lm-admin-calendar' );
		$opts['labels']['new_item']						= esc_html__( "New {$single}", 'lm-admin-calendar' );
		$opts['labels']['not_found']					= esc_html__( "No {$plural} Found", 'lm-admin-calendar' );
		$opts['labels']['not_found_in_trash']			= esc_html__( "No {$plural} Found in Trash", 'lm-admin-calendar' );
		$opts['labels']['search_items']					= esc_html__( "Search {$plural}", 'lm-admin-calendar' );
		$opts['labels']['singular_name']				= esc_html__( $single, 'lm-admin-calendar' );
		$opts['labels']['view_item']					= esc_html__( "View {$single}", 'lm-admin-calendar' );

		register_post_type( strtolower( $cpt_name ), $opts );
	}

	/**
	 * Creates a new taxonomy for a custom post type
	 *
	 * @since 	1.0.0
	 * @access 	public
	 * @uses 	register_taxonomy()
	 */
	public static function new_taxonomy_category() {
		$plural 	= 'Categories';
		$single 	= 'Category';
		$tax_name 	= 'lmac_event_category';
		$opts['hierarchical']							= TRUE;
		$opts['query_var']								= $tax_name;
		$opts['labels']['add_new_item'] 				= esc_html__( "Add New {$single}", 'lm-admin-calendar' );
		$opts['labels']['add_or_remove_items'] 			= esc_html__( "Add or remove {$plural}", 'lm-admin-calendar' );
		$opts['labels']['all_items'] 					= esc_html__( $plural, 'lm-admin-calendar' );
		$opts['labels']['choose_from_most_used'] 		= esc_html__( "Choose from most used {$plural}", 'lm-admin-calendar' );
		$opts['labels']['edit_item'] 					= esc_html__( "Edit {$single}" , 'lm-admin-calendar');
		$opts['labels']['menu_name'] 					= esc_html__( $plural, 'now-hiring' );
		$opts['labels']['name'] 						= esc_html__( $plural, 'now-hiring' );
		$opts['labels']['new_item_name'] 				= esc_html__( "New {$single} Name", 'lm-admin-calendar' );
		$opts['labels']['not_found'] 					= esc_html__( "No {$plural} Found", 'lm-admin-calendar' );
		$opts['labels']['parent_item'] 					= esc_html__( "Parent {$single}", 'lm-admin-calendar' );
		$opts['labels']['parent_item_colon'] 			= esc_html__( "Parent {$single}:", 'lm-admin-calendar' );
		$opts['labels']['popular_items'] 				= esc_html__( "Popular {$plural}", 'lm-admin-calendar' );
		$opts['labels']['search_items'] 				= esc_html__( "Search {$plural}", 'lm-admin-calendar' );
		$opts['labels']['separate_items_with_commas'] 	= esc_html__( "Separate {$plural} with commas", 'lm-admin-calendar' );
		$opts['labels']['singular_name'] 				= esc_html__( $single, 'lm-admin-calendar' );
		$opts['labels']['update_item'] 					= esc_html__( "Update {$single}", 'lm-admin-calendar' );
		$opts['labels']['view_item'] 					= esc_html__( "View {$single}", 'lm-admin-calendar' );

		register_taxonomy( $tax_name, 'lmac_event', $opts );
	}


}
