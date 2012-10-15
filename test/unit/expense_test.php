<?php
class ExpenseTest extends UnitTestCase { 
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

  // Search last expenses
  function testFindExpenseReturnsArray() {
    $this->assertTrue(is_array(QuadernoExpense::find()));
  }

  // Search by ID
  function testFindExpenseWithInvalidIdReturnsFalse() {
    $this->assertFalse(QuadernoExpense::find("Invalid Id"));
  }

  function testCreatingAnExpenseWithNoItemsReturnFalse() {
    $expense = new QuadernoExpense(array(
                                 'subject' => 'Failing test Quaderno',
                                 'notes' => 'This should fail'));
    $expense->addContact($this->contacts[0]);
    $expense->addPayment($this->payment);
    $this->assertFalse($expense->save());
  }

  function testCreatingExpenseReturningItAndDeletingIt() {
    $expense = new QuadernoExpense(array(
                                 'number' => '00013',
                                 'subject' => 'Testing Quaderno API',
                                 'notes' => 'Test execution',
                                 'currency' => 'EUR'));        
    $expense->addContact($this->contacts[0]);
    $this->assertFalse($expense->save());
    $expense->addItem($this->item);
    $expense->addItem($this->item);
    $expense->addItem($this->item);
    $this->assertTrue($expense->save());
    $this->assertClone(QuadernoExpense::find($expense->id), $expense);
    $expense->notes = 'Changing notes!';
    $this->assertTrue($expense->save());
    $this->assertEqual('Changing notes!', $expense->notes);
    $this->assertTrue($expense->delete());
  }  

}
?>