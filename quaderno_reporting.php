<?php
/**
* Quaderno Reporting
*
* @package   Quaderno PHP
* @author    Quaderno <support@quaderno.io>
* @copyright Copyright (c) 2021, Quaderno
* @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
*/

class QuadernoReporting extends QuadernoModel
{
	/**
	 * @param array|null $params
	 *
	 * @return bool
	 */
	public static function requests($params) {
		$return = false;
		$response = QuadernoBase::apiCall('POST', 'reporting', 'requests', $params);

		if (QuadernoBase::responseIsValid($response))
			$return = $response['data']['valid'];

		return $return;
	}
}
?>
