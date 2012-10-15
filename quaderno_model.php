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
    $response = null;

    // Check if there are payments stored not yet created
    if (isset(static::$paymentsArray)) {
      foreach ($paymentsArray as $index => $p) {    
        if (!isset($p->id)) {
          $response = QuadernoBase::saveNested(static::$MODEL, $this->id, 'payments', $p); 
          $p->id = $response['data']['id'];
        }
        if ($p->markToDelete) {
          $deleteResponse = QuadernoBase::deleteNested(static::$MODEL, $this->id, 'payments', $p->id); 
          if (QuadernoBase::responseIsValid($deleteResponse)) unset($paymentsArray[$index]);
        }
      }

      if (QuadernoBase::responseIsValid($response)) $this->data = $response['data'];
    } 

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