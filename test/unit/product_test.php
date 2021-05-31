<?php
require_once(dirname(__FILE__) . '/../../quaderno_load.php');

class ItemTest extends UnitTestCase {
  function __construct() {
  }

  // Search last items
  function testFindItemsReturnsArray() {
    $this->assertTrue(is_array(QuadernoProduct::find()));
  }

  // Search by ID
  function testFindItemWithInvalidIdReturnsFalse() {
    $this->assertFalse(QuadernoProduct::find("0"));
  }

  function testFindItemWithIdReturnsAnItem() {
    $item = new QuadernoProduct(array(
                                 'code' => 'SPACE9999',
                                 'name' => 'Space meatballs',
                                 'unit_cost' => '21.00'));
    $this->assertTrue($item->save());
    $this->assertClone(QuadernoProduct::find($item->id), $item);
    $id = $item->id;
    $this->assertTrue($item->delete());
    $this->assertNull($item->id);
    $this->assertFalse(QuadernoProduct::find($id));
  }

  function testCreatingAndModifyingItem() {
    $item = new QuadernoProduct(array(
                                 'code' => 'SPACE9999',
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