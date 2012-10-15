<?php
abstract class QuadernoDocument extends QuadernoModel {

  public function addContact($contact) {
    $this->data["contacts"] = $contact->getArray();
    return isset($this->data["contacts"]);
  }

  public function addItem($item) {
    $length = isset($this->data["items"]) ? count($this->data["items"]) : 0;
    $this->data["items"][$length] = $item->getArray();
    return count($this->data["items"]) == $length+1;
  }

  protected function execAddPayment($payment) {
    $length = isset($this->data["payments"]) ? count($this->data["payments"]) : 0;
    $this->data["payments"][$length] = $payment->getArray();
    return count($this->data["payments"] == $length+1);
  }

  //// Deliver call for QuadernoDocument objects
  // Deliver object to the contact email
  // Returns true or false whether the request is accepted or not
  protected function execDeliver() {
    $return = false;
    $response = QuadernoBase::deliver(static::$MODEL, $this->id);

    if (QuadernoBase::responseIsValid($response)) {
      $return = true;
      if (isset($response['data'])) $this->data = $response['data'];
    }

    return $return;
  }
}
?>