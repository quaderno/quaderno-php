<?php
class EndeveContact extends EndeveModel {
  const MODEL = 'contacts';

  // If input = ID => Return EndeveContact
  // Else => Return array of EndeveContact
  static function find($query=null) {
    $return = false;

    switch (gettype($query)) {
      // Input string => ID
      case 'string':
        $response = EndeveBase::findByID(self::MODEL, $query);
        if (EndeveBase::responseIsValid($response)) $return = new self($response['data']);
        break;

      // Input array
      default:
        $response = EndeveBase::find(self::MODEL, $query);

        if (EndeveBase::responseIsValid($response)) {
          $return = array();
          for ($i=0; $i<count($response['data']); $i++) $return[$i] = new self($response['data'][$i]);          
        }
    }

    return $return;
  }


}
?>