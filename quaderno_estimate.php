<?php
class QuadernoEstimate extends QuadernoDocument {
  static protected $MODEL = 'estimates';

  public function deliver() {
    $this->execDeliver();
  }
}
?>