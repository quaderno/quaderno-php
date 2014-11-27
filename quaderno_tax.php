<?php
class QuadernoTax extends QuadernoModel {

  static function calculate($params) {
    $response = QuadernoBase::calculate($params);
    $return = false;
    
    if (QuadernoBase::responseIsValid($response)) {
      $return = new self($response['data']);
    }

    return $return;
  }
}
?>