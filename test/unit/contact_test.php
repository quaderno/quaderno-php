<?php
class ContactTest extends UnitTestCase { 
  function __construct() {    
    //$contact = EndeveContact::find('Tim Cook');
    //if (isset($contact)) $contact->delete();
  }  

  // Search last contacts
  function testFindContactsReturnsArray() {
    $this->assertTrue(is_array(EndeveContact::find()));
  }

  // Search by ID
  function testFindContactWithInvalidIdReturnsFalse() {
    $this->assertFalse(EndeveContact::find("0"));
  }

  function testFindExistingID() {
    $this->assertTrue(EndeveContact::find("50758a232f412e144700001c") instanceof EndeveContact);
  }

  function testFindContactWithIdReturnsAContact() {
    $contact = new EndeveContact(array(
                                 'first_name' => 'Sergey',
                                 'last_name' => 'Brin'));
    $this->assertTrue($contact->save());
    $this->assertClone(EndeveContact::find($contact->id), $contact);
    $this->assertTrue($contact->delete());
  }

  // Search by name
  function testFindContactWithNonExistingNameReturnsFalse() {
    $this->assertFalse(EndeveContact::find('Tim Cook'));
  }

  function testFindContactWithNameReturnsAContact() {
    $contact = new EndeveContact(array(
                                 'first_name' => 'Joseph',
                                 'last_name' => 'Tribbiani'));
    $this->assertTrue($contact->save());
    $contact->first_name = 'Joey';
    $this->assertTrue($contact->save());
    $this->assertClone(EndeveContact::find('Joey'), $contact);
    $this->assertTrue($contact->delete());
  }

  // Search by properties
  function testFindContactWithWrongPropertiesReturnsFalse() {
    $this->assertFalse(EndeveContact::find(array('superhero' => 'robin')));
  }

  function testFindContactWithPropertiesReturnsArray() {
    $this->assertTrue(is_array(EndeveContact::find(array('page' => 2))));
  }

}
?>