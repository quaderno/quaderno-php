<?php
/**
* Quaderno Estimate
*
* @package   Quaderno PHP
* @author    Quaderno <support@quaderno.io>
* @copyright Copyright (c) 2021, Quaderno
* @license   https://opensource.org/licenses/MIT The MIT License
*/

class QuadernoEstimate extends QuadernoDocument
{
	static protected $model = 'estimates';

	public function deliver()
	{
		return $this->execDeliver();
	}
}
?>