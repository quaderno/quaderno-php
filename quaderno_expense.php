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

  // THESE TWO FUNCTIONS ARE HERE ONLY TO SOLVE TEST PROBLEMS
  // CAUSE OF A BUG IN THE API. REMOVE AFTER SOLVED.
  public function correctAmount() {
    foreach ($this->paymentsArray as $p) {
      $p->amount = "$0.00";
    }
  }
  public function correctUrl() {
    foreach ($this->paymentsArray as $p) {            
      $str = 'expenses';
      $p->url = substr_replace($p->url, $str, 40, strlen('invoices'));      
    }
  }
}
?>