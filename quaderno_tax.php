<?php
/**
* Quaderno Tax
*
* @package   Quaderno PHP
* @author    Quaderno <hello@quaderno.io>
* @copyright Copyright (c) 2015, Quaderno
* @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
*/

class QuadernoTax extends QuadernoModel
{
  public static function calculate($params)
  {
    $response = QuadernoBase::calculate($params);
    $return = false;

    if (QuadernoBase::responseIsValid($response))
      $return = new self($response['data']);

    return $return;
  }
  public static function validate_vat_number($country, $vat_number)
  {
    $response = QuadernoBase::apiCall('GET', 'taxes', 'validate', array( 'country' => $country, 'vat_number' => $vat_number));
    $return = false;

    if (QuadernoBase::responseIsValid($response))
      $return = $response['data']['valid'];

    return $return;
  }
}
?>