<?php
namespace FruitMachine;

class FruitMachineDefineTest extends \PHPUnit_Framework_TestCase {

  /**
   * @covers \FruitMachine\FruitMachine::define
   */
  public function test_define_allows_module_to_be_built_via_create() {
    Singleton::getInstance()->define('apple', '\Test\Apple');
    $apple = Singleton::getInstance()->create('apple');
    $this->assertInstanceOf('\Test\Apple', $apple);
  }

}
