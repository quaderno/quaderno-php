<?php
class ContactTest extends UnitTestCase { 
  function __construct() {    
    //$contact = QuadernoContact::find('Tim Cook');
    //if (isset($contact)) $contact->delete();
  }  

  // Search last contacts
  function testFindContactsReturnsArray() {
    $this->assertTrue(is_array(QuadernoContact::find()));
  }

  // Search by ID
  function testFindContactWithInvalidIdReturnsFalse() {
    $this->assertFalse(QuadernoContact::find("0"));
  }

  function testFindExistingID() {
    $this->assertTrue(QuadernoContact::find("50758a232f412e144700001c") instanceof QuadernoContact);
  }

  function testFindContactWithIdReturnsAContact() {
    $contact = new QuadernoContact(array(
                                 'first_name' => 'Sergey',
                                 'last_name' => 'Brin'));
    $this->assertTrue($contact->save());
    $this->assertClone(QuadernoContact::find($contact->id), $contact);
    $this->assertTrue($contact->delete());
  }

  // Search by name
  function testFindContactWithNonExistingNameReturnsFalse() {
    $this->assertFalse(QuadernoContact::find('Tim Cook'));
  }

  function testFindContactWithNameReturnsAContact() {
    $contact = new QuadernoContact(array(
                                 'first_name' => 'Joseph',
                                 'last_name' => 'Tribbiani'));
    $this->assertTrue($contact->save());
    $contact->first_name = 'Joey';
    $this->assertTrue($contact->save());
    $this->assertClone(QuadernoContact::find('Joey'), $contact);
    $this->assertTrue($contact->delete());
  }

  // Search by properties
  function testFindContactWithWrongPropertiesReturnsFalse() {
    $this->assertFalse(QuadernoContact::find(array('superhero' => 'robin')));
  }

  function testFindContactWithPropertiesReturnsArray() {
    $this->assertTrue(is_array(QuadernoContact::find(array('page' => 2))));
  }

}
?>