<?php
/**
* Quaderno Class
*
* @package   Quaderno PHP
* @author    Quaderno <hello@quaderno.io>
* @copyright Copyright (c) 2017, Quaderno
* @license   https://opensource.org/licenses/MIT The MIT License
*/

/* Interface that implements every single class */
abstract class QuadernoClass implements \JsonSerializable
{
	protected $data = array();

	public function __construct($newdata)
	{
		if (is_array($newdata)) $this->data = $newdata;
	}

	public function __set($name, $value)
	{
		$this->data[$name] = $value;
	}

	public function __get($name)
	{
		return array_key_exists($name, $this->data) ? $this->data[$name] : null;
	}

  public function __isset($name)
  {
    return array_key_exists($name, $this->data);
  }

	protected function getArray()
	{
		return $this->data;
	}

	/**
     * Specify data which should be serialized to JSON.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->data;
    }
}
?>