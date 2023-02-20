<?php
/**
* Quaderno Tax ID
*
* @package   Quaderno PHP
* @author    Quaderno <support@quaderno.io>
* @copyright Copyright (c) 2021, Quaderno
* @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
*/

class QuadernoTaxId extends QuadernoModel
{
	static protected $model = 'tax_ids';

	/**
	 * @param array|null $params
	 *
	 * @return bool
	 */
	public static function validate($params) {
		$return = false;
		$response = QuadernoBase::apiCall('GET', 'tax_ids', 'validate', $params);

		if (QuadernoBase::responseIsValid($response))
			$return = $response['data']['valid'];

		return $return;
	}
}
?>
