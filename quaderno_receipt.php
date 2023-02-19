<?php
/**
* Quaderno Estimate
*
* @package   Quaderno PHP
* @author    Quaderno <support@quaderno.io>
* @copyright Copyright (c) 2021, Quaderno
* @license   https://opensource.org/licenses/MIT The MIT License
*/

class QuadernoReceipt extends QuadernoDocument
{
	static protected $model = 'receipts';

	/**
	 * @return bool
	 */
	public function deliver()
	{
		return $this->execDeliver();
	}
}
?>
