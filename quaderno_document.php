<?php
/* Interface that implements every Document
This is, invoices, expenses and estimates */
abstract class QuadernoDocument extends QuadernoModel {
  protected $paymentsArray = array();

  function __construct($newdata) {
    parent::__construct($newdata);
    if (isset($this->data['payments'])) {
      foreach ($this->data['payments'] as $p) {        
        array_push($this->paymentsArray, new QuadernoPayment($p));
      }
    }
  }

  public function addContact($contact) {
    $this->data["contact_id"] = $contact->id;
    $this->data["contact_name"] = $contact->full_name;
    return isset($this->data["contact_id"]) && isset($this->data["contact_name"]);
  }

  public function addItem($item) {
    $length = isset($this->data["items_attributes"]) ? count($this->data["items_attributes"]) : 0;
    $this->data["items_attributes"][$length] = $item->getArray();
    return count($this->data["items_attributes"]) == $length+1;
  }

  // Interface - only subclasses which implement original ones (i.e. without exec-)
  // can call these methods
  protected function execAddPayment($payment) {    
    $length = count($this->paymentsArray);
    return array_push($this->paymentsArray, $payment) == $length+1;
  }

  protected function execGetPayments() {
    return $this->paymentsArray;
  }

  protected function execRemovePayment($payment) {
    $i = array_search($payment, $this->paymentsArray, true);
    if ($i >= 0) $this->paymentsArray[$i]->markToDelete = true;
    return ($i >= 0);
  }

  //// Deliver call for QuadernoDocument objects
  // Deliver object to the contact email
  // Returns true or false whether the request is accepted or not
  protected function execDeliver() {
    $return = false;
    $response = QuadernoBase::deliver(static::$MODEL, $this->id);

    if (QuadernoBase::responseIsValid($response)) $return = true;
    elseif (isset($response['data']['errors'])) $this->errors = $response['data']['errors'];

    return $return;
  }

}
?>