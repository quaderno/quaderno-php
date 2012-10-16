<?php
/* Interface for every model */
abstract class QuadernoModel extends QuadernoClass {
  //// Find for QuadernoModel objects
  // If $id is passed, it returns a single object
  // If $id is not passed, it returns an array of objects
  // When request fails, it returns false
  static function find($id=null) {
    $return = false;
    $class = get_called_class();

    if (isset($id)) {
      // Searching for an ID
      $response = QuadernoBase::findByID(static::$MODEL, $id);      
      if (QuadernoBase::responseIsValid($response)) $return = new $class($response['data']);
    }
    else {
      // Searching array of objects
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
    $response = null;
    $newobject = false;    
    $return = false;

    ////////// 1st step - New object to be created

    // Check if the current object has not been created yet
    if (is_null($this->id)) {
      // Not yet created, let's do it
      $response = QuadernoBase::save(static::$MODEL, $this->data, $this->id);
      $newobject = true;


      // Update data with the response
      if (QuadernoBase::responseIsValid($response)) {        
        $this->data = $response['data'];
        $return = true;
      }
    }

    $response = null;
    $newdata = false;

    /////////// 2nd step - Payments to be created

    // Check if there are any payments stored and not yet created
    if (isset($this->paymentsArray) && count($this->paymentsArray)) {

      foreach ($this->paymentsArray as $index => $p) {        
        if (is_null($p->id)) {
          // The payment does not have ID -> Not yet created
          $response = QuadernoBase::saveNested(static::$MODEL, $this->id, 'payments', $p->data);
          if (QuadernoBase::responseIsValid($response)) {
            $p->data = $response['data'];
            $newdata = self::find($this->id);
          }
        }        
        if ($p->markToDelete) {
          // The payment is marked to delete -> Let's do it.
          $deleteResponse = QuadernoBase::deleteNested(static::$MODEL, $this->id, 'payments', $p->id); 
          if (QuadernoBase::responseIsValid($deleteResponse)) {            
            array_splice($this->paymentsArray, $index, 1);
          }
        }
      }

      // If this object has received new data, let's update data field.
      if ($newdata) $this->data = $newdata->data;
    }

    ////////// 3rd step - Update object

    // Update object - This is only necessary when it's not a new object,
    // or new payments have been created.
    if (!$newobject || $newdata) {
      $response = QuadernoBase::save(static::$MODEL, $this->data, $this->id);

      if (QuadernoBase::responseIsValid($response)) {
        $return = true;
        $this->data = $response['data'];      
      }
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