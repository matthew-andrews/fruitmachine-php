<?php
namespace Test;

class Apple extends \FruitMachine\AbstractModule {

  public $classes = array('foo', 'bar');

  public static function name() {
    return 'apple';
  }

  public function template(array $data) {
    return (isset($data[0]) ? $data[0] : '');
  }

}
