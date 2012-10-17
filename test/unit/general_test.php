<?php
class GeneralTest extends UnitTestCase { 
  function __construct() {
  }  

  function testPingWorks() {
    $this->assertTrue(QuadernoBase::ping());
  }
}
?>