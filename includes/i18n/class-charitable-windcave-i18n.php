<?php
/**
 * Sets up translations for Charitable Windcave.
 *
 * @package   Charitable/Classes/Charitable_i18n
 * @copyright Copyright (c) 2020, Studio 164a
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 * @version   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Ensure that Charitable_i18n exists */
if ( ! class_exists( 'Charitable_i18n' ) ) :
	return;
endif;

if ( ! class_exists( 'Charitable_Windcave_i18n' ) ) :

	/**
	 * Charitable_Windcave_i18n
	 *
	 * @since 1.0.0
	 */
	class Charitable_Windcave_i18n extends Charitable_i18n {

		/**
		 * Plugin textdomain.
		 *
		 * @since 1.0.0
		 *
		 * @var   string
		 */
		protected $textdomain = 'charitable-windcave';

		/**
		 * Set up the class.
		 *
		 * @since 1.0.0
		 */
		protected function __construct() {
			/**
			 * Customize the directory to use for translation files.
			 *
			 * @since 1.0.0
			 *
			 * @param string $directory The directory, relative to the WP_PLUGIN_DIR directory.
			 */
			$this->languages_directory = apply_filters( 'charitable_windcave_languages_directory', 'charitable-windcave/languages' );

			$this->locale = apply_filters( 'plugin_locale', get_locale(), $this->textdomain );
			$this->mofile = sprintf( '%1$s-%2$s.mo', $this->textdomain, $this->locale );

			$this->load_textdomain();
		}
	}

endif;
