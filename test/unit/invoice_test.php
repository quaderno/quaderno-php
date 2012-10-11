<?php
class InvoiceTest extends UnitTestCase { 
  function __construct() {
  }  

  // Search last invoices
  function findInvoiceReturnsArray() {
    assertTrue(is_array(EndeveInvoice::find()));
  }

  // Search by ID
  function findInvoiceWithInvalidIdReturnsFalse() {
    assertFalse(EndeveInvoice::find(0));
  }

  function findInvoiceWithIdReturnsAInvoice() {
    $invoice = new EndeveInvoice(array(
                                 'number' => '0001',
                                 'contact_id' => '1',
                                 'currency' => 'EUR'));
    assertTrue($invoice->save());
    assertClone(EndeveInvoice::find($invoice->id), $invoice);
    assertTrue($invoice->deliver());
    assertTrue($invoice->delete());
  }

  // Search by properties
  function findInvoiceWithWrongPropertiesReturnsFalse() {
    assertFalse(EndeveInvoice::find(array('superhero' => 'robin')));
  }

  function findInvoiceWithPropertiesReturnsArray() {
    $invoice = new EndeveInvoice(array(
                                 'number' => '0002'));
    assertTrue($invoice->save());
    $invoice->number = '0067';
    assertTrue($invoice->save());
    assertClone(EndeveInvoice::find(array('number' => '0067'), $invoice));
    assertTrue($invoice->delete());
  }
}
?>