<?php
namespace Test;

class Pear extends \FruitMachine\AbstractModule {

  public static $name = 'pear';

  public function template(array $data) {
    return 'I am Pear';
  }

}
