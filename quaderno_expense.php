<?php
/**
* Quaderno Expense
*
* @package   Quaderno PHP
* @author    Quaderno <support@quaderno.io>
* @copyright Copyright (c) 2021, Quaderno
* @license   https://opensource.org/licenses/MIT The MIT License
*/

class QuadernoExpense extends QuadernoDocument
{
	static protected $model = 'expenses';

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
}
?>
