<?php
class ContactTest extends UnitTestCase { 
  function __construct() {
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
  /*function testFindContactWithNonExistingNameReturnsFalse() {
    $result = QuadernoContact::find(array('full_name' => 'Tim Cook'));
    $this->assertFalse($result);
  }*/

  function testFindContactWithNameReturnsAContact() {
    $contact = new QuadernoContact(array(
                                 'full_name' => 'Joseph Tribbiani',
                                 'contact_name' => 'Ismuser'));    
    $this->assertTrue($contact->save());
    $id = $contact->id;
    $contact->contact_name = 'Joey';
    $this->assertTrue($contact->save());
    $this->assertTrue($contact->delete());
    $this->assertNull($contact->id);
    $this->assertFalse($contact::find($id));
  }

  // Search by properties
  /*function testFindContactWithWrongPropertiesReturnsFalse() {
    $this->assertFalse(QuadernoContact::find(array('superhero' => 'robin')));
  }

  function testFindContactWithPropertiesReturnsArray() {
    $this->assertTrue(is_array(QuadernoContact::find(array('page' => 2))));
  }*/

}
?>