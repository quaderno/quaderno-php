<?php
require_once(dirname(__FILE__) . '/simpletest/autorun.php');
require_once(dirname(__FILE__) . '/../quaderno_load.php');

class AllTests extends TestSuite {
  # please use your own api_key and subdomain
  const API_KEY = 'sk_test_7oEdy3CW5z8QzwgCk4hp';
  const API_URL = 'http://sandbox-quadernoapp.com/api/';

  function __construct() {
    parent::__construct();
    QuadernoBase::init(self::API_KEY, self::API_URL);
    $this->addFile(dirname(__FILE__) . '/unit/general_test.php');
    $this->addFile(dirname(__FILE__) . '/unit/contact_test.php');
    $this->addFile(dirname(__FILE__) . '/unit/product_test.php');
    $this->addFile(dirname(__FILE__) . '/unit/invoice_test.php');
    $this->addFile(dirname(__FILE__) . '/unit/expense_test.php');
    $this->addFile(dirname(__FILE__) . '/unit/estimate_test.php');
    $this->addFile(dirname(__FILE__) . '/unit/payment_test.php');
  }
}
?>
