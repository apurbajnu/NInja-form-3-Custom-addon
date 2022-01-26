<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://apurba.me
 * @since             1.0.0
 * @package           Nf_custom_addon
 *
 * @wordpress-plugin
 * Plugin Name:       NF_CUSTOM_ADDON
 * Plugin URI:        https://bestdecoder.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Apurba Podder
 * Author URI:        https://apurba.me
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       nf_custom_addon
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'NF_CUSTOM_ADDON_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-nf_custom_addon-activator.php
 */
function activate_nf_custom_addon() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nf_custom_addon-activator.php';
	Nf_custom_addon_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-nf_custom_addon-deactivator.php
 */
function deactivate_nf_custom_addon() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nf_custom_addon-deactivator.php';
	Nf_custom_addon_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_nf_custom_addon' );
register_deactivation_hook( __FILE__, 'deactivate_nf_custom_addon' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-nf_custom_addon.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_nf_custom_addon() {

	$plugin = Nf_custom_addon::instance();



}
run_nf_custom_addon();
