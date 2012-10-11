<?php  
require_once('simpletest/autorun.php');
require_once('../endeve_load.php');

class EndeveApiTest extends TestSuite {
  const API_KEY = 'czirkmYm5AZvX5qDExJq';
  const ACCOUNT_ID = 'nippur-575';

  function __construct() {
    parent::__construct();
    EndeveBase::init(self::API_KEY, self::ACCOUNT_ID, true);

    $this->addFile('unit/contact_test.php');
    //$this->addFile('unit/invoice_test.php');
    //$this->addFile('unit/item_test.php');
  }
}
?>