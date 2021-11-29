# PHP Wrapper for Quaderno API
This library is a PHP wrapper to connect and handle the [Quaderno API](https://developers.quaderno.io/api/) in a nicer way. It connects to your PHP app with [Quaderno](https://quaderno.io), so you will need to have a valid Quaderno account to use it.

You can use our [sandbox environment](https://sandbox-quadernoapp.com/) to test your processes before going live.

## Why using it?
You will need this if you want to connect to the Quaderno API from your PHP app with no need to be handling annoying low-level HTTP requests and JSON-encoded data.

## Requirements
* PHP 5 or higher
* [cURL](http://php.net/manual/en/book.curl.php) (included by default in recent PHP versions)

## Installation
Install via [Composer](https://packagist.org/packages/quaderno/quaderno), or just copy all the files into a single folder in your project.

## Usage
A rule of thumb to take into account when using the wrapper is that calls to the API are actually only made with the methods: '_find()_', '_save()_', '_delete()_', '_deliver()_' and '_ping()_'.

### Load
```php
require_once 'quaderno_load.php';
```


### Setup
```php
QuadernoBase::init('YOUR_API_KEY', 'YOUR_API_URL', API_VERSION); // API_VERSION is optional as it defaults to the account API version
```


### Testing connection
```php
QuadernoBase::ping();   // returns true (success) or false (error)
```

### Pagination

⚠️ There's a known limitation on the current version of the PHP wrapper which does not allow to easily inspect response headers, like the ones used to facilitate pagination from version [20210316](https://developers.quaderno.io/api/#safely-upgrading-to-api-version-20210316) (`X-Pages-NextPage` and `X-Pages-HasMore`). For now, you can workaround that either by playing with more requests with smaller data ranges and higher `limit` to fetch more docs (max 100); or fetch your first find data with any query and then perform another paginated request with the `created_before` parameter and the ID of the last returned document, until the response is empty.

```php
// Simple example to request all pages from the given dates until all invoices have been returned:
$invoices = [];
$filters['limit'] = '50'; // bunches of 50 invoices per request
$filters['date']= "$from,$to"; // please use dates to avoid always getting all invoices
do {
  $data = QuadernoInvoice::find($filters);
  if (is_array($data) && !empty($data)) {
    $invoices = array_merge($invoices, $data);
    $last_invoice = end($data); // advances array's internal pointer to the last element, and returns its value
    $filters['created_before'] = $last_invoice->id; // paginate from there
  }
} while (!empty($data));
```

### Taxes
Quaderno allows you to calculate tax rates and validate tax IDs.

####  Calculating taxes
Check our [API docs](https://developers.quaderno.io/api/#calculating-a-tax-rate) to know all valid parameters.

```php
$params = array(
  'to_country' => 'ES',
  'to_postal_code' => '08080'
);

$tax = QuadernoTaxRate::calculate($params);   // returns a QuadernoTax
$tax->name;  // 'IVA'
$tax->rate;  // 21.0


// A non-processable request (due to invalid data, outages, etc.) returns false boolean
QuadernoBase::init('fakeApiKey', 'https://fake-subdomain.quadernoapp.com/api/');

$tax = QuadernoTaxRate::calculate($params);
$tax // false

// Example using a callback to get the specific error response
$tax = QuadernoTaxRate::calculate($params, function($apiErrorResponse) use (&$errorResponse) {
  $errorResponse = $apiErrorResponse;
});

$tax; // false
$errorResponse['http_code']; // 401
$errorResponse['data']['error']; // "Wrong API key or the user does not exist."

```

#### Validate tax ID
Check our [API docs](https://developers.quaderno.io/api/#validate-a-tax-id) to know the supported tax jurisdictions.

```php
$params = array(
  'country' => 'ES',
  'tax_id' => 'ESA58818501'
);

QuadernoTaxId::validate($params);   // returns boolean (true or false)
```


### Contacts
A contact is any customer or vendor who appears on your invoices, credit notes, and expenses.

#### Find contacts
Returns _false_ if request fails.

```php
$contacts = QuadernoContact::find();                              // returns an array of QuadernoContact
$contacts = QuadernoContact::find(array('created_before' => 2));  // returns an array of QuadernoContact
$contact = QuadernoContact::find('ID_TO_FIND');                   // returns a QuadernoContact
```

#### Creating and updating a contact

```php
$params = array('first_name' => 'Joseph',
                'last_name' => 'Tribbiani',
                'email' => 'joseph@friends.com',
                'contact_name' => 'Joey');
$contact = new QuadernoContact($params);
$contact->save(); // returns true (success) or false (error)

$contact->first_name = '';
$contact->save(); // returns false - first_name is a required field

// print errors
foreach($contact->errors as $field => $errors) {
  print "{$field}: ";
  foreach ($errors as $e) print $e;
}

$contact->first_name = 'Joey';
$contact->save();
```

#### Retrieve a contact by payment processor ID

```php
$gateway = 'stripe';
$customer_id = 'cus_Av4LiDPayM3nt_ID';

$contact = QuadernoContact::retrieve($id, $gateway);  // returns a QuadernoContact (success) or false (error)
```


### Products
Products are those goods or services that you sell to your customers.

#### Find products
Returns _false_ if request fails.

```php
$items = QuadernoProduct::find();              // returns an array of QuadernoProduct
$items = QuadernoProduct::find('ID_TO_FIND');  // returns a QuadernoProduct
```

#### Creating and updating a product

```php
$params = array('name' => 'Jelly pizza',
                'code' => 'Yummy',
                'unit_cost' => '15.00');
$item = new QuadernoProduct($params);
$item->save();  // returns true (success) or false (error)

$item->name = '';
$item->save();  // returns false - name is a required field

foreach($item->errors as $field => $errors) {
  print "{$field}: ";
  foreach ($errors as $e) print $e;
}

$item->name = 'Jelly Pizza';
$item->save();
```


### Transactions
Create a Transaction object to easily send sales & refunds from your app to Quaderno. We'll use them to issue invoices & credit notes, as well as update tax reports automatically.

#### Create a transaction

```php
$params = array(
  'type' => 'sale',
  'customer' => array(
    'id' => $contact->id
  ),
  'date' => date('Y-m-d'),
  'currency' => 'EUR',
  'processor' => 'YOUR_APP_NAME',
  'processor_id' => 'TRANSACTION_ID_IN_YOUR_APP',
  'payment' => array(
    'method' => 'credit_card',
    'processor' => 'stripe',
    'processor_id' => 'ch_1IMFwhB2xq6voISLLk4I1KeE'
  ),
  'notes' => 'With mobile version'
);

$transaction = new QuadernoTransaction($params);

$items = array();
array_push( $items, array('description' => 'Pizza bagles', 'quantity' => 10, 'amount' => 99.90));
$transaction->items = $items;

$transaction->save(); // returns true (success) or false (error)
```


### Billing documents
A billing document is either an _invoice_, an _expense_, a _credit_ or an _estimate_.

#### Find documents

```php
$invoices = QuadernoInvoice::find();                              // returns an array of QuadernoInvoice
$invoices = QuadernoInvoice::find(array('created_before' => 2));  // returns an array of QuadernoInvoice
$invoice = QuadernoInvoice::find('ID_TO_FIND');                   // returns a QuadernoInvoice
```

Note: In order to looking up for number, contact name or P.O. number fields, you must set the 'q' param in the params array.

```php
$invoices = QuadernoInvoice::find(array('q' => '0001')); // search filtering
```

#### Retrieve a document by payment processor ID

```php
$gateway = 'stripe';
$payment_id = 'ch_Av4LiDPayM3nt_ID';
$refund_id = 'ch_Av4LiDR3fuNd_ID';

$invoice = QuadernoInvoice::retrieve($payment_id, $gateway); // returns a QuadernoInvoice (success) or false (error)
$credit_note = QuadernoCredit::retrieve($refund_id, $gateway); // returns a QuadernoCredit (success) or false (error)
```

#### Create and update a document

```php
$params = array('currency' => 'EUR',
                'notes' => 'With mobile version');

$estimate = new QuadernoEstimate($params);
$estimate->addContact($contact);

$item = new QuadernoDocumentItem(array('description' => 'Pizza bagles', 'unit_price' => 9.99, 'quantity' => 20));
$estimate->addItem($item);

$estimate->save();  // returns true (success) or false (error)

$estimate->notes = 'Finally, no mobile version will be necessary';
$estimate->save();
```

#### Deliver a document
Only possible in invoices, credit notes, and estimates. The contact must have an email address defined.

```php
$invoice->deliver();  // returns true (success) or false (error)
```


### Payments
Payments in Quaderno-lingo represent the recording of a successful payment.

#### Add a payment to a document
You can add a payment only to invoices and expenses. Input should be a QuadernoPayment object.

```php
$payment = new QuadernoPayment(array('date' => date('Y-m-d'), 'payment_method' => 'credit_card'));
$invoice->addPayment($payment);   // returns true (success) or false (error)
$invoice->save();                 // returns true (success) or false (error)
```
#### Get payments

```php
$payments = $invoice->getPayments();  // returns an array of QuadernoPayment
```

#### Remove a payment

```php
$invoice->removePayment($payments[2]);  // returns true (success) or false (error)
```


### Evidence
Location evidence are proofs of the customer's location that should be stored in order to be compliant with tax rules in some tax jurisdictions (e.g. EU VAT).

#### Creating a location evidence

```php
$evidence = new QuadernoEvidence(array('document_id' => $invoice->id, 'billing_country' => $contact->country, 'ip_address' => '127.0.0.1', 'bank_country' => 'ES'));
$evidence->save();  // returns true (success) or false (error)
```


### Webhooks
Webhooks refers to a combination of elements that collectively create a notification and reaction system within a larger integration.

#### Find webhooks
Returns _false_ if request fails.

```php
$sessions = QuadernoCheckoutSession::find();                    // returns an array of QuadernoCheckoutSession
$sessions = QuadernoCheckoutSession::find('ID_TO_FIND');          // returns a QuadernoCheckoutSession
```


### Sessions
A Checkout Session represents your customer's session as they pay for one-time purchases or subscriptions through Quaderno Checkout.

#### Creating and updating a Checkout Session
```php
$params = array('cancel_url' => 'http://myapp.com/back',
                'success_url' => 'http://myapp.com/thank-you',
                'items' => array(array('product' => 'prod_123456'));

$session = new QuadernoCheckoutSession($params);
$session->save();              // returns true (success) or false (error)

$session->success_url = '';
$session->save();              // returns false - url is a required field

foreach($session->errors as $field => $errors) {
  print "{$field}: ";
  foreach ($errors as $e) print $e;
}

$session->success_url = 'http://mapp.com/thank-you?product=xxxxx';
$session->save();
```


## More information
Remember this is only a PHP wrapper for the original API. If you want more information about the API itself, head to the original [API documentation](https://developers.quaderno.io/api/).

## License
(The MIT License)

Copyright © 2013-2021 Quaderno

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the ‘Software’), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED ‘AS IS’, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
