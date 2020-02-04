<?php
/**
 * Charitable Windcave Gateway Hooks.
 *
 * @package   Charitable Windcave/Hooks/Gateway
 * @copyright Copyright (c) 2020, Studio 164a
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 * @version   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register our new gateway.
 *
 * @see Charitable_Gateway_Windcave::register_gateway()
 */
add_filter( 'charitable_payment_gateways', [ 'Charitable_Gateway_Windcave', 'register_gateway' ] );

/**
 * Validate the donation form submission before processing.
 *
 * @see Charitable_Gateway_Windcave::validate_donation()
 */
add_filter( 'charitable_validate_donation_form_submission_gateway', [ 'Charitable_Gateway_Windcave', 'validate_donation' ], 10, 3 );

/**
 * Process the donation.
 *
 * @see Charitable_Gateway_Windcave::process_donation()
 */
add_filter( 'charitable_process_donation_windcave', [ 'Charitable_Gateway_Windcave', 'process_donation' ], 10, 3 );
