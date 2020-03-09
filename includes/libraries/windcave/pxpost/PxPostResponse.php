<?php
namespace Charitable_Windcave;

/**
 * Class for PxPost response messages.
 *
 * @package   PxPost/Classes/PxPostRequest
 * @author    Eric Daams
 * @copyright Copyright (c) 2019, Studio 164a
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 * @version   2.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PxPostResponse extends PxPostMessage {

	public function __construct( $xml ) {
		parent::__construct();

		$this->msg = new MifMessage( $xml );
	}

	/**
	 * Get response value for a particular element.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $element The element to get.
	 * @return mixed
	 */
	public function __get( $element ) {
		return $this->msg->get_element_text( $element );
	}

	/**
	 * Route all method calls to the MifMessage object.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $method The method called.
	 * @param  array  $args   The arguments passed to the method.
	 * @return mixed
	 */
	public function __call( $method, $args ) {
		return call_user_func( [ $this->msg, $method ], $args );
	}
}