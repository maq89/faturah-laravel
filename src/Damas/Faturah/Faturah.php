<?php

namespace Damas\Faturah;

use Damas\Faturah\Order;
use Damas\Faturah\FaturahUtility;
use Illuminate\Support\Str;


class Faturah{

	private $merchantCode;
	private $secureKey;
	private $token;
	private $message;
	private $hash;
	public $order;


	public static function getInstance($merchantCode, $secureKey)
	{
		static $inst = null;
		if ($inst === null) {
			$inst = new Faturah();
		}
		$inst->setMerchant($merchantCode, $secureKey);
		return $inst;
	}

	private function setMerchant($merchantCode, $secureKey)
	{
		$this->merchantCode = $merchantCode;
		$this->secureKey = $secureKey;
		$this->order = new Order();
	}

	private function generateToken()
	{
		$this->token = FaturahUtility::generateToken($this->merchantCode);
	}

	private function isSecureMerchant()
	{
		$isSecure = FaturahUtility::isSecureMerchant($this->merchantCode);
		if($isSecure)
		{
			$this->message = $this->secureKey.$this->merchantCode.$this->token.$this->order->totalPrice;
			$this->hash = FaturahUtility::generateSecureHash($this->message);
		}
	}

	private function formSubmit()
	{
		$productIDs 			= implode('|', $this->order->productIDs);
		$productNames 			= implode('|', $this->order->productNames);
		$productDescriptions	= implode('|', $this->order->productDescriptions);
		$productQuantities		= implode('|', $this->order->productQuantities);
		$productPrices			= implode('|', $this->order->productPrices);

		echo 'Redirecting...';
		echo '<form id="paymentForm" action="https://gateway.faturah.com/TransactionRequestHandler_Post.aspx" method="post">';
		echo '<input name="mc" type="hidden" value="'. $this->merchantCode .'"/>';
		echo '<input name="mt" type="hidden" value="'. $this->token .'"/>';
		echo '<input name="dt" type="hidden" value="'. $this->order->date .'"/>';
		echo '<input name="a" type="hidden" value="'. $this->order->totalPrice .'"/>';
		echo '<input name="ProductID" type="hidden" value="'. $productIDs .'"/>';
		echo '<input name="ProductName" type="hidden" value="'. $productNames .'"/>';
		echo '<input name="ProductDescription" type="hidden" value="'. $productDescriptions .'"/>';
		echo '<input name="ProductQuantity" type="hidden" value="'. $productQuantities .'"/>';
		echo '<input name="ProductPrice" type="hidden" value="'. $productPrices .'"/>';
		echo '<input name="DeliveryCharge" type="hidden" value="'. $this->order->deliveryCharge .'"/>';
		echo '<input name="CustomerName" type="hidden" value="'. $this->order->customerName .'"/>';
		echo '<input name="EMail" type="hidden" value="'. $this->order->customerEmail .'"/>';
		echo '<input name="PhoneNumber" type="hidden" value="'. $this->order->customerPhoneNumber .'"/>';
		echo '<input name="vpc_SecureHash" type="hidden" value="'. $this->hash .'"/>';
		echo '<input name="buyButton" type="submit" value="Buy" style="display: none"/>';
		echo '</form>';
		echo '<script type="text/javascript">
		  function processPayment() {
			  document.getElementById("paymentForm").submit();
		  }
		 window.onload = processPayment();
	   </script>';
	}

	public function send()
	{
		$this->generateToken();
		$this->isSecureMerchant();
		$this->formSubmit();
	}

}