<?php
class ExpenseTest extends UnitTestCase { 
  private $contact = null;
  private $item = null;
  private $payment = null;

  function __construct() {
    $this->contact = new QuadernoContact(array(
                         'first_name' => 'Chuck',
                         'last_name' => 'Norris'));

    $this->item = new QuadernoItem(array(
                                   'description': 'concepto 1',
                                   'price': 100.0,
                                   'quantity': 20
                                   ));

    $this->payment = new QuadernoPayment(array(
                                         //'amount' => 150, Funcionará si le meto más del total?
                                         'date' => date('2012-10-10'),
                                         'payment_method' => 'Transfer'
                                         ));
  }  

  // Search last expenses
  function testFindExpenseReturnsArray() {
    $this->assertTrue(is_array(QuadernoExpense::find()));
  }

  // Search by ID
  function testFindExpenseWithInvalidIdReturnsFalse() {
    $this->assertFalse(QuadernoExpense::find("0"));
  }

  function testCreatingAnExpenseWithNoItemsReturnFalse() {
    $expense = new QuadernoExpense(array(
                                 'subject' => 'Quaderno',
                                 'notes' => 'Yeah'));
    $expense->addContact($this->contact);
    $expense->addPayment($this->payment);
    $this->assertFalse($expense->save());
  }

  function testCreatingExpenseReturningItAndDeletingIt() {
    $expense = new QuadernoExpense(array(
                                 'subject' => 'Quaderno',
                                 'notes' => 'Yeah'));
    $expense->addContact($this->contact);
    $this->assertFalse($expense->save());
    $expense->addItem($this->item);
    $expense->addItem($this->item);
    $expense->addItem($this->item);
    $expense->addPayment($this->payment);
    $this->assertTrue($expense->save());
    $this->assertClone(QuadernoExpense::find($expense->id), $expense);
    $this->assertTrue($expense->delete());
  }

  function testFindExpenseWithInvalidIdReturnsFalse() {
    $this->assertFalse(QuadernoExpense::find("pericopalotes"));
  }
}
?>