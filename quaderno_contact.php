<?php
/**
* Quaderno Contact
*
* @package   Quaderno PHP
* @author    Quaderno <support@quaderno.io>
* @copyright Copyright (c) 2021, Quaderno
* @license   https://opensource.org/licenses/MIT The MIT License
*/

class QuadernoContact extends QuadernoModel {
	static protected $model = 'contacts';

	/**
	 * @param string $id
	 * @param string $gateway
	 *
	 * @return false|QuadernoContact
	 */
	public static function retrieve($id, $gateway)
	{
		$response = QuadernoBase::retrieve($id, 'customers', $gateway);
		$return = false;

		if (QuadernoBase::responseIsValid($response))
			$return = new self($response['data']);

		return $return;
	}
}
?>
