<?php
namespace Test;

class Apple extends \FruitMachine\AbstractModule {

  public $classes = array('foo', 'bar');

  public function template(array $data) {
    return (isset($data[0]) ? $data[0] : '');
  }

}
