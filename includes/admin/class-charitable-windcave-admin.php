<?php
/**
 * The class responsible for adding & saving extra settings in the Charitable admin.
 *
 * @package   Charitable Windcave/Classes/Charitable_Windcave_Admin
 * @copyright Copyright (c) 2020, Studio 164a
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 * @version   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Charitable_Windcave_Admin' ) ) :

	/**
	 * Charitable_Windcave_Admin
	 *
	 * @since 1.0.0
	 */
	class Charitable_Windcave_Admin {

		/**
		 * The single static class instance.
		 *
		 * @since 1.0.0
		 *
		 * @var   Charitable_Windcave_Admin
		 */
		private static $instance = null;

		/**
		 * Create and return the class object.
		 *
		 * @since 1.0.0
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new Charitable_Windcave_Admin();
			}

			return self::$instance;
		}

		/**
		 * Set up the class.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			if ( ! is_null( self::$instance ) ) {
				return;
			}

			self::$instance = $this;

			/**
			 * Add a direct link to the Extensions settings page from the plugin row.
			 */
			add_filter( 'plugin_action_links_' . plugin_basename( charitable_windcave()->get_path() ), [ $this, 'add_plugin_action_links' ] );

			/**
			 * Add a "Windcave" section to the Extensions settings area of Charitable.
			 */
			add_filter( 'charitable_settings_tab_fields_extensions', [ $this, 'add_windcave_settings' ], 6 );

		}

		/**
		 * Add custom links to the plugin actions.
		 *
		 * @since  1.0.0
		 *
		 * @param  string[] $links Links to be added to plugin actions row.
		 * @return string[]
		 */
		public function add_plugin_action_links( $links ) {
			$links[] = '<a href="' . admin_url( 'admin.php?page=charitable-settings&tab=extensions' ) . '">' . __( 'Settings', 'charitable-newsletter-connect' ) . '</a>';
			return $links;
		}

		/**
		 * Add settings to the Extensions settings tab.
		 *
		 * @since  1.0.0
		 *
		 * @param  array[] $fields Settings to display in tab.
		 * @return array[]
		 */
		public function add_windcave_settings( $fields = array() ) {
			if ( ! charitable_is_settings_view( 'extensions' ) ) {
				return $fields;
			}

			$custom_fields = [
				'section_windcave'          => [
					'title'    => __( 'Windcave', 'charitable-windcave' ),
					'type'     => 'heading',
					'priority' => 50,
				],
				'windcave_setting_text'     => [
					'title'    => __( 'Text Field Setting', 'charitable-windcave' ),
					'type'     => 'text',
					'priority' => 50.2,
					'default'  => __( '', 'charitable-windcave' ),
				],
				'windcave_setting_checkbox' => [
					'title'    => __( 'Checkbox Setting', 'charitable-windcave' ),
					'type'     => 'checkbox',
					'priority' => 50.6,
					'default'  => false,
					'help'     => __( '', 'charitable-windcave' ),
				],
			];

			$fields = array_merge( $fields, $custom_fields );

			return $fields;
		}
	}

endif;
