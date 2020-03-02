<?php
/**
 * A helper class for logging deprecated arguments, functions and methods.
 *
 * @package   Charitable Windcave/Classes/Charitable_Windcave_Deprecated
 * @author    Eric Daams
 * @copyright Copyright (c) 2020, Studio 164a
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 * @version   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Charitable_Windcave_Deprecated' ) ) :

	/**
	 * Charitable_Deprecated
	 *
	 * @since 1.0.0
	 */
	class Charitable_Windcave_Deprecated extends Charitable_Deprecated {

		/**
		 * One true class object.
		 *
		 * @since 1.0.0
		 *
		 * @var   Charitable_Windcave_Deprecated
		 */
		private static $instance = null;

		/**
		 * Create class object. Private constructor.
		 *
		 * @since 1.0.0
		 */
		private function __construct() {
			$this->context = 'Charitable Windcave';
		}

		/**
		 * Create and return the class object.
		 *
		 * @since  1.0.0
		 *
		 * @return Charitable_Windcave_Deprecated
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}

endif;
