<?php
require_once(dirname(__FILE__) . '/../../quaderno_load.php');

class GeneralTest extends UnitTestCase { 
  function __construct() {
  }  

  function testPingWorks() {
    $this->assertTrue(QuadernoBase::ping());
  }
}
?>