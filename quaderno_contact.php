<?php
class QuadernoContact extends QuadernoModel {
  const MODEL = 'contacts';

  // If input = ID => Return QuadernoContact
  // Else => Return array of QuadernoContact
  static function find($query=null) {
    $return = false;

    switch (gettype($query)) {
      // Input string => ID
      case 'string':
        $response = QuadernoBase::findByID(self::MODEL, $query);
        if (QuadernoBase::responseIsValid($response)) $return = new self($response['data']);
        break;

      // Input array
      default:
        $response = QuadernoBase::find(self::MODEL, $query);

        if (QuadernoBase::responseIsValid($response)) {
          $return = array();
          for ($i=0; $i<count($response['data']); $i++) $return[$i] = new self($response['data'][$i]);          
        }
    }

    return $return;
  }

  public function save() {
    $return = false;
    $response = QuadernoBase::save(self::MODEL, $this->data, $this->id);

    if (QuadernoBase::responseIsValid($response)) {
      $return = true;      
      $this->data = $response['data'];      
    }

    return $return;
  }

  public function delete() {
    $return = false;
    $response = QuadernoBase::delete(self::MODEL, $this->id);

    if (QuadernoBase::responseIsValid($response)) {
      $return = true;
      $this->data = array();
    }

    return $return;
  }


}
?>