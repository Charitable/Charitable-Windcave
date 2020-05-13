<?php
namespace Charitable_Windcave;

/**
 * Responsible for setting up requests to Windcave API.
 *
 * This is based on the PxPost_Curl class provided by Windcave
 * in their sample library, but uses WordPress' wp_remote_post
 * function instead of curl.
 *
 * @package   PxPost/Classes/PxPostWordPress
 * @author    Windcave DevSupport, Eric Daams
 * @copyright Windcave 2017(c)
 * @since     1.0.0
 * @version   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PxPostWordPress {

	public $PxPost_Key;
	public $PxPost_Url;
	public $PxPost_Userid;

	public function __construct( $Url, $UserId, $Key ) {
		$this->PxPost_Key    = $Key;
		$this->PxPost_Url    = $Url;
		$this->PxPost_Userid = $UserId;
	}

	/**
	 * Create a request for the PxPost interface.
	 */
	public function makeRequest( $request ) {
		/* Validate the request. */
		if ( $request->validData() == false ) {
			return '';
		}

		$request->setUserId( $this->PxPost_Userid );
		$request->setKey( $this->PxPost_Key );

		$xml = $request->toXml();

		$result = $this->submitXml( $xml );

		return $result;
	}

	/**
	 * Return the transaction outcome details.
	 */
	public function getResponse( $result ) {
		$inputXml = '<ProcessResponse><PxPostUserId>' . $this->PxPost_Userid . '</PxPostUserId><PxPostKey>' . $this->PxPost_Key .
		'</PxPostKey><Response>' . $result . '</Response></ProcessResponse>';

		$outputXml = $this->submitXml( $inputXml );

		$pxresp = new PxPostResponse( $outputXml );
		return $pxresp;
	}

	/**
	 * Actual submission of XML using wp_remote_post. Returns output XML.
	 */
	public function submitXml( $inputXml ) {
		$response = wp_remote_post(
			$this->PxPost_Url,
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
