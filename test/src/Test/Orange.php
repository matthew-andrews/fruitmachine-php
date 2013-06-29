<?php
namespace Test;

class Orange extends \FruitMachine\AbstractModule {

  public function template(array $data) {
    return 'I am Orange';
  }

}
