<?php
/**
* Quaderno Transaction
*
* @package   Quaderno PHP
* @author    Quaderno <support@quaderno.io>
* @copyright Copyright (c) 2021, Quaderno
* @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
*/

class QuadernoTransaction extends QuadernoDocument
{
  static protected $model = 'transactions';

  public function deliver() {
    $document = unserialize(sprintf(
      'O:%d:"%s"%s',
      strlen('Quaderno'. $this->type),
      'Quaderno' . $this->type,
      strstr(strstr(serialize($this), '"'), ':')
      ));

    return $document->execDeliver();
  }
}
?>