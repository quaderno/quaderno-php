<?php
class InvoiceTest extends UnitTestCase { 
  private $contacts = null;
  private $item = null;
  private $payment = null;

  function __construct() {
    $this->contacts = QuadernoContact::find();

    $this->item = new QuadernoItem(array(
                                   'description' => 'concepto 1',
                                   'price' => 100.0,
                                   'quantity' => 20
                                   ));

    $this->payment = new QuadernoPayment(array(                                         
                                         'date' => date('2012-10-10'),
                                         'payment_method' => 'credit_card'
                                         ));
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
    $invoice->addContact($this->contacts[0]);
    $invoice->addPayment($this->payment);
    $this->assertFalse($invoice->save()); // Impossible to create doc w/o items
  }

  function testCreatingInvoiceReturningItAndDeletingIt() {
    $invoice = new QuadernoInvoice(array(
                                 'subject' => 'Testing Quaderno API',
                                 'notes' => 'Test execution',
                                 'currency' => 'EUR'));        
    $invoice->addContact($this->contacts[0]);
    $this->assertFalse($invoice->save()); // Impossible to create doc w/o items
    $invoice->addItem($this->item);
    $invoice->addItem($this->item);
    $invoice->addItem($this->item);
    $this->assertTrue($invoice->save());
    $this->assertClone(QuadernoInvoice::find($invoice->id), $invoice);
    $invoice->notes = 'Changing notes!';
    $this->assertTrue($invoice->save());
    $this->assertEqual('Changing notes!', $invoice->notes);
    $this->assertTrue($invoice->deliver());
    $this->assertTrue($invoice->delete());
  }  

}
?>