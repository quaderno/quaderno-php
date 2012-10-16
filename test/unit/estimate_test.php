<?php
// REINICIAR BD !
class EstimateTest extends UnitTestCase { 
  private $contact = null;
  private $item = null;

  function __construct() {
    $this->contacts = QuadernoContact::find();

    $this->item = new QuadernoItem(array(
                                   'description' => 'concepto 1',
                                   'price' => 100.0,
                                   'quantity' => 20
                                   ));
  }  

  // Search last estimates
  function testFindEstimateReturnsArray() {
    $this->assertTrue(is_array(QuadernoEstimate::find()));
  }

  // Search by ID
  function testFindEstimateWithInvalidIdReturnsFalse() {
    $this->assertFalse(QuadernoEstimate::find("pericopalotes"));
  }

  function testCreatingAnEstimateWithNoItemsReturnFalse() {
    $estimate = new QuadernoEstimate(array(
                                 'subject' => 'Quaderno',
                                 'notes' => 'Yeah'));
    $estimate->addContact($this->contacts[0]);
    $this->assertFalse($estimate->save()); // Impossible to create doc w/o items
  }

  function testCreatingEstimateReturningItAndDeletingIt() {
    $estimate = new QuadernoEstimate(array(
                                 'subject' => 'Quaderno',
                                 'notes' => 'Yeah',
                                 'currency' => 'EUR'));
    $estimate->addContact($this->contacts[0]);
    $this->assertFalse($estimate->save()); // Impossible to create doc w/o items
    $estimate->addItem($this->item);
    $estimate->addItem($this->item);
    $estimate->addItem($this->item);
    $this->assertTrue($estimate->save());
    $this->assertClone(QuadernoEstimate::find($estimate->id), $estimate);
    //$this->assertFalse($estimate->deliver());         //////////// ACTIVAR!
    $this->contacts[0]->email = "jorge@recrea.es";
    $this->assertTrue($this->contacts[0]->save());
    $this->assertTrue($estimate->deliver());
    $this->assertTrue($estimate->delete());
  }

  
}
?>