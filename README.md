# PHP Wrapper for Quaderno API
This library is a PHP wrapper to connect and handle the [Quaderno API] (https://github.com/recrea/quaderno-api) in a nicer way. It connects to your account in [Quaderno] (https://quadernoapp.com), so you will need to have a valid account to use it.

## Why using it?
You will need this if you want to connect to the Quaderno API from your PHP application with no need to be handling annoying low-level HTTP requests and JSON-encoded data.

## Requirements
* PHP 5
* [cURL] (http://php.net/manual/en/book.curl.php) (included by default in recent PHP versions)

## Installation
Copy all the files into a single folder in your project

## Usage
A rule of thumb to take into account when using the wrapper is that calls to the API are actually only made with the methods: '_find()_', '_save()_', '_delete()_', '_deliver()_' and '_ping()_'.

### Load
```php
require_once 'quaderno_load.php';
```

### Setup
```php
QuadernoBase::init('YOUR_API_KEY', 'YOUR_ACCOUNT_ID');
```

### Testing connection
```php
QuadernoBase::ping();                         // Returns true (success) or false (error)
```

### Getting the authorization info
```php
QuadernoBase::authorization('YOUR_API_KEY');                  // Returns an array with your information
```
Example response:

```php
array(
  "identity" => array(
    "id" => 1,
    "name" => "James Earl Jones",
    "email" => "im@your.father",
    "href" => "https://quadernoapp.com/blackhelmet/api/v1/",
  ),
)

```

### Contacts
#### -- Find contacts
Returns _false_ if request fails.
```php
$contacts = QuadernoContact::find();                    // Returns an array of QuadernoContact
$contacts = QuadernoContact::find(array('page' => 2));  // Returns an array of QuadernoContact
$contact = QuadernoContact::find('IDTOFIND');           // Returns a QuadernoContact
```

#### -- Creating and updating a contact
```php
$contact = new QuadernoContact(array(
                                 'first_name' => 'Joseph',
                                 'last_name' => 'Tribbiani',
                                 'email' => 'joseph@friends.com',
                                 'contact_name' => 'Joey'));

$contact->save();                             // Returns true (success) or false (error)

$contact->first_name = "";
$contact->save();                             // Returns false - first_name is a required field
foreach($contact->errors as $field => $errors) { 
  print "{$field}: ";
  foreach ($errors as $e) print $e;
}

$contact->first_name = 'Joey';
$contact->save();
```
### Items
The items are those products or services that you sell to your customers.

#### -- Find items
Returns _false_ if request fails.
```php
$items = QuadernoItem::find();                    // Returns an array of QuadernoItem
$items = QuadernoItem::find('IDTOFIND');           // Returns a QuadernoItem
```

#### -- Creating and updating an item
```php
$item = new QuadernoItem(array(
                                 'name' => 'Jelly pizza',
                                 'code' => 'Yummy',
                                 'unit_cost' => '15.00',
                                 'tax_1_name' => 'JUNKTAX',
                                 'tax_1_rate' => '99.99'));

$item->save();                             // Returns true (success) or false (error)

$item->name = "";
$item->save();                             // Returns false - name is a required field
foreach($item->errors as $field => $errors) { 
  print "{$field}: ";
  foreach ($errors as $e) print $e;
}

$item->name = 'Jelly Pizza';
$item->tax_2_name = 'FOODTAX';
$item->tax_2_rate = '70.77';
$item->save();
```



### Documents
A document is either an _invoice_, an _expense_ or an _estimate_.

#### -- Find documents
```php
$invoices = QuadernoInvoice::find();                      // Returns an array of QuadernoInvoice
$invoices = QuadernoInvoice::find(array('page' => 2));    // Returns an array of QuadernoInvoice
$invoice = QuadernoInvoice::find("IDTOFIND");             // Returns a QuadernoInvoice
```

#### -- Create and update a document
```php
$estimate = new QuadernoEstimate(array(
                                 'subject' => 'Business website',
                                 'notes' => 'With mobile version',
                                 'currency' => 'EUR'));

$estimate->save();                            // Returns true (success) or false (error)

$estimate->notes = 'Finally, no mobile version will be necessary';
$estimate->save();
```

#### -- Deliver a document
Only possible in invoices and estimates. The contact must have an email address defined.
```php
$invoice->deliver();                          // Return true (success) or false (error)

```


### Payments
#### -- Add a payment to a document
Only possible in invoices and expenses. Input should be a QuadernoPayment object.
```php
$payment = new QuadernoPayment(array(                                         
                                 'date' => date('2012-10-10'),
                                 'payment_method' => 'credit_card'));

$invoice->addPayment($payment);               // Return true (success) or false (error)
$invoice->save();                             // Returns true (success) or false (error)
```

#### -- Get payments of a document
```php
$payments = $expense->getPayments();          // Returns an array of QuadernoPayment
```

#### -- Remove a payment from a document
```php
$expense->removePayment($payments[2]);         // Return true (success) or false (error)
```

### webhooks

#### -- Find webhooks
Returns _false_ if request fails.
```php
$webhooks = QuadernoWebhook::find();                    // Returns an array of QuadernoWebhook
$webhooks = QuadernoWebhook::find('IDTOFIND');           // Returns a QuadernoWebhook
```

#### -- Creating and updating an webhook
```php
$webhook = new QuadernoWebhook(array(
                                 'url' => 'http://myapp.com/notifications',
                                 'events' => array('created'));

$webhook->save();                             // Returns true (success) or false (error)

$webhook->url = "";
$webhook->save();                             // Returns false - url is a required field
foreach($webhook->errors as $field => $errors) { 
  print "{$field}: ";
  foreach ($errors as $e) print $e;
}

$webhook->url = 'http://anotherapp.com/quaderno/notifications';
$webhook->events = array('created', 'updated', 'deleted');
$webhook->save();
```

## More information
Remember this is only a PHP wrapper for the original API. If you want more information about the API itself, head to the original API documentation.

* [API Documentation] (https://github.com/recrea/quaderno-api)

## License
(The MIT License)

Copyright © 2013 recreahq.com

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the ‘Software’), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED ‘AS IS’, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

