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
  }

  function testCreatingDocumentAndAddingPayment() {
    $expense = new QuadernoExpense(array(
                                 'number' => '00013',
                                 'subject' => 'Testing Quaderno API',
                                 'notes' => 'Test execution',
                                 'currency' => 'EUR'));

    $payment = new QuadernoPayment(array(                                         
                                         'date' => date('2012-10-10'),
                                         'payment_method' => 'credit_card'
                                         ));

    $expense->addContact($this->contacts[0]);    
    $expense->addItem($this->item);
    $this->assertTrue($expense->save());    
    $expense->addPayment($payment);    
    $this->assertTrue($expense->save());    
    $exp = QuadernoExpense::find($expense->id);
    /////////
    $exp->correctAmount();
    $expense->correctUrl();    
    /////////
    $this->assertClone($exp, $expense);
    $this->assertTrue($expense->delete());
  }

  function testCreatingDocumentWithPaymentInIt() {
    $expense = new QuadernoExpense(array(
                                 'number' => '00013',
                                 'subject' => 'Testing Quaderno API',
                                 'notes' => 'Test execution',
                                 'currency' => 'EUR'));

    $payment = new QuadernoPayment(array(                                         
                                         'date' => date('2012-10-10'),
                                         'payment_method' => 'credit_card'
                                         ));

    $expense->addContact($this->contacts[0]);    
    $expense->addItem($this->item);
    $expense->addPayment($payment);
    $this->assertTrue($expense->save());
    $exp = QuadernoExpense::find($expense->id);
    /////////
    $exp->correctAmount();
    $expense->correctUrl();
    /////////
    $this->assertClone($exp, $expense);
    $this->assertTrue($expense->delete());
  }

  function testCreatingDocumentWithTwoPayments() {
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

    $expense->addContact($this->contacts[0]);    
    $expense->addItem($this->item);
    $expense->addPayment($payment);
    $expense->addPayment($payment2);
    $this->assertTrue($expense->save());
    $exp = QuadernoExpense::find($expense->id);    
    /////////    
    $exp->correctAmount();
    $expense->correctUrl();
    ////////
    $this->assertClone($exp, $expense);


    $payments = $expense->getPayments();
    $this->assertTrue($expense->removePayment($payments[0]));
    $this->assertTrue($expense->save());
    $exp = QuadernoExpense::find($expense->id);    
    ///////////
    $exp->correctAmount();
    ///////////
    $this->assertClone($exp, $expense);
    $this->assertTrue($expense->delete());
  }
}