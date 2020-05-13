<?php
namespace Charitable_Windcave;

/**
 * Responsible for setting up requests to Windcave API.
 *
 * This is based on the PxPay_Curl class provided by Windcave
 * in their sample library, but uses WordPress' wp_remote_post
 * function instead of curl.
 *
 * @package   PxPay/Classes/PxPayWordPress
 * @author    Windcave DevSupport, Eric Daams
 * @copyright Windcave 2017(c)
 * @since     1.0.0
 * @version   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PxPayWordPress {

	public $PxPay_Key;
	public $PxPay_Url;
	public $PxPay_Userid;

	public function __construct( $Url, $UserId, $Key ) {
		$this->PxPay_Key    = $Key;
		$this->PxPay_Url    = $Url;
		$this->PxPay_Userid = $UserId;
	}

	/**
	 * Create a request for the PxPay interface.
	 */
	public function makeRequest( $request ) {
		/* Validate the request. */
		if ( $request->validData() == false ) {
			return '';
		}

		$request->setUserId( $this->PxPay_Userid );
		$request->setKey( $this->PxPay_Key );

		$xml = $request->toXml();

		$result = $this->submitXml( $xml );

		return $result;
	}

	/**
	 * Return the transaction outcome details.
	 */
	public function getResponse( $result ) {
		$inputXml = '<ProcessResponse><PxPayUserId>' . $this->PxPay_Userid . '</PxPayUserId><PxPayKey>' . $this->PxPay_Key .
		'</PxPayKey><Response>' . $result . '</Response></ProcessResponse>';

		$outputXml = $this->submitXml( $inputXml );

		$pxresp = new PxPayResponse( $outputXml );
		return $pxresp;
	}

	/**
	 * Actual submission of XML using wp_remote_post. Returns output XML.
	 */
	public function submitXml( $inputXml ) {
		$response = wp_remote_post(
			$this->PxPay_Url,
			[
				'timeout'     => 20,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking'    => true,
				'body'        => $inputXml,
			]
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		return wp_remote_retrieve_body( $response );
	}
}
