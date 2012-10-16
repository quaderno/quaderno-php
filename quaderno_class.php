<?php
/* Interface that implements every single class */
abstract class QuadernoClass {
  protected $data = array();  
  
  function __construct($newdata) {
    if (is_array($newdata)) $this->data = $newdata;
  }

  public function __set($name, $value) {
    $this->data[$name] = $value;
  }

  public function __get($name) {
    return array_key_exists($name, $this->data) ? $this->data[$name] : null;
  }
  
  protected function getArray() {
    return $this->data;
  }

}
?>