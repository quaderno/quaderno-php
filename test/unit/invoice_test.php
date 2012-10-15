<?php
class InvoiceTest extends UnitTestCase { 
  private $contact = null;
  private $item = null;
  private $payment = null;

  function __construct() {
    $this->contact = new QuadernoContact(array(
                         'first_name' => 'Chuck',
                         'last_name' => 'Norris'));

    $this->item = new QuadernoItem(array(
                                   'description' => 'concepto 1',
                                   'price' => 100.0,
                                   'quantity' => 20
                                   ));

    $this->payment = new QuadernoPayment(array(
                                         'amount' => 300,
                                         'date' => date('2012-10-10'),
                                         'payment_method' => 'Transfer'
                                         ));
  }  

  // Search last invoices
  function testFindInvoiceReturnsArray() {
    $this->assertTrue(is_array(QuadernoInvoice::find()));
  }

  // Search by ID
  function testFindInvoiceWithInvalidIdReturnsFalse() {
    $this->assertFalse(QuadernoInvoice::find("0"));
  }

  function testCreatingAnInvoiceWithNoItemsReturnFalse() {
    $invoice = new QuadernoInvoice(array(
                                 'subject' => 'Quaderno',
                                 'notes' => 'Yeah'));
    $invoice->addContact($this->contact);
    $invoice->addPayment($this->payment);
    $this->assertFalse($invoice->save());
  }

  function testCreatingInvoiceReturningItAndDeletingIt() {
    $invoice = new QuadernoInvoice(array(
                                 'number' => '00015',
                                 'subject' => 'Quaderno',
                                 'notes' => 'Yeah',
                                 'currency' => 'EUR'));
    $invoice->addContact($this->contact);
    $this->assertFalse($invoice->save());
    $invoice->addItem($this->item);
    $invoice->addItem($this->item);
    $invoice->addItem($this->item);
    $invoice->addPayment($this->payment);
    echo "Prueba: " . var_dump($invoice->getArray()) . "<br/><br/>";
    $this->assertTrue($invoice->save());
    /*$this->assertClone(QuadernoInvoice::find($invoice->id), $invoice);
    $invoice->addItem($this->item);
    $this->assertTrue($invoice->save());
    $this->assertTrue($invoice->deliver());
    $this->assertTrue($invoice->delete());*/
  }
}
?>