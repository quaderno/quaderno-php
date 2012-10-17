<?php
class QuadernoExpense extends QuadernoDocument {
  static protected $MODEL = 'expenses';

  public function addPayment($payment) {
    return $this->execAddPayment($payment);
  }

  public function getPayments() {
    return $this->execGetPayments();
  }

  public function removePayment($payment) {
    return $this->execRemovePayment($payment);
  }
}
?>