<?php
namespace FruitMachine;

class SingletonTest extends \PHPUnit_Framework_TestCase {

  public function setUp() {

    // First do hard reset so that we actually test the instantiation
    // of the FruitMachine
    Singleton::reset();
  }

  /**
   * @covers \FruitMachine\Singleton
   * @covers \FruitMachine\FruitMachine::__construct
   */
  public function test_singleton_is_instance_of_FruitMachine() {
    $singleton = Singleton::getInstance();

    $this->assertTrue($singleton instanceof FruitMachine);

    $singletonAgain = Singleton::getInstance();
    $this->assertEquals($singleton, $singletonAgain);
  }

}
