<?php
class PaymentTest extends UnitTestCase { 
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

  function testCreatingDocumentAndAddingPayment() {
    $expense = new QuadernoExpense(array(
                                 'number' => '00013',
                                 'subject' => 'Testing Quaderno API',
                                 'notes' => 'Test execution',
                                 'currency' => 'EUR'));

    $expense->addContact($this->contacts[0]);    
    $expense->addItem($this->item);
    $this->assertTrue($expense->save());    
    $expense->addPayment($this->payment);
    $this->assertTrue($expense->save());
    $this->assertClone(QuadernoExpense::find($expense->id), $expense);
    $this->assertTrue($expense->delete());
  }

  function testCreatingDocumentWithPaymentInIt() {
    $expense = new QuadernoExpense(array(
                                 'number' => '00013',
                                 'subject' => 'Testing Quaderno API',
                                 'notes' => 'Test execution',
                                 'currency' => 'EUR'));

    $expense->addContact($this->contacts[0]);    
    $expense->addItem($this->item);
    $expense->addPayment($this->payment);
    $this->assertTrue($expense->save());
    $this->assertClone(QuadernoExpense::find($expense->id), $expense);
    $this->assertTrue($expense->delete());
  }

  function testSearchingForPaymentAndDeletingIt() {
    $expense = new QuadernoExpense(array(
                                 'number' => '00013',
                                 'subject' => 'Testing Quaderno API',
                                 'notes' => 'Test execution',
                                 'currency' => 'EUR'));

    $expense->addContact($this->contacts[0]);    
    $expense->addItem($this->item);
    $expense->addPayment($this->payment);
    $expense->addPayment($this->payment2);
    $this->assertTrue($expense->save());    

    $payments = $expense->getPayments();
    $this->assertTrue($expense->removePayment($payments[1]));
    $this->assertTrue($expense->save());
    $this->assertClone(QuadernoExpense::find($expense->id), $expense);
    $this->assertTrue($expense->delete());
  }
}