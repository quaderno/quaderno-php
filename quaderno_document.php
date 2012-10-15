<?php
abstract class QuadernoDocument extends QuadernoModel {
  protected $paymentsArray = array();

  public function addContact($contact) {
    $this->data["contact_id"] = $contact->id;
    $this->data["contact_name"] = $contact->contact_name;
    return isset($this->data["contact_id"]) && isset($this->data["contact_name"]);
  }

  public function addItem($item) {
    $length = isset($this->data["items"]) ? count($this->data["items"]) : 0;
    $this->data["items"][$length] = $item->getArray();
    return count($this->data["items"]) == $length+1;
  }

  protected function execAddPayment($payment) {    
    array_push($this->paymentsArray, $payment);
  }

  protected function execGetPayments() {
    return $this->paymentsArray;
  }

  protected function execRemovePayment($payment) {
    $p = array_search($payment, $paymentsArray, true);
    $p->markToDelete = true;
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