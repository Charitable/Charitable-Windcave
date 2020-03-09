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

		/** The URL that PxPay & PxPost requests should be made to. */
		const WINDCAVE_PXPAY_URL  = 'https://sec.windcave.com/pxaccess/pxpay.aspx';
		const WINDCAVE_PXPOST_URL = 'https://sec.paymentexpress.com/pxpost.aspx';

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
		 * PxPay class for making requests.
		 *
		 * @since 1.0.0
		 *
		 * @var   \Charitable_Windcave\PxPayWordPress
		 */
		protected $pxpay;

		/**
		 * PxPost class for making requests.
		 *
		 * @since 1.0.0
		 *
		 * @var   \Charitable_Windcave\PxPostWordPress
		 */
		protected $pxpost;

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
				'refunds',
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

			/**
			 * Refund a donation from the dashboard.
			 */
			add_action( 'charitable_process_refund_windcave', [ $this, 'refund_donation_from_dashboard' ] );

			/**
			 * Process the response.
			 */
			add_action( 'init', [ $this, 'process_response' ] );
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
			return array_merge(
				$settings,
				[
					'pxpay'           => [
						'title'    => __( 'PxPay Settings', 'charitable-windcave' ),
						'type'     => 'heading',
						'priority' => 4,
					],
					'pxpay_userid'    => [
						'type'     => 'text',
						'title'    => __( 'PxPay User ID', 'charitable-windcave' ),
						'priority' => 6,
						'class'    => 'wide',
					],
					'pxpay_key'       => [
						'type'     => 'text',
						'title'    => __( 'PxPay Key', 'charitable-windcave' ),
						'priority' => 8,
						'class'    => 'wide',
					],
					'pxppost'         => [
						'title'    => __( 'PxPost Settings', 'charitable-windcave' ),
						'type'     => 'heading',
						'priority' => 10,
					],
					'pxpost_intro'    => [
						'type'     => 'content',
						'content'  => __( 'PxPost credentials are required to be able to refund donations from within Charitable.', 'charitable-windcave' ),
						'priority' => 11,
					],
					'pxpost_userid'   => [
						'type'     => 'text',
						'title'    => __( 'PxPost User ID', 'charitable-windcave' ),
						'priority' => 12,
						'class'    => 'wide',
					],
					'pxpost_password' => [
						'type'     => 'text',
						'title'    => __( 'PxPost Password', 'charitable-windcave' ),
						'priority' => 14,
						'class'    => 'wide',
					],
				]
			);
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
				'pxpay_key'       => trim( $this->get_value( 'pxpay_key' ) ),
				'pxpay_userid'    => trim( $this->get_value( 'pxpay_userid' ) ),
				'pxpost_userid'   => trim( $this->get_value( 'pxpost_userid' ) ),
				'pxpost_password' => trim( $this->get_value( 'pxpost_password' ) ),
			];
		}

		/**
		 * Set up a PxPay object.
		 *
		 * @since  1.0.0
		 *
		 * @return \Charitable_Windcave\PxPayWordPress|false
		 */
		public function pxpay() {
			if ( ! isset( $this->pxpay ) ) {
				$keys = $this->get_keys();

				if ( empty( $keys['pxpay_userid'] ) || empty( $keys['pxpay_key'] ) ) {
					return false;
				}

				$this->pxpay = new \Charitable_Windcave\PxPayWordPress(
					self::WINDCAVE_PXPAY_URL,
					$keys['pxpay_userid'],
					$keys['pxpay_key']
				);

			}

			return $this->pxpay;
		}

		/**
		 * Set up a PxPost object.
		 *
		 * @since  1.0.0
		 *
		 * @return \Charitable_Windcave\PxPostWordPress|false
		 */
		public function pxpost() {
			if ( ! isset( $this->pxpost ) ) {
				$keys = $this->get_keys();

				if ( empty( $keys['pxpost_userid'] ) || empty( $keys['pxpost_password'] ) ) {
					return false;
				}

				$this->pxpost = new \Charitable_Windcave\PxPostWordPress(
					self::WINDCAVE_PXPOST_URL,
					$keys['pxpost_userid'],
					$keys['pxpost_password']
				);

			}

			return $this->pxpost;
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
		}

		/**
		 * Process the response from Windcave.
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function process_response() {
			if ( ! isset( $_GET['result'] ) || ! isset( $_GET['userid'] ) ) {
				return;
			}

			/* Do a sanity check to make sure the user id matches. */
			if ( $_GET['userid'] != trim( $this->get_value( 'userid' ) ) ) {
				return;
			}

			$response    = $this->pxpay()->getResponse( $_GET['result'] );
			$donation_id = $response->getMerchantReference();

			if ( ! $donation_id ) {
				return;
			}

			/* We've processed this donation already. */
			if ( get_post_meta( $donation_id, '_charitable_processed_windcave_response', true ) ) {
				return;
			}

			$donation = new Charitable_Donation( $donation_id );

			/* Make sure the txnID matches the donation key. */
			if ( $response->getTxnId() != substr( $donation->get_donation_key(), 0, 16 ) ) {
				return;
			}

			/* Record the gateway transaction ID. */
			$donation->set_gateway_transaction_id( $response->getDpsTxnRef() );

			if ( '1' == $response->getSuccess() ) {
				$donation->update_donation_log( __( 'Payment completed.', 'charitable-windcave' ) );
				$donation->update_status( 'charitable-completed' );
			} else {
				$donation->update_donation_log( $response->getResponseText() );
				$donation->update_status( 'charitable-failed' );
			}

			/* Avoid processing this response again. */
			add_post_meta( $donation_id, '_charitable_processed_windcave_response', true );
		}

		/**
		 * Check whether a particular donation can be refunded automatically in Windcave.
		 *
		 * @since  1.0.0
		 *
		 * @param  Charitable_Donation $donation The donation object.
		 * @return boolean
		 */
		public function is_donation_refundable( Charitable_Donation $donation ) {
			return false !== ( $this->pxpost() && $donation->get_gateway_transaction_id() );
		}

		/**
		 * Process a refund initiated in the WordPress dashboard.
		 *
		 * @since  1.0.0
		 *
		 * @param  int $donation_id The donation ID.
		 * @return boolean
		 */
		public static function refund_donation_from_dashboard( $donation_id ) {
			$donation = charitable_get_donation( $donation_id );

			if ( ! $donation ) {
				return false;
			}

			$pxpost = $this->pxpost();

			if ( ! $pxpost ) {
				return false;
			}

			$transaction = $donation->get_gateway_transaction_id();

			if ( ! $transaction ) {
				return false;
			}

			$request                = new \Charitable_Windcave\PxPostRequest();
			$request->TxnType       = 'Refund';
			$request->Amount        = $donation->get_total_donation_amount( true );
			$request->InputCurrency = charitable_get_currency();
			$request->DpsTxnRef     = $transaction;

			$response = new \Charitable_Windcave\PxPostResponse( $pxpost->makeRequest( $request ) );

			if ( is_wp_error( $response ) ) {
				$donation->log()->add(
					sprintf(
						/* translators: %1$s: error message; %2$s: error code */
						__( 'Windcave refund failed: %1$s [%2$s]', 'charitable-windcave' ),
						$request_string->get_error_message(),
						$request_string->get_error_code()
					)
				);

				return false;
			} elseif ( ! $response->Success ) {
				$donation->log()->add(
					sprintf(
						/* translators: %s: error message. */
						__( 'Windcave refund failed: %1$s', 'charitable-windcave' ),
						$response->ResponseText
					)
				);

				return false;
			}

			update_post_meta( $donation_id, '_windcave_refunded', true );
			update_post_meta( $donation_id, '_windcave_refund_id', $response->DpsTxnRef );

			$donation->log()->add(
				sprintf(
					/* translators: %s: transaction reference. */
					__( 'Windcave refund transaction ID: <code>%s</code>', 'charitable-windcave' ),
					$response->DpsTxnRef
				)
			);

			return true;
		}
	}

endif;
