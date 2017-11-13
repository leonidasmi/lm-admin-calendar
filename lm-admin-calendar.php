<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              #
 * @since             1.0.0
 * @package           Lm_Admin_Calendar
 *
 * @wordpress-plugin
 * Plugin Name:       LM Admin Calendar
 * Plugin URI:        #
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Leonidas Milosis
 * Author URI:        #
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       lm-admin-calendar
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-lm-admin-calendar-activator.php
 */
function activate_lm_admin_calendar() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-lm-admin-calendar-activator.php';
	Lm_Admin_Calendar_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-lm-admin-calendar-deactivator.php
 */
function deactivate_lm_admin_calendar() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-lm-admin-calendar-deactivator.php';
	Lm_Admin_Calendar_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_lm_admin_calendar' );
register_deactivation_hook( __FILE__, 'deactivate_lm_admin_calendar' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-lm-admin-calendar.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_lm_admin_calendar() {

	$plugin = new Lm_Admin_Calendar();
	$plugin->run();

}
run_lm_admin_calendar();
