<?php namespace SimplePaypal\Support;

use ArrayAccess;

class Collection implements ArrayAccess
{
  protected $items = array();

  public function __construct(array $items = array())
  {
    if ($items) {
      $this->fill($items);
    }
  }

  public function fill(array $items)
  {
    $this->items = $items;
    return $this;
  }

  public function add($key, $value)
  {
    $this->offsetSet($key, $value);
    return $this;
  }

  public function toArray()
  {
    return $this->items;
  }

  public function toJson($options = 0)
  {
    return json_encode($this->toArray(), $options);
  }

  public function get($key)
  {
    return array_key_exists($key, $this->items) ? $this->items[$key] : null;
  }

  public function set($key, $value)
  {
    $this->items[$key] = $value;
    return $this;
  }

  public function offsetExists($key)
  {
    return array_key_exists($this->items, $key);
  }

  public function offsetGet($key)
  {
    return $this->get($key);
  }

  public function offsetSet($key, $value)
  {
    $this->items[$key] = $value;
  }

  public function offsetUnset($key)
  {
    unset($this->$items[$key]);
  }

  public function __get($key)
  {
    return $this->get($key);
  }

  public function __set($key, $value)
  {
    $this->set($key, $value);
  }
}
