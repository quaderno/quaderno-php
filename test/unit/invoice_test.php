<?php
class InvoiceTest extends UnitTestCase { 
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

  // Search last invoices
  function testFindInvoiceReturnsArray() {
    $this->assertTrue(is_array(QuadernoInvoice::find()));
  }

  // Search by ID
  function testFindInvoiceWithInvalidIdReturnsFalse() {
    $this->assertFalse(QuadernoInvoice::find("Invalid Id"));
  }

  function testCreatingAnInvoiceWithNoItemsReturnFalse() {
    $invoice = new QuadernoInvoice(array(
                                 'subject' => 'Failing test Quaderno',
                                 'notes' => 'This should fail'));
    $invoice->addContact($this->contact);    
    $this->assertFalse($invoice->save()); // Impossible to create doc w/o items
  }

  function testCreatingInvoiceReturningItAndDeletingIt() {
    $invoice = new QuadernoInvoice(array(
                                 'subject' => 'Testing Quaderno API',
                                 'notes' => 'Test execution',
                                 'currency' => 'EUR'));        
    $invoice->addContact($this->contact);
    $this->assertFalse($invoice->save()); // Impossible to create doc w/o items
    $invoice->addItem($this->item);
    $invoice->addItem($this->item);
    $invoice->addItem($this->item);
    $this->assertTrue($invoice->save());
    $this->assertClone(QuadernoInvoice::find($invoice->id), $invoice);
    $invoice->notes = 'Changing notes!';
    $this->assertTrue($invoice->save());
    $this->assertEqual('Changing notes!', $invoice->notes);

    // Impossible to deliver when the contact doesn't have an e-mail address
    $this->assertFalse($invoice->deliver());
    $this->contact->email = "joseph.tribbiani@friends.com";
    $this->assertTrue($this->contact->save());
    $this->assertTrue($invoice->deliver());
    $this->assertTrue($invoice->delete());
  }  

}
?>