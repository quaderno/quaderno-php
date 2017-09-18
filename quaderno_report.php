<?php
/**
* Quaderno Invoice
*
* @package   Quaderno PHP
* @author    Quaderno <hello@quaderno.io>
* @copyright Copyright (c) 2015, Quaderno
* @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
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