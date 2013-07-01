<?php
namespace Test;

class Orange extends \FruitMachine\AbstractModule {

  public static function name() {
    return 'orange';
  }

  public function template(array $data) {
    return 'I am Orange';
  }

}
