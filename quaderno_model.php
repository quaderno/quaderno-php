<?php
class QuadernoModel {
  protected $data = array();
  protected $id = null;
  
  function __construct($newdata) {
    if (is_array($newdata)) $this->data = $newdata;
  }

  public function __set($name, $value) {
    $this->data[$name] = $value;
  }

  public function __get($name) {
    return array_key_exists($name, $this->data) ? $this->data[$name] : null;
  }

  /*public function save() {

  }*/
}
?>