<?php
require_once(dirname(__FILE__) . '/../../quaderno_load.php');

class ItemTest extends UnitTestCase { 
  function __construct() {
  }  

  // Search last items
  function testFindItemsReturnsArray() {
    $this->assertTrue(is_array(QuadernoItem::find()));
  }

  // Search by ID
  function testFindItemWithInvalidIdReturnsFalse() {
    $this->assertFalse(QuadernoItem::find("0"));
  }

  function testFindItemWithIdReturnsAnItem() {
    $item = new QuadernoItem(array(
                                 'name' => 'Space meatballs',
                                 'unit_cost' => '21.00'));
    $this->assertTrue($item->save());
    $this->assertClone(QuadernoItem::find($item->id), $item);
    $id = $item->id;
    $this->assertTrue($item->delete());
    $this->assertNull($item->id);
    $this->assertFalse(QuadernoItem::find($id));
  }

  function testCreatingAndModifyingItem() {
    $item = new QuadernoItem(array(
                                 'name' => 'Umbrella juice',
                                 'unit_cost' => '35.00'));
    $this->assertTrue($item->save());
    $this->assertEqual($item->name, 'Umbrella juice');
    $item->name = "";
    $this->assertFalse($item->save());   // Fails because 'name' field is required
    $this->assertNotNull($item->errors["name"]);
    $item->name = "Umbrella juice";
    $this->assertTrue($item->save());
    $this->assertEqual($item->name, 'Umbrella juice');
    $this->assertTrue($item->delete());
  }
}
?>