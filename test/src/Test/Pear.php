<?php
namespace Test;

class Pear extends \FruitMachine\AbstractModule {

  public static $name = 'orange';

  public function template(array $data) {
    return 'I am Pear';
  }

}
