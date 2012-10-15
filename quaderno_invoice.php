<?php
class QuadernoInvoice extends QuadernoDocument {
  static protected $MODEL = 'invoices';

  public function addPayment($payment) {
    $this->execAddPayment($payment);
  }

  public function deliver() {
    $this->execDeliver();
  }
}
?>