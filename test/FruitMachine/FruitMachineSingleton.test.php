<?php

class ModelTest extends \PHPUnit_Framework_TestCase {

  public function testSingletonIsInstanceOfFruitMachine() {
    $fruitMachineSingleton = \FruitMachine\FruitMachineSingleton::getInstance();
    $this->assertTrue($fruitMachineSingleton instanceof \FruitMachine\FruitMachine);
  }

}
