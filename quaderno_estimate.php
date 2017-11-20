<?php
/**
* Quaderno Estimate
*
* @package   Quaderno PHP
* @author    Quaderno <hello@quaderno.io>
* @copyright Copyright (c) 2017, Quaderno
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