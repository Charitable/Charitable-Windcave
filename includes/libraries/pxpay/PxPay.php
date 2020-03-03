<?php
/**
 * File responsible for loading including all the required Windcave PxPay classes.
 *
 * All of the included classes are based on the sample code provided by Windcave,
 * with minor cosmetic alterations in terms of how they are structured.
 *
 * @package   PxPay
 * @author    Eric Daams
 * @copyright Copyright (c) 2019, Studio 164a
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 * @version   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( 'MifMessage.php' );
require_once( 'PxPay_Curl.php' );
require_once( 'PxPayMessage.php' );
require_once( 'PxPayRequest.php' );
require_once( 'PxPayResponse.php' );
