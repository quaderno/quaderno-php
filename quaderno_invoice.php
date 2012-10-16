<?php
class QuadernoInvoice extends QuadernoDocument {
  static protected $MODEL = 'invoices';

  public function deliver() {
    return $this->execDeliver();
  }

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