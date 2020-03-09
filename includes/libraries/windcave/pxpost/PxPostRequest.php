<?php
namespace Charitable_Windcave;

/**
 * Class for PxPost request messages.
 *
 * @package   PxPost/Classes/PxPostRequest
 * @author    Windcave DevSupport, Eric Daams
 * @copyright Windcave 2017(c)
 * @since     1.0.0
 * @version   2.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PxPostRequest extends PxPostMessage {

	public $UrlFail;
	public $UrlSuccess;
	public $Amount;
	public $EnableAddBillCard;
	public $PxPostUserId;
	public $PxPostKey;
	public $Opt;

	/**
	 * Set up class instance.
	 */
	public function __construct() {
		parent::__construct();
	}

	public function setEnableAddBillCard( $EnableBillAddCard ) {
		$this->EnableAddBillCard = $EnableBillAddCard;
	}
	public function setUrlFail( $UrlFail ) {
		$this->UrlFail = $UrlFail;
	}
	public function setUrlSuccess( $UrlSuccess ) {
		$this->UrlSuccess = $UrlSuccess;
	}
	public function setAmount( $Amount ) {
		$this->Amount = sprintf( '%.2f', $Amount );
	}
	public function setUserId( $UserId ) {
		$this->PostUsername = $UserId;
	}
	public function setKey( $Key ) {
		$this->PostPassword = $Key;
	}

	/**
	 * Data validation.
	 */
	public function validData() {
		$msg = '';

		if ( ! in_array( $this->TxnType, [ 'Auth', 'Complete', 'Purchase', 'Refund', 'Validate' ] ) ) {
			$msg = "Invalid TxnType[$this->TxnType]<br>";
		}

		if ( strlen( $this->MerchantReference ) > 64 ) {
			$msg = "Invalid MerchantReference [$this->MerchantReference]<br>";
		}

		if ( strlen( $this->TxnId ) > 16 ) {
			$msg = "Invalid TxnId [$this->TxnId]<br>";
		}
		if ( strlen( $this->TxnData1 ) > 255 ) {
			$msg = "Invalid TxnData1 [$this->TxnData1]<br>";
		}
		if ( strlen( $this->TxnData2 ) > 255 ) {
			$msg = "Invalid TxnData2 [$this->TxnData2]<br>";
		}
		if ( strlen( $this->TxnData3 ) > 255 ) {
			$msg = "Invalid TxnData3 [$this->TxnData3]<br>";
		}

		if ( strlen( $this->EmailAddress ) > 255 ) {
			$msg = "Invalid EmailAddress [$this->EmailAddress]<br>";
		}

		if ( strlen( $this->UrlFail ) > 255 ) {
			$msg = "Invalid UrlFail [$this->UrlFail]<br>";
		}
		if ( strlen( $this->UrlSuccess ) > 255 ) {
			$msg = "Invalid UrlSuccess [$this->UrlSuccess]<br>";
		}
		if ( strlen( $this->BillingId ) > 32 ) {
			$msg = "Invalid BillingId [$this->BillingId]<br>";
		}

		if ( $msg != '' ) {
			trigger_error( $msg, E_USER_ERROR );
			return false;
		}

		return true;
	}
}