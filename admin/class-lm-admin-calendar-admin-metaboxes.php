<?php
/**
 * The metabox-specific functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Lm_Admin_Calendar
 * @subpackage Lm_Admin_Calendar/admin
 */

/**
 * The metabox-specific functionality of the plugin.
 *
 * @package    Lm_Admin_Calendar
 * @subpackage Lm_Admin_Calendar/admin
 * @author     Leonidas Milosis <leonidas.milosis@gmail.com>
 */
class Lm_Admin_Calendar_Admin_Metaboxes {

	/**
	 * The post meta data
	 *
	 * @since  1.0.0
	 * @access private
	 * @var string $meta The post meta data.
	 */
	private $meta;

	/**
	 * The ID of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		$this->set_meta();

	}

	/**
	 * Registers metaboxes with WordPress
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function add_metaboxes() {

		add_meta_box(
			'lm_admin_calendar_metabox',
			__( 'Event Details', 'lm-admin-calendar' ),
			array( $this, 'metabox' ),
			'lmac_event',
			'side',
			'default'
		);

	}



	/**
	 * Check each nonce. If any don't verify, $nonce_check is increased.
	 * If all nonces verify, returns 0.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array $posted The _POST.
	 * @return int The value of $nonce_check
	 */
	private function check_nonces( $posted ) {

		$nonces = array();
		$nonce_check = 0;

		$nonces[] = 'event_details';

		foreach ( $nonces as $nonce ) {

			if ( ! isset( $posted[ $nonce ] ) ) {
				$nonce_check++;
			}
			if ( isset( $posted[ $nonce ] ) && ! wp_verify_nonce( $posted[ $nonce ], $this->plugin_name ) ) {
				$nonce_check++;
			}
		}

		return $nonce_check;

	}

	/**
	 * Returns an array of the all the metabox fields and their respective types
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array Metabox fields and types
	 */
	private function get_metabox_fields() {

		$fields = array();

		$fields[] = array( 'lmac_event_date', 'date' );
		$fields[] = array( 'lmac_event_user', 'text' );

		return $fields;

	}

	/**
	 * Calls a metabox file specified in the add_meta_box args.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object $post The Post.
	 * @return void
	 */
	public function metabox( $post ) {

		wp_nonce_field( $this->plugin_name, 'event_details' );

		$values = get_post_custom( $post->ID );
		$event_date = isset( $values['lmac_event_date'] ) ? esc_attr( $values['lmac_event_date'][0] ) : '';
		$event_user = isset( $values['lmac_event_user'] ) ? esc_attr( $values['lmac_event_user'][0] ) : '';

		include( plugin_dir_path( __FILE__ ) . 'partials/lm-admin-calendar-admin-metabox-event-options.php' );

	}

	/**
	 * Sanitizes the data from the metabox.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string $type The field type for this data.
	 * @param  mixed  $data The data to sanitize.
	 * @return string
	 */
	private function sanitizer( $type, $data ) {

		if ( empty( $type ) ) {
			return;
		}
		if ( empty( $data ) ) {
			return;
		}

		$return = '';
		$sanitizer = new Lm_Admin_Calendar_Sanitize();

		$sanitizer->set_data( $data );
		$sanitizer->set_type( $type );

		$return = $sanitizer->clean();

		unset( $sanitizer );

		return $return;

	}

	/**
	 * Sets the class variable $options
	 */
	public function set_meta() {

		global $post;

		if ( empty( $post ) ) {
			return;
		}
		if ( 'lmac_event' != $post->post_type ) {
			return;
		}

		$this->meta = get_post_custom( $post->ID );

	}

	/**
	 * Saves metabox data
	 *
	 * Repeater section works like this:
	 * Loops through meta fields
	 * Loops through submitted data
	 * Sanitizes each field into $clean array
	 * Gets max of $clean to use in FOR loop
	 * FOR loops through $clean, adding each value to $new_value as an array
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  int    $post_id The post ID.
	 * @param  object $object  The post object.
	 * @return void
	 */
	public function validate_meta( $post_id, $object ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		if ( 'lmac_event' !== $object->post_type ) {
			return;
		}

		$nonce_check = $this->check_nonces( $_POST );

		if ( 0 < $nonce_check ) {
			return;
		}

		$metas = $this->get_metabox_fields();

		foreach ( $metas as $meta ) {

			$name = $meta[0];
			$type = $meta[1];

			$new_value[ $name ] = $this->sanitizer( $type, $_POST[ $name ] );

			update_post_meta( $post_id, $name, $new_value[ $name ] );
		}

	}

}
