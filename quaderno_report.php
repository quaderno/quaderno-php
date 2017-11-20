<?php
/**
* Quaderno Invoice
*
* @package   Quaderno PHP
* @author    Quaderno <hello@quaderno.io>
* @copyright Copyright (c) 2017, Quaderno
* @license   https://opensource.org/licenses/MIT The MIT License
*/

class QuadernoReport extends QuadernoModel
{
  public static function journal($params)
  {
    $response = QuadernoBase::apiCall('GET', 'reports', 'journal', $params);
    $return = false;

    if (QuadernoBase::responseIsValid($response))
      $return = $response['data'];

    return $return;
  }

  public static function taxes($country, $vat_number)
  {
    $response = QuadernoBase::apiCall('GET', 'reports', 'taxes', $params);
    $return = false;

    if (QuadernoBase::responseIsValid($response))
      $return = $response['data'];

    return $return;
  }
}
?>