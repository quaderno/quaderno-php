<?php
/**
* Quaderno Estimate
*
* @package   Quaderno PHP
* @author    Quaderno <hello@quaderno.io>
* @copyright Copyright (c) 2015, Quaderno
* @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
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