<?php
/**
* Quaderno Document
*
* @package   Quaderno PHP
* @author    Quaderno <support@quaderno.io>
* @copyright Copyright (c) 2021, Quaderno
* @license   https://opensource.org/licenses/MIT The MIT License
*/

/* Interface that implements every document: invoices, expenses, credit notes, and estimates */
abstract class QuadernoDocument extends QuadernoModel
{
	protected $payments_array = array();

	/**
	 * @param array $newdata
	 */
	public function __construct($newdata)
	{
		parent::__construct($newdata);
		if (isset($this->data['payments']))
		{
			foreach ($this->data['payments'] as $p)
				array_push($this->payments_array, new QuadernoPayment($p));
		}
	}

	/**
	 * @param QuadernoContact $contact
	 *
	 * @return bool
	 */
	public function addContact($contact)
	{
		$this->data['contact_id'] = $contact->id;
		$this->data['contact_name'] = $contact->full_name;
		return isset($this->data['contact_id']) && isset($this->data['contact_name']);
	}

	/**
	 * @param QuadernoDocumentItem $item
	 *
	 * @return bool
	 */
	public function addItem($item)
	{
		$length = isset($this->data['items_attributes']) ? count($this->data['items_attributes']) : 0;

		if (isset($item->id) && $length > 0)
		{
			$index = 0;

			for($index; $index < $length; $index ++){
				if ($this->data['items_attributes'][$index]['id'] == $item->id) break;
			}

			$this->data['items_attributes'][$index] = $item->getArray();
			return count($this->data['items_attributes']) == $length;
		}
		else
		{
			$this->data['items_attributes'][$length] = $item->getArray();
			return count($this->data['items_attributes']) == $length + 1;
		}
	}

	/**
	 * @param array|QuadernoDocumentItem $item
	 *
	 * @return bool
	 */
	public function updateItem($item)
	{
		if (is_array($item)) {
			$item = new QuadernoDocumentItem($item);
		}

		return $this->addItem($item);
	}

	/**
	 * Interface - only subclasses which implement original ones (i.e. without exec-) can call these methods
	 *
	 * @param QuadernoPayment $payment
	 *
	 * @return bool
	 */
	protected function execAddPayment($payment)
	{
		$length = count($this->payments_array);
		return array_push($this->payments_array, $payment) == $length + 1;
	}

	/**
	 * @return QuadernoPayment[]
	 */
	protected function execGetPayments()
	{
		return $this->payments_array;
	}

	/**
	 * @param QuadernoPayment $payment
	 *
	 * @return bool
	 */
	protected function execRemovePayment($payment)
	{
		$i = array_search($payment, $this->payments_array, true);
		if ($i >= 0) $this->payments_array[$i]->mark_to_delete = true;
		return ($i >= 0);
	}

	/**
	 * Deliver call for QuadernoDocument objects
	 * Deliver object to the contact email
	 * Returns true or false whether the request is accepted or not
	 *
	 * @return bool
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
