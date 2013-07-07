<?php
namespace Test;

class Orange extends \FruitMachine\AbstractModule {

  public static $name = 'orange';

  public function template(array $data) {
    return 'I am Orange';
  }

}
