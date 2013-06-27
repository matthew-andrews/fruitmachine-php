<?php

class SingletonTest extends \PHPUnit_Framework_TestCase {

  public function testSingletonIsInstanceOfFruitMachine() {
    $singleton = \FruitMachine\Singleton::getInstance();
    $this->assertTrue($singleton instanceof \FruitMachine\FruitMachine);
  }

}
