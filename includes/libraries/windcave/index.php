<?php
/**
 * File responsible for loading including all the required Windcave classes.
 *
 * All of the included classes are based on the sample code provided by Windcave,
 * with minor cosmetic alterations in terms of how they are structured.
 *
 * @package   Windcave
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

require_once( 'MifMessage.php' );
require_once( 'pxpay/PxPay_Curl.php' );
require_once( 'pxpay/PxPayMessage.php' );
require_once( 'pxpay/PxPayRequest.php' );
require_once( 'pxpay/PxPayResponse.php' );
require_once( 'pxpay/PxPayWordPress.php' );
require_once( 'pxpost/PxPostMessage.php' );
require_once( 'pxpost/PxPostRequest.php' );
require_once( 'pxpost/PxPostResponse.php' );
require_once( 'pxpost/PxPostWordPress.php' );
