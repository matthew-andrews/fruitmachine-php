<?php

class SingletonTest extends \PHPUnit_Framework_TestCase {

  public function test_singleton_is_instance_of_FruitMachine() {
    $singleton = \FruitMachine\Singleton::getInstance();
    $this->assertTrue($singleton instanceof \FruitMachine\FruitMachine);
  }

}
