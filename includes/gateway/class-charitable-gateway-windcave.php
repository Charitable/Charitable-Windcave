<?php
/**
 * Windcave Gateway class.
 *
 * @package   Charitable Windcave/Classes/Charitable_Gateway_Windcave
 * @copyright Copyright (c) 2020, Studio 164a
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 * @version   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Charitable_Gateway_Windcave' ) ) :

	/**
	 * Windcave Gateway.
	 *
	 * @since 1.0.0
	 */
	class Charitable_Gateway_Windcave extends Charitable_Gateway {

		/** The gateway ID. */
		const ID = 'windcave';

		/**
		 * Boolean flag recording whether the gateway hooks
		 * have been set up.
		 *
		 * @since 1.0.0
		 *
		 * @var   boolean
		 */
		private static $setup = false;

		/**
		 * Flags whether the gateway requires credit card fields added to the donation form.
		 *
		 * @since 1.0.0
		 *
		 * @var   boolean
		 */
		protected $credit_card_form;

		/**
		 * Instantiate the gateway class, defining its key values.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			/**
			 * Change the Windcave gateway name as its shown in the gateway settings page.
			 *
			 * @since 1.0.0
			 *
			 * @param string $name The gateway name.
			 */
			$this->name = apply_filters( 'charitable_gateway_windcave_name', __( 'Windcave', 'charitable-windcave' ) );

			$this->defaults = [
				'label' => __( 'Windcave', 'charitable-windcave' ),
			];

			$this->supports = [
				'1.3.0',
			];

			$this->setup();
		}

		/**
		 * Set up hooks for the class.
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function setup() {
			if ( self::$setup ) {
				return;
			}

			self::$setup = true;

			/**
			 * Register our new gateway.
			 */
			add_filter( 'charitable_payment_gateways', [ $this, 'register_gateway' ] );

			/**
			 * Process the donation.
			 */
			add_filter( 'charitable_process_donation_windcave', [ $this, 'process_donation' ], 10, 3 );
		}

		/**
		 * Returns the current gateway's ID.
		 *
		 * @since  1.0.0
		 *
		 * @return string
		 */
		public static function get_gateway_id() {
			return self::ID;
		}

		/**
		 * Register gateway settings.
		 *
		 * @since  1.0.0
		 *
		 * @param  array[] $settings Default array of settings for the gateway.
		 * @return array[]
		 */
		public function gateway_settings( $settings ) {
			$settings['userid'] = [
				'type'     => 'text',
				'title'    => __( 'PxPay User ID', 'charitable-windcave' ),
				'priority' => 6,
				'class'    => 'wide',
			];

			$settings['key'] = [
				'type'     => 'text',
				'title'    => __( 'PxPay Key', 'charitable-windcave' ),
				'priority' => 8,
				'class'    => 'wide',
			];

			return $settings;
		}

		/**
		 * Register the payment gateway class.
		 *
		 * @since  1.0.0
		 *
		 * @param  string[] $gateways The list of registered gateways.
		 * @return string[]
		 */
		public function register_gateway( $gateways ) {
			$gateways['windcave'] = 'Charitable_Gateway_Windcave';
			return $gateways;
		}

		/**
		 * Return the keys to use.
		 *
		 * This will return the test keys if test mode is enabled. Otherwise, returns
		 * the production keys.
		 *
		 * @since  1.0.0
		 *
		 * @return string[]
		 */
		public function get_keys() {
			return [
				'key'     => trim( $this->get_value( 'key' ) ),
				'user_id' => trim( $this->get_value( 'user_id' ) ),
			];
		}

		/**
		 * Process the donation with the gateway.
		 *
		 * @since  1.0.0
		 *
		 * @param  mixed                         $return      Response to be returned.
		 * @param  int                           $donation_id The donation ID.
		 * @param  Charitable_Donation_Processor $processor   Donation processor object.
		 * @return boolean|array
		 */
		public function process_donation( $return, $donation_id, $processor ) {
			$gateway_processor = new Charitable_Windcave_Gateway_Processor( $donation_id, $processor );

			return $gateway_processor->run();

			// API keys
			// $keys        = $gateway->get_keys();

			// Donation fields
			// $donation_key = $donation->get_donation_key();
			// $item_name    = sprintf( __( 'Donation %d', 'charitable-payu-money' ), $donation->ID );;
			// $description  = $donation->get_campaigns_donated_to();
			// $amount 	  = $donation->get_total_donation_amount( true );

			// Donor fields
			// $first_name   = $donor->get_donor_meta( 'first_name' );
			// $last_name    = $donor->get_donor_meta( 'last_name' );
			// $address      = $donor->get_donor_meta( 'address' );
			// $address_2    = $donor->get_donor_meta( 'address_2' );
			// $email 		  = $donor->get_donor_meta( 'email' );
			// $city         = $donor->get_donor_meta( 'city' );
			// $state        = $donor->get_donor_meta( 'state' );
			// $country      = $donor->get_donor_meta( 'country' );
			// $postcode     = $donor->get_donor_meta( 'postcode' );
			// $phone        = $donor->get_donor_meta( 'phone' );

			// URL fields
			// $return_url = charitable_get_permalink( 'donation_receipt_page', [ 'donation_id' => $donation->ID ] );
			// $cancel_url = charitable_get_permalink( 'donation_cancel_page', [ 'donation_id' => $donation->ID ] );
			// $notify_url = function_exists( 'charitable_get_ipn_url' )
			// 	? charitable_get_ipn_url( Charitable_Gateway_Sparrow::ID )
			// 	: Charitable_Donation_Processor::get_instance()->get_ipn_url( Charitable_Gateway_Sparrow::ID );

			// Credit card fields
			// $cc_expiration = $this->get_gateway_value( 'cc_expiration', $values );
			// $cc_number     = $this->get_gateway_value( 'cc_number', $values );
			// $cc_year       = $cc_expiration['year'];
			// $cc_month      = $cc_expiration['month'];
			// $cc_cvc		   = $this->get_gateway_value( 'cc_cvc', $values );

			/**
			 * Create donation charge through gateway.
			 *
			 * @todo
			 *
			 * You should return one of three values.
			 *
			 * 1. If the donation fails to be processed and the user should be
			 *    returned to the donation page, return false.
			 * 2. If the donation succeeds and the user should be directed to
			 *    the donation receipt, return true.
			 * 3. If the user should be redirected elsewhere (for example,
			 *    a gateway-hosted payment page), you should return an array
			 *    like this:

				[
					'redirect' => $redirect_url,
					'safe' => false // Set to false if you are redirecting away from the site.
				];
			 *
			 */

			return true;
		}

		/**
		 * Process an IPN request.
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public static function process_ipn() {
			/**
			 * Process the IPN.
			 *
			 * @todo
			 */
		}
	}

endif;
