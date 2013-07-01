<?php
namespace Test;

class Layout extends \FruitMachine\AbstractModule {

  public static function name() {
    return 'layout';
  }

  public function template(array $data) {
    return (isset($data[1]) ? $data[1] : '')
      . (isset($data[2]) ? $data[2] : '')
      . (isset($data[3]) ? $data[3] : '');
  }

}
