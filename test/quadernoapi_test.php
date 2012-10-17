<?php  
require_once('simpletest/autorun.php');
require_once('../quaderno_load.php');

class QuadernoApiTest extends TestSuite {
  const API_KEY = 'czirkmYm5AZvX5qDExJq';
  const ACCOUNT_ID = 'nippur-575';

  function __construct() {
    parent::__construct();
    QuadernoBase::init(self::API_KEY, self::ACCOUNT_ID, true);

    $this->addFile('unit/general_test.php');
    $this->addFile('unit/contact_test.php');
    $this->addFile('unit/invoice_test.php');
    $this->addFile('unit/expense_test.php');
    $this->addFile('unit/payment_test.php');
    $this->addFile('unit/estimate_test.php');
  }
}
?>