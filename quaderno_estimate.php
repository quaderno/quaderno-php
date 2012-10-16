<?php
class QuadernoEstimate extends QuadernoDocument {
  static protected $MODEL = 'estimates';

  public function deliver() {
    return $this->execDeliver();
  }
}
?>