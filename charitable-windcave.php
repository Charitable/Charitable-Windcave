<?php
/**
 * Plugin Name:       Charitable - Windcave
 * Plugin URI:        https://www.wpcharitable.com/extensions/charitable-windcave
 * Description:       Accept donations securely with Windcave.
 * Version:           1.0.2
 * Author:            WP Charitable
 * Author URI:        https://www.wpcharitable.com
 * Requires at least: 5.0
 * Tested up to:      5.4.1
 *
 * Text Domain: charitable-windcave
 * Domain Path: /languages/
 *
 * @package  Charitable Windcave
 * @category Core
 * @author   WP Charitable
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load plugin class, but only if Charitable is found and activated.
 *
 * @return false|Charitable_Windcave Whether the class was loaded.
 */
add_action(
	'plugins_loaded',
	function() {
		require_once( 'includes/class-charitable-windcave.php' );

		/* Check for Charitable */
		if ( ! class_exists( 'Charitable' ) ) {
			if ( ! class_exists( 'Charitable_Extension_Activation' ) ) {
				require_once 'includes/admin/class-charitable-extension-activation.php';
			}

			$activation = new Charitable_Extension_Activation( plugin_dir_path( __FILE__ ), basename( __FILE__ ) );
			$activation = $activation->run();
		} else {
			new Charitable_Windcave( __FILE__ );
		}
	}
);
