<?php
class QuadernoInvoice extends QuadernoDocument {
  static protected $MODEL = 'invoices';

  public function deliver() {
    $this->execDeliver();
  }

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