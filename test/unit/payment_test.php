<?php
class PaymentTest extends UnitTestCase { 
  private $contact = null;
  private $item = null;

  function __construct() {
    // General items and contacts to use
    $this->contact = new QuadernoContact(array(
                                 'first_name' => 'Joseph',
                                 'last_name' => 'Tribbiani',
                                 'contact_name' => 'Friends Staff'));
    $this->contact->save();

    $this->item = new QuadernoDocumentItem(array(
                                   'description' => 'concepto 1',
                                   'price' => 100.0,
                                   'quantity' => 20
                                   ));
  }

  function __destruct() {
    $this->contact->delete();
  }

  function testCreatingExpenseAndAddingPayment() {
    $expense = new QuadernoExpense(array(
                                 'number' => '00013',
                                 'subject' => 'Testing Quaderno API',
                                 'notes' => 'Test execution',
                                 'currency' => 'EUR'
                                    ));

    $payment = new QuadernoPayment(array(                                         
                                 'date' => date('2012-10-10'),
                                 'payment_method' => 'credit_card'
                                    ));

    $expense->addContact($this->contact);    
    $expense->addItem($this->item);
    $this->assertTrue($expense->save());    
    $expense->addPayment($payment);    
    $this->assertTrue($expense->save());    
    $exp = QuadernoExpense::find($expense->id);
    $this->assertClone($exp, $expense);
    $this->assertTrue($expense->delete());
  }

  function testCreatingExpenseWithPaymentInIt() {
    $expense = new QuadernoExpense(array(
                                 'number' => '00013',
                                 'subject' => 'Testing Quaderno API',
                                 'notes' => 'Test execution',
                                 'currency' => 'EUR'));

    $payment = new QuadernoPayment(array(                                         
                                         'date' => date('2012-10-10'),
                                         'payment_method' => 'credit_card'
                                         ));

    $expense->addContact($this->contact);    
    $expense->addItem($this->item);
    $expense->addPayment($payment);
    $this->assertTrue($expense->save());
    $exp = QuadernoExpense::find($expense->id);
    $this->assertClone($exp, $expense);
    $this->assertTrue($expense->delete());
  }

  function testCreatingExpenseWithTwoPayments() {
    $expense = new QuadernoExpense(array(
                                 'number' => '00013',
                                 'subject' => 'Testing Quaderno API',
                                 'notes' => 'Test execution',
                                 'currency' => 'EUR'));

    $payment = new QuadernoPayment(array(
                                     'date' => date('2012-10-10'),
                                     'payment_method' => 'credit_card'
                                     ));

    $payment2 = new QuadernoPayment(array(
                                     'date' => date('2012-10-12'),
                                     'payment_method' => 'credit_card'
                                     ));

    $expense->addContact($this->contact);
    $expense->addItem($this->item);
    $expense->addPayment($payment);
    $expense->addPayment($payment2);
    $this->assertTrue($expense->save());
    $exp = QuadernoExpense::find($expense->id);
    $this->assertClone($exp, $expense);


    $payments = $expense->getPayments();
    $this->assertTrue($expense->removePayment($payments[0]));
    $this->assertTrue($expense->save());
    $exp = QuadernoExpense::find($expense->id);
    $this->assertClone($exp, $expense);
    $this->assertTrue($expense->delete());
  }

  function testCreatingInvoiceAndAddingPayment() {
    $invoice = new QuadernoInvoice(array(
                                 'subject' => 'Testing Quaderno API',
                                 'notes' => 'Test execution',
                                 'currency' => 'EUR'));

    $payment = new QuadernoPayment(array(                                         
                                         'date' => date('2012-10-10'),
                                         'payment_method' => 'credit_card'
                                         ));

    $invoice->addContact($this->contact);    
    $invoice->addItem($this->item);
    $this->assertTrue($invoice->save());    
    $invoice->addPayment($payment);    
    $this->assertTrue($invoice->save());    
    $inv = QuadernoInvoice::find($invoice->id);
    $this->assertClone($inv, $invoice);
    $this->assertTrue($invoice->delete());
  }

  function testCreatingInvoiceWithPaymentInIt() {
    $invoice = new QuadernoInvoice(array(
                                 'subject' => 'Testing Quaderno API',
                                 'notes' => 'Test execution',
                                 'currency' => 'EUR'));

    $payment = new QuadernoPayment(array(                                         
                                         'date' => date('2012-10-10'),
                                         'payment_method' => 'credit_card'
                                         ));

    $invoice->addContact($this->contact);    
    $invoice->addItem($this->item);
    $invoice->addPayment($payment);
    $this->assertTrue($invoice->save());
    $inv = QuadernoInvoice::find($invoice->id);
    $this->assertClone($inv, $invoice);
    $this->assertTrue($invoice->delete());
  }

  function testCreatingInvoiceWithTwoPayments() {
    $invoice = new QuadernoInvoice(array(
                                 'subject' => 'Testing Quaderno API',
                                 'notes' => 'Test execution',
                                 'currency' => 'EUR'));

    $payment = new QuadernoPayment(array(                                         
                                     'date' => date('2012-10-10'),
                                     'payment_method' => 'credit_card'
                                     ));

    $payment2 = new QuadernoPayment(array(                                         
                                     'date' => date('2012-10-12'),
                                     'payment_method' => 'credit_card'
                                     ));

    $invoice->addContact($this->contact);    
    $invoice->addItem($this->item);
    $invoice->addPayment($payment);
    $invoice->addPayment($payment2);
    $this->assertTrue($invoice->save());
    $inv = QuadernoInvoice::find($invoice->id);
    $this->assertClone($inv, $invoice);


    $payments = $invoice->getPayments();
    $this->assertTrue($invoice->removePayment($payments[0]));
    $this->assertTrue($invoice->save());
    $inv = QuadernoInvoice::find($invoice->id);
    $this->assertClone($inv, $invoice);
    $this->assertTrue($invoice->delete());
  }
}