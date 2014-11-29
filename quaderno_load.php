<?php
/* This can put quaderno wrapper in a own folder */
$quaderno_script_path = str_replace('\\', '/', dirname(__FILE__)) . '/';
/* Interfae to load every needed file.
  This is the ONLY file to include from external code */
require_once $quaderno_script_path . 'quaderno_class.php';
require_once $quaderno_script_path . 'quaderno_model.php';
require_once $quaderno_script_path . 'quaderno_base.php';
require_once $quaderno_script_path . 'quaderno_contact.php';
require_once $quaderno_script_path . 'quaderno_item.php';
require_once $quaderno_script_path . 'quaderno_document.php';
require_once $quaderno_script_path . 'quaderno_expense.php';
require_once $quaderno_script_path . 'quaderno_estimate.php';
require_once $quaderno_script_path . 'quaderno_invoice.php';
require_once $quaderno_script_path . 'quaderno_document_item.php';
require_once $quaderno_script_path . 'quaderno_payment.php';
require_once $quaderno_script_path . 'quaderno_json.php';
require_once $quaderno_script_path . 'quaderno_webhook.php';
require_once $quaderno_script_path . 'quaderno_tax.php';
