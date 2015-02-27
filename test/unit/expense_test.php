<?php
require_once(dirname(__FILE__) . '/../../quaderno_load.php');

class ExpenseTest extends UnitTestCase { 
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
    $expense->addContact($this->contact);
    $this->assertFalse($expense->save()); // Impossible to create doc w/o items
  }

  function testCreatingExpenseReturningItAndDeletingIt() {
    $expense = new QuadernoExpense(array(
                                 'number' => '00013',
                                 'subject' => 'Testing Quaderno API',
                                 'notes' => 'Test execution',
                                 'currency' => 'EUR'));        
    $expense->addContact($this->contact);
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