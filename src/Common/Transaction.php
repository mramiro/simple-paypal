<?php namespace SimplePaypal\Common;

use SimplePaypal\Support\Collection;

class Transaction extends Collection
{
  protected $valid = false;
  protected $validator;

  public function __construct($id)
  {
    $items = is_array($id) ? $id: array('txn_id' => $id);
    parent::__construct($items);
  }

  public function getItemsData()
  {
    $items = array();
    foreach ($this->items as $key => $value) {
      if (preg_match('/^([a-z_]+)([0-9]+)$/', $key, $m)) {
        $k = preg_replace('/_$/', '', $m[1]);
        $items[$m[2]][$k] = $value;
      }
    }
    return $items;
  }

  public function isValid()
  {
    return $this->valid;
  }

  public function validate(TransactionValidator $validator)
  {
    $this->valid = $validator->validate($this);
    return $this;
  }

}
