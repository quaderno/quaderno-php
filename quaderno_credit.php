<?php
/**
* Quaderno Invoice
*
* @package   Quaderno PHP
* @author    Quaderno <hello@quaderno.io>
* @copyright Copyright (c) 2017, Quaderno
* @license   https://opensource.org/licenses/MIT The MIT License
*/

class QuadernoCredit extends QuadernoDocument
{
  static protected $model = 'credits';

  public function deliver()
  {
    return $this->execDeliver();
  }

  public function addPayment($payment)
  {
    return $this->execAddPayment($payment);
  }

  public function getPayments()
  {
    return $this->execGetPayments();
  }

  public function removePayment($payment)
  {
    return $this->execRemovePayment($payment);
  }

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