<?php
/**
* Quaderno Document
*
* @package   Quaderno PHP
* @author    Quaderno <hello@quaderno.io>
* @copyright Copyright (c) 2015, Quaderno
* @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
*/

/* Interface that implements every document: invoices, expenses, credits and estimates */
abstract class QuadernoDocument extends QuadernoModel
{
	protected $payments_array = array();

	public function __construct($newdata)
	{
		parent::__construct($newdata);
		if (isset($this->data['payments']))
		{
			foreach ($this->data['payments'] as $p)
				array_push($this->payments_array, new QuadernoPayment($p));
		}
	}

	public function addContact($contact)
	{
		$this->data['contact_id'] = $contact->id;
		$this->data['contact_name'] = $contact->full_name;
		return isset($this->data['contact_id']) && isset($this->data['contact_name']);
	}

	public function addItem($item)
	{
		$length = isset($this->data['items_attributes']) ? count($this->data['items_attributes']) : 0;
		$this->data['items_attributes'][$length] = $item->getArray();
		return count($this->data['items_attributes']) == $length + 1;
	}

	/* Interface - only subclasses which implement original ones (i.e. without exec-) can call these methods */
	protected function execAddPayment($payment)
	{
		$length = count($this->payments_array);
		return array_push($this->payments_array, $payment) == $length + 1;
	}

	protected function execGetPayments()
	{
		return $this->payments_array;
	}

	protected function execRemovePayment($payment)
	{
		$i = array_search($payment, $this->payments_array, true);
		if ($i >= 0) $this->payments_array[$i]->markToDelete = true;
		return ($i >= 0);
	}

	/**
	* Deliver call for QuadernoDocument objects
	* Deliver object to the contact email
	* Returns true or false whether the request is accepted or not
	*/
	protected function execDeliver()
	{
		$return = false;
		$response = QuadernoBase::deliver(static::$model, $this->id);

		if (QuadernoBase::responseIsValid($response))
			$return = true;
		elseif (isset($response['data']['errors']))
			$this->errors = $response['data']['errors'];

		return $return;
	}

}
?>