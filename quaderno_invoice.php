<?php
/**
* Quaderno Invoice
*
* @package   Quaderno PHP
* @author    Quaderno <support@quaderno.io>
* @copyright Copyright (c) 2021, Quaderno
* @license   https://opensource.org/licenses/MIT The MIT License
*/

class QuadernoInvoice extends QuadernoDocument
{
	static protected $model = 'invoices';

	public function deliver()
	{
		return $this->execDeliver();
	}

	public function addPayment($payment)
	{
		return $this->execAddPayment($payment);
	}

	public function getPayments()
	{
		return $this->execGetPayments();
	}

	public function removePayment($payment)
	{
		return $this->execRemovePayment($payment);
	}

  public static function retrieve($id, $gateway)
	{
		$response = QuadernoBase::retrieve($id, 'charges', $gateway);
		$return = false;

		if (QuadernoBase::responseIsValid($response))
			$return = new self($response['data']);

		return $return;
	}
}
?>