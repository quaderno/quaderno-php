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
    $id = $contact->id;
    $this->assertTrue($contact->delete());
    $this->assertNull($contact->id);
    $this->assertFalse(QuadernoContact::find($id));
  }

  function testCreatingAndModifyingContact() {
    $contact = new QuadernoContact(array(
                                 'first_name' => 'Joseph',
                                 'last_name' => 'Tribbiani'));
    $this->assertTrue($contact->save());
    $this->assertEqual($contact->full_name, 'Joseph Tribbiani');
    $contact->first_name = 'Joey';
    $this->assertTrue($contact->save());    
    $this->assertEqual($contact->full_name, 'Joey Tribbiani');
    $this->assertTrue($contact->delete());
  }
}
?>