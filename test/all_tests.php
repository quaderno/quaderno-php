<?php  
require_once(dirname(__FILE__) . '/simpletest/autorun.php');
require_once(dirname(__FILE__) . '/../quaderno_load.php');

class AllTests extends TestSuite {
  # please use your own api_key and subdomain
  const API_KEY = 'HPx1vDBKCG85X1HppFo8';
  const API_URL = 'http://uruk-1832.sandbox-quadernoapp.com/api/v1/';

  function __construct() {
    parent::__construct();
    QuadernoBase::init(self::API_KEY, self::API_URL);
    $this->addFile(dirname(__FILE__) . '/unit/general_test.php');
    $this->addFile(dirname(__FILE__) . '/unit/contact_test.php');
    $this->addFile(dirname(__FILE__) . '/unit/item_test.php');
    $this->addFile(dirname(__FILE__) . '/unit/invoice_test.php');
    $this->addFile(dirname(__FILE__) . '/unit/expense_test.php'); 
    $this->addFile(dirname(__FILE__) . '/unit/payment_test.php');
    $this->addFile(dirname(__FILE__) . '/unit/estimate_test.php');
  }
}
?>
