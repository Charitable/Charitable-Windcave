<?php
/**
 * Charitable Windcave template.
 *
 * @package   Charitable Windcave/Classes/Charitable_Windcave_Template
 * @copyright Copyright (c) 2020, Studio 164a
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 * @version   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Charitable_Windcave_Template' ) ) :

	/**
	 * Charitable_Windcave_Template
	 *
	 * @since 1.0.0
	 */
	class Charitable_Windcave_Template extends Charitable_Template {

		/**
		 * Set theme template path.
		 *
		 * @since  1.0.0
		 *
		 * @return string
		 */
		public function get_theme_template_path() {
			/**
			 * Customize the directory to use for template files in themes/child themes.
			 *
			 * @since 1.0.0
			 *
			 * @param string $directory The directory, relative to the theme or child theme's root directory.
			 */
			return trailingslashit( apply_filters( 'charitable_windcave_theme_template_path', 'charitable/charitable-windcave' ) );
		}

		/**
		 * Return the base template path.
		 *
		 * @since  1.0.0
		 *
		 * @return string
		 */
		public function get_base_template_path() {
			return charitable_windcave()->get_path( 'templates' );
		}
	}

endif;
