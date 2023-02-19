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

	/**
	 * @return bool
	 */
	public function deliver()
	{
		return $this->execDeliver();
	}

	/**
	 * @param QuadernoPayment $payment
	 *
	 * @return bool
	 */
	public function addPayment($payment)
	{
		return $this->execAddPayment($payment);
	}

	/**
	 * @return QuadernoPayment[]
	 */
	public function getPayments()
	{
		return $this->execGetPayments();
	}

	/**
	 * @param QuadernoPayment $payment
	 *
	 * @return bool
	 */
	public function removePayment($payment)
	{
		return $this->execRemovePayment($payment);
	}

	/**
	 * @param string $id
	 * @param string $gateway
	 *
	 * @return false|QuadernoInvoice
	 */
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
