<?php
namespace Test;

class Pear extends \FruitMachine\AbstractModule {

  public static function name() {
    return 'pear';
  }

  public function template(array $data) {
    return 'I am Pear';
  }

}
