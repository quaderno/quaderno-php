<?php
class QuadernoExpense extends QuadernoDocument {
  static protected $MODEL = 'expenses';

  public function addPayment($payment) {
    $this->execAddPayment($payment);
  }
}
?>