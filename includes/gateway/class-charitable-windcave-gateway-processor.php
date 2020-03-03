<?php
/**
 * Responsible for processing payments through Windcave.
 *
 * @package   Charitable Windcave/Classes/Charitable_Windcave_Gateway_Processor
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

if ( ! class_exists( 'Charitable_Windcave_Gateway_Processor' ) ) :

	/**
	 * Charitable_Windcave_Gateway_Processor
	 *
	 * @since 1.0.0
	 */
	abstract class Charitable_Windcave_Gateway_Processor implements Charitable_Windcave_Gateway_Processor_Interface {

		/** The URL that PxPay requests should be made to. */
		const WINDCAVE_REQUEST_URL = 'https://sec.windcave.com/pxaccess/pxpay.aspx';

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
			$keys  = $this->gateway->get_keys();
			$pxpay = new Charitable_Windcave\PxPay_Curl(
				self::WINDCAVE_REQUEST_URL,
				$keys['user_id'],
				$keys['key']
			);

			echo '<pre>';
			var_dump( $pxpay );
			echo '</pre>';
			die;
		}
	}

endif;
