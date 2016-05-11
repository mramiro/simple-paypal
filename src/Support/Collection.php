<?php namespace SimplePaypal\Support;

use ArrayAccess;
use IteratorAggregate;
use ArrayIterator;
use Countable;

class Collection implements ArrayAccess, IteratorAggregate, Countable
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
    foreach ($items as $key => $value) {
      $this->set($key, $value);
    }
    return $this;
  }

  public function add($value)
  {
    $this->items[] = $value;
    return $this;
  }

  public function toArray()
  {
    $items = array();
    foreach ($this->items as $key => $item) {
      $items[$key] = $item instanceof self ? $item->toArray() : $item;
    }
    return $items;
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
    return array_key_exists($key, $this->items);
  }

  public function offsetGet($key)
  {
    return $this->get($key);
  }

  public function offsetSet($key, $value)
  {
    $this->set($key, $value);
  }

  public function offsetUnset($key)
  {
    unset($this->$items[$key]);
  }

  public function getIterator()
  {
    return new ArrayIterator($this->items);
  }

  public function count()
  {
    return count($this->items);
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
