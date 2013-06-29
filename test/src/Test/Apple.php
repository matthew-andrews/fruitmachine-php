<?php
namespace Test;

class Apple extends \FruitMachine\AbstractModule {

  public function template(array $data) {
    return (isset($data[0]) ? $data[0] : '');
  }

}
