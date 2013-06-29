<?php
namespace Test;

class Apple extends \FruitMachine\AbstractModule {

  public function template(array $data) {
    return 'I am Apple';
  }

}
