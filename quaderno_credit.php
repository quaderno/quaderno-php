<?php
/**
* Quaderno Invoice
*
* @package   Quaderno PHP
* @author    Quaderno <hello@quaderno.io>
* @copyright Copyright (c) 2015, Quaderno
* @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
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
}
?>