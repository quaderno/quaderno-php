<?php
/**
* Quaderno Tax Rate
*
* @package   Quaderno PHP
* @author    Quaderno <support@quaderno.io>
* @copyright Copyright (c) 2021, Quaderno
* @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
*/

class QuadernoTaxRate extends QuadernoModel
{
  static protected $model = 'tax_rates';

  public static function calculate($params, $errorCallback = null) {
    $return = false;
    $response = QuadernoBase::apiCall('GET', 'tax_rates', 'calculate', $params);

    if (QuadernoBase::responseIsValid($response)) {
      $return = new self($response['data']);
    }

    if (is_callable($errorCallback)) {
      $errorCallback($response);
    }

    return $return;
  }

  /**
   * @return string
   */
  public static function getError()
  {
    return self::$error;
  }
}
?>
