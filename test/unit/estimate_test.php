<?php
class EstimateTest extends UnitTestCase { 
  private $contact = null;
  private $item = null;

  function __construct() {
    $this->contact = new QuadernoContact(array(
                         'first_name' => 'Chuck',
                         'last_name' => 'Norris'));

    $this->item = new QuadernoItem(array(
                                   'description': 'concepto 1',
                                   'price': 100.0,
                                   'quantity': 20
                                   ));
  }  

  // Search last estimates
  function testFindEstimateReturnsArray() {
    $this->assertTrue(is_array(QuadernoEstimate::find()));
  }

  // Search by ID
  function testFindEstimateWithInvalidIdReturnsFalse() {
    $this->assertFalse(QuadernoEstimate::find("0"));
  }

  function testCreatingAnEstimateWithNoItemsReturnFalse() {
    $estimate = new QuadernoEstimate(array(
                                 'subject' => 'Quaderno',
                                 'notes' => 'Yeah'));
    $estimate->addContact($this->contact);
    $this->assertFalse($estimate->save());
  }

  function testCreatingEstimateReturningItAndDeletingIt() {
    $estimate = new QuadernoEstimate(array(
                                 'subject' => 'Quaderno',
                                 'notes' => 'Yeah'));
    $estimate->addContact($this->contact);
    $this->assertFalse($estimate->save());
    $estimate->addItem($this->item);
    $estimate->addItem($this->item);
    $estimate->addItem($this->item);
    $this->assertTrue($estimate->save());
    $this->assertClone(QuadernoEstimate::find($estimate->id), $estimate);
    $this->assertTrue($invoice->deliver());
    $this->assertTrue($estimate->delete());
  }

  function testFindEstimateWithInvalidIdReturnsFalse() {
    $this->assertFalse(QuadernoEstimate::find("pericopalotes"));
  }
}
?>