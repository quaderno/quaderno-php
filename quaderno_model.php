<?php
/**
* Quaderno Load
*
* Interface for every model
*
* @package   Quaderno PHP
* @author    Quaderno <support@quaderno.io>
* @copyright Copyright (c) 2021, Quaderno
* @license   https://opensource.org/licenses/MIT The MIT License
*/

abstract class QuadernoModel extends QuadernoClass {
	/**
	 *  Find for QuadernoModel objects
	 * If $params is a single value, it returns a single object
	 * If $params is null or an array, it returns an array of objects
	 * When request fails, it returns false
	 *
	 * @param string|array|null $params
	 *
	 * @return static|static[]|false
	 * @phpstan-return ($params is string ? static|false : static[]|false)
	 */
	public static function find($params = array('page' => 1))
	{
		$return = false;
		$class = get_called_class();

		if (!is_array($params))
		{
			// Searching for an ID
			$response = QuadernoBase::findByID(static::$model, $params);
			if (QuadernoBase::responseIsValid($response)) $return = new $class($response['data']);
		}
		else
		{
			$response = QuadernoBase::find(static::$model, $params);

			if (QuadernoBase::responseIsValid($response))
			{
				$return = array();
				$length = count($response['data']);
				for ($i = 0; $i < $length; $i++)
					$return[$i] = new $class($response['data'][$i]);
			}
		}

		return $return;
	}

	/**
	 * Save for QuadernoModel objects
	 * Export object data to the model
	 * Returns true or false whether the request is accepted or not
	 *
	 * @return bool
	 */
	public function save()
	{
		$response = null;
		$new_object = false;
		$return = false;

		/**
		* 1st step - New object to be created
		* Check if the current object has not been created yet
		*/
		if (is_null($this->id))
		{
			// Not yet created, let's do it
			$response = QuadernoBase::save(static::$model, $this->data, $this->id);
			$new_object = true;

			/* Update data with the response */
			if (QuadernoBase::responseIsValid($response))
			{
				$this->data = $response['data'];
				$return = true;
			}
			elseif (isset($response['data']['errors']))
				$this->errors = $response['data']['errors'];
		}

		$response = null;
		$new_data = false;

		/**
		* 2nd step - Payments to be created
		* Check if there are any payments stored and not yet created
		*/
		if (isset($this->payments_array) && count($this->payments_array))
		{
			foreach ($this->payments_array as $index => $p)
			{
				if (is_null($p->id))
				{
					// The payment does not have ID -> Not yet created
					$response = QuadernoBase::saveNested(static::$model, $this->id, 'payments', $p->data);
					if (QuadernoBase::responseIsValid($response))
					{
						$p->data = $response['data'];
						$new_data = self::find($this->id);
					}
					elseif (isset($response['data']['errors']))
						$this->errors = $response['data']['errors'];
				}

				if ($p->mark_to_delete)
				{
					// The payment is marked to delete -> Let's do it.
					$delete_response = QuadernoBase::deleteNested(static::$model, $this->id, 'payments', $p->id);
					if (QuadernoBase::responseIsValid($delete_response))
						array_splice($this->payments_array, $index, 1);
					elseif (isset($response['data']['errors']))
						$this->errors = $response['data']['errors'];
				}
			}
			/* If this object has received new data, let's update data field. */
			if ($new_data) $this->data = $new_data->data;
		}

		/**
		* 3rd step - Update object
		* Update object - This is only necessary when it's not a new object, or new payments have been created.
		*/
		if (!$new_object || $new_data)
		{
			$body_data = $this->data;

			if (isset($this->items)) unset($body_data['items']);

			$response = QuadernoBase::save(static::$model, $body_data, $this->id);

			if (QuadernoBase::responseIsValid($response))
			{
				$return = true;
				$this->data = $response['data'];
			}
			elseif (isset($response['data']['errors']))
				$this->errors = $response['data']['errors'];
		}

		return $return;
	}

	/**
	 * Delete for QuadernoModel objects
	 * Delete object from the model
	 * Returns true or false whether the request is accepted or not
	 *
	 * @return bool
	 */
	public function delete()
	{
		$return = false;
		$response = QuadernoBase::delete(static::$model, $this->id);

		if (QuadernoBase::responseIsValid($response))
		{
			$return = true;
			$this->data = array();
		}
		elseif (isset($response['data']['errors']))
			$this->errors = $response['data']['errors'];

		return $return;
	}

}
?>
