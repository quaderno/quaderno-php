<?php
class EstimateTest extends UnitTestCase { 
  private $contact = null;
  private $item = null;

  function __construct() {
    // General items and contacts to use
    $this->contact = new QuadernoContact(array(
                                 'first_name' => 'Joseph',
                                 'last_name' => 'Tribbiani',
                                 'contact_name' => 'Friends Staff'));
    $this->contact->save();

    $this->item = new QuadernoItem(array(
                                   'description' => 'concepto 1',
                                   'price' => 100.0,
                                   'quantity' => 20
                                   ));
  }

  function __destruct() {
    $this->contact->delete();
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
    $estimate->addContact($this->contact);
    $this->assertFalse($estimate->save()); // Impossible to create doc w/o items
  }

  function testCreatingEstimateReturningItAndDeletingIt() {
    $estimate = new QuadernoEstimate(array(
                                 'subject' => 'Quaderno',
                                 'notes' => 'Yeah',
                                 'currency' => 'EUR'));
    $estimate->addContact($this->contact);
    $this->assertFalse($estimate->save()); // Impossible to create doc w/o items
    $estimate->addItem($this->item);
    $estimate->addItem($this->item);
    $estimate->addItem($this->item);
    $this->assertTrue($estimate->save());
    $this->assertClone(QuadernoEstimate::find($estimate->id), $estimate);

    // Impossible to deliver when the contact doesn't have an e-mail address
    $this->assertFalse($estimate->deliver());
    $this->contact->email = "joseph.tribbiani@friends.com";
    $this->assertTrue($this->contact->save());
    $this->assertTrue($estimate->deliver());
    $this->assertTrue($estimate->delete());
  }
  
}
?>