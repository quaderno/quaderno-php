<?php
/**
* Quaderno Class
*
* @package   Quaderno PHP
* @author    Quaderno <support@quaderno.io>
* @copyright Copyright (c) 2021, Quaderno
* @license   https://opensource.org/licenses/MIT The MIT License
*/

/* Interface that implements every single class */
#[AllowDynamicProperties]
abstract class QuadernoClass implements \JsonSerializable
{
	/**
	 * @var array
	 */
	protected $data = array();

	/**
	 * @param array $newdata
	 */
	public function __construct($newdata)
	{
		if (is_array($newdata)) $this->data = $newdata;
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 *
	 * @return void
	 */
	public function __set($name, $value)
	{
		$this->data[$name] = $value;
	}

	/**
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function __get($name)
	{
		return array_key_exists($name, $this->data) ? $this->data[$name] : null;
	}

	/**
	 * @param string $name
	 *
	 * @return bool
	 */
	public function __isset($name)
	{
		return array_key_exists($name, $this->data);
	}

	/**
	 * @return array
	 */
	protected function getArray()
	{
		return $this->data;
	}

	/**
	 * Specify data which should be serialized to JSON.
	 *
	 * @return array
	 */
	#[ReturnTypeWillChange]
	public function jsonSerialize()
	{
		return $this->data;
	}
}
?>
