<?php
namespace Charitable_Windcave;

/**
 * Abstract base class for PxPost messages.
 *
 * These are messages with certain defined elements, which can be serialized to XML.
 *
 * @package   PxPost/Classes/PxPostMessage
 * @author    Windcave DevSupport, Eric Daams
 * @copyright Windcave 2017(c)
 * @since     1.0.0
 * @version   2.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class PxPostMessage {
	public function __construct() {
	}

	/**
	 * Set request value for a particular element.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $element The element to set.
	 * @param  mixed  $value   The value to set it to.
	 * @return mixed
	 */
	public function __set( $element, $value ) {
		return $this->$element = $value;
	}

	public function toXml() {
		$xml = '<Txn>';

		foreach ( get_object_vars( $this ) as $prop => $val ) {
			if ( empty( $val ) ) {
				continue;
			}

			$xml .= "<$prop>$val</$prop>";
		}

		$xml .= '</Txn>';

		return $xml;
	}
}
