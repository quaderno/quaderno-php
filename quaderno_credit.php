<?php
/**
* Quaderno Invoice
*
* @package   Quaderno PHP
* @author    Quaderno <support@quaderno.io>
* @copyright Copyright (c) 2021, Quaderno
* @license   https://opensource.org/licenses/MIT The MIT License
*/

class QuadernoCredit extends QuadernoDocument
{
	static protected $model = 'credits';

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
	 * @return false|QuadernoCredit
	 */
	public static function retrieve($id, $gateway)
	{
		$response = QuadernoBase::retrieve($id, 'refunds', $gateway);
		$return = false;

		if (QuadernoBase::responseIsValid($response))
			$return = new self($response['data']);

		return $return;
	}
}
?>
