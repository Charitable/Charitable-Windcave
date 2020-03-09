<?php
namespace Charitable_Windcave;

/**
 * Abstract base class for PxPost messages.
 *
 * These are messages with certain defined elements, which can be serialized to XML.
 *
 * @package   PxPost/Classes/PxPostMessage
 * @author    Windcave DevSupport, Eric Daams
 * @copyright Windcave 2017(c)
 * @since     1.0.0
 * @version   2.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class PxPostMessage {
	public $TxnType;
	public $CurrencyInput;
	public $TxnData1;
	public $TxnData2;
	public $TxnData3;
	public $MerchantReference;
	public $EmailAddress;
	public $BillingId;
	public $TxnId;

	public function __construct() {
	}

	public function setBillingId( $BillingId ) {
		$this->BillingId = $BillingId;
	}
	public function getBillingId() {
		return $this->BillingId;
	}
	public function setTxnType( $TxnType ) {
		$this->TxnType = $TxnType;
	}
	public function getTxnType() {
		return $this->TxnType;
	}
	public function setInputCurrency( $InputCurrency ) {
		$this->InputCurrency = $InputCurrency;
	}
	public function getInputCurrency() {
		return $this->InputCurrency;
	}
	public function setMerchantReference( $MerchantReference ) {
		$this->MerchantReference = $MerchantReference;
	}
	public function getMerchantReference() {
		return $this->MerchantReference;
	}
	public function setEmailAddress( $EmailAddress ) {
		$this->EmailAddress = $EmailAddress;
	}
	public function getEmailAddress() {
		return $this->EmailAddress;
	}
	public function setTxnData1( $TxnData1 ) {
		$this->TxnData1 = $TxnData1;
	}
	public function getTxnData1() {
		return $this->TxnData1;
	}
	public function setTxnData2( $TxnData2 ) {
		$this->TxnData2 = $TxnData2;
	}
	public function getTxnData2() {
		return $this->TxnData2;
	}
	public function getTxnData3() {
		return $this->TxnData3;
	}
	public function setTxnData3( $TxnData3 ) {
		$this->TxnData3 = $TxnData3;
	}
	public function setTxnId( $TxnId ) {
		$this->TxnId = $TxnId;
	}
	public function getTxnId() {
		return $this->TxnId;
	}
	public function setDpsTxnRef( $DpsTxnRef ) {
		$this->DpsTxnRef = $DpsTxnRef;
	}
	public function getDpsTxnRef() {
		return $this->DpsTxnRef;
	}

	public function toXml() {
		$xml = '<Txn>';

		print_r( get_object_vars( $this ) );
		// echo '<code>' . $xml . PHP_EOL;

		foreach ( get_object_vars( $this ) as $prop => $val ) {
			if ( empty( $val ) ) {
				continue;
			}

			$xml .= "<$prop>$val</$prop>";
			// echo $xml . PHP_EOL;
		}

		$xml .= '</Txn>';
		echo '<code>' . $xml . '</code>' . PHP_EOL;

		return $xml;
	}
}
