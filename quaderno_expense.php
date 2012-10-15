<?php
class QuadernoExpense extends QuadernoDocument {
  static protected $MODEL = 'expenses';

  public function addPayment($payment) {
    $this->execAddPayment($payment);
  }

  public function getPayments() {
    $this->execGetPayments();
  }

  public function removePayment($payment) {
    $this->execRemovePayment($payment);
  }
}
?>