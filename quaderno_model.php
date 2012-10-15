<?php
abstract class QuadernoModel extends QuadernoClass {
  //// Find for QuadernoModel objects
  // If $id is passed, it returns a single object
  // If $id is not passed, it returns an array of objects
  // When request fails, it returns false
  static function find($id=null) {
    $return = false;
    $class = get_called_class();

    if (isset($id)) {
      $response = QuadernoBase::findByID(static::$MODEL, $id);      
      if (QuadernoBase::responseIsValid($response)) $return = new $class($response['data']);
    }
    else {
      $response = QuadernoBase::find(static::$MODEL);

      if (QuadernoBase::responseIsValid($response)) {
        $return = array();
        for ($i=0; $i<count($response['data']); $i++) $return[$i] = new $class($response['data'][$i]);          
      }
    }

    return $return;
  }

  //// Save for QuadernoModel objects
  // Export object data to the model
  // Returns true or false whether the request is accepted or not
  public function save() {
    $return = false;
    $response = QuadernoBase::save(static::$MODEL, $this->data, $this->id);

    if (QuadernoBase::responseIsValid($response)) {
      $return = true;
      $this->data = $response['data'];
    }

    return $return;
  }

  //// Delete for QuadernoModel objects
  // Delete object from the model
  // Returns true or false whether the request is accepted or not
  public function delete() {
    $return = false;
    $response = QuadernoBase::delete(static::$MODEL, $this->id);

    if (QuadernoBase::responseIsValid($response)) {
      $return = true;
      $this->data = array();
    }

    return $return;
  }

}
?>