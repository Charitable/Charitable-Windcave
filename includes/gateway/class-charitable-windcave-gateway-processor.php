<?php
/**
 * Responsible for processing payments through Windcave.
 *
 * @package   Charitable Windcave/Classes/Charitable_Windcave_Gateway_Processor
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

if ( ! class_exists( 'Charitable_Windcave_Gateway_Processor' ) ) :

	/**
	 * Charitable_Windcave_Gateway_Processor
	 *
	 * @since 1.0.0
	 */
	class Charitable_Windcave_Gateway_Processor {

		/**
		 * The donation object.
		 *
		 * @since 1.0.0
		 *
		 * @var   Charitable_Donation
		 */
		protected $donation;

		/**
		 * Donation log instance for this donation.
		 *
		 * @since 1.0.0
		 *
		 * @var   Charitable_Donation_Log
		 */
		protected $donation_log;

		/**
		 * The donor object.
		 *
		 * @since 1.0.0
		 *
		 * @var   Charitable_Donor
		 */
		protected $donor;

		/**
		 * The donation processor object.
		 *
		 * @since 1.0.0
		 *
		 * @var   Charitable_Donation_Processor
		 */
		protected $processor;

		/**
		 * The Windcave gateway model.
		 *
		 * @since 1.0.0
		 *
		 * @var   Charitable_Gateway_Windcave
		 */
		protected $gateway;

		/**
		 * Submitted donation values.
		 *
		 * @since 1.0.0
		 *
		 * @var   array
		 */
		protected $donation_data;

		/**
		 * Set up class instance.
		 *
		 * @since 1.0.0
		 *
		 * @param int                           $donation_id The donation ID.
		 * @param Charitable_Donation_Processor $processor   The donation processor object.
		 */
		public function __construct( $donation_id, Charitable_Donation_Processor $processor ) {
			$this->donation      = new Charitable_Donation( $donation_id );
			$this->donation_log  = $this->donation->log();
			$this->donor         = $this->donation->get_donor();
			$this->gateway       = charitable_windcave()->gateway();
			$this->processor     = $processor;
			$this->donation_data = $this->processor->get_donation_data();
		}

		/**
		 * Run the processor.
		 *
		 * @return boolean|array
		 */
		public function run() {
			/* Set up PxPay request properties. */
			$request = new \Charitable_Windcave\PxPayRequest();
			$request->setMerchantReference( $this->donation->ID );
			$request->setAmountInput( $this->donation->get_total_donation_amount( true ) );
			$request->setTxnData1( $this->donor->get_name() );
			$request->setTxnData2( $this->donor->get_donor_meta( 'address' ) );
			$request->setTxnData3( $this->donor->get_donor_meta( 'address_2' ) );
			$request->setTxnType( 'Purchase' );
			$request->setCurrencyInput( charitable_get_currency() );
			$request->setEmailAddress( $this->donor->get_donor_meta( 'email' ) );
			$request->setUrlFail( charitable_get_permalink( 'donation_cancel_page', [ 'donation_id' => $this->donation->ID ] ) );
			$request->setUrlSuccess( charitable_get_permalink( 'donation_receipt_page', [ 'donation_id' => $this->donation->ID ] ) );
			$request->setTxnId( substr( $this->donation->get_donation_key(), 0, 16 ) );

			/* Call makeRequest function to obtain input XML. */
			$request_string = $this->gateway->pxpay()->makeRequest( $request );

			if ( is_wp_error( $request_string ) ) {
				charitable_get_notices()->add_error( __( 'Error when attempting to create payment request.', 'charitable-windcave' ) );

				$this->donation_log->add(
					sprintf(
						/* translators: %1$s: error message; %2$s: error code */
						__( 'Error response when setting up payment request with Windcave API: %1$s [%2$s]', 'charitable-windcave' ),
						$request_string->get_error_message(),
						$request_string->get_error_code()
					)
				);

				return false;
			}

			/* Obtain output XML. */
			$response = new \Charitable_Windcave\MifMessage( $request_string );

			/* Parse output XML. */
			$redirect_url = $response->get_element_text( 'URI' );
			$valid        = $response->get_attribute( 'valid' );

			if ( ! $valid || empty( $redirect_url ) ) {
				charitable_get_notices()->add_error( __( 'Error when attempting to create payment request.', 'charitable-windcave' ) );

				$this->donation_log->add(
					sprintf(
						/* translators: %1$s: error message; %2$s: error code */
						__( 'Invalid payment request response received from Windcave API: %1$s', 'charitable-windcave' ),
						$response->get_element_text( 'ResponseText' )
					)
				);

				return false;
			}

			/* Log that the payment request was successful. */
			$this->donation_log->add( __( 'Payment request created. Redirecting user to Windcave payment page.', 'charitable-windcave' ) );

			return [
				'redirect' => $redirect_url,
				'safe'     => false,
			];
		}
	}

endif;
