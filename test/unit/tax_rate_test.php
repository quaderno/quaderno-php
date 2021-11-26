<?php
require_once(dirname(__FILE__) . '/../../quaderno_load.php');

class TaxRateTest extends UnitTestCase {
  function testCalculateReturnsTaxRateObject() {
    $this->assertIsA(QuadernoTaxRate::calculate(['to_country' => 'gb', 'tax_id' => null]), QuadernoTaxRate::class);
  }

  function testCalculateReturnsFalse() {
    // Provide the base object with invalid creds
    $originalApiKey = QuadernoBase::getApiKey();
    $originalApiUrl = QuadernoBase::getApiUrl();
    QuadernoBase::init('doesnotexist', 'doesnotexist');

    $this->assertFalse(QuadernoTaxRate::calculate(['to_country' => 'gb', 'tax_id' => null]));

    // Reset base object to original
    QuadernoBase::init($originalApiKey, $originalApiUrl);
  }

  function testCalculateReturnsErrorsIfRequested() {
    // Provide the base object with invalid creds
    $originalApiKey = QuadernoBase::getApiKey();
    $originalApiUrl = QuadernoBase::getApiUrl();
    QuadernoBase::init('doesnotexist', 'doesnotexist');

    $errorMessage = null;
    $taxRate = QuadernoTaxRate::calculate(['to_country' => 'gb', 'tax_id' => null], function($apiErrorMessage) use (&$errorMessage) {
      $errorMessage = $apiErrorMessage;
    });

    $this->assertFalse($taxRate);
    $this->assertNotNull($errorMessage, $errorMessage);

    // Reset base object to original
    QuadernoBase::init($originalApiKey, $originalApiUrl);
  }
}
?>
