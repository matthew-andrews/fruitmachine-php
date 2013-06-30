<?php
namespace FruitMachine;

class FruitMachineTest extends \PHPUnit_Framework_TestCase {

  public function tearDown() {
    Singleton::getInstance()->reset();
  }

  /**
   * @covers \FruitMachine\FruitMachine::define
   */
  public function test_define_allows_module_to_be_built_via_create() {
    Singleton::getInstance()->define('apple', '\Test\Apple');
    $apple = Singleton::getInstance()->create('apple');
    $this->assertInstanceOf('\Test\Apple', $apple);
  }

  public function test_creating_an_undefined_module_throws_error() {
    $exceptionCaught = false;
    try {
      $apple = Singleton::getInstance()->create('apple');
    } catch (ModuleNotDefinedException $exception) {
      $exceptionCaught = true;
    }
    $this->assertTrue($exceptionCaught);
  }

  public function test_defining_an_non_existent_class_throws_error() {
    $exceptionCaught = false;
    try {
      $apple = Singleton::getInstance()->define('silly', '\Test\Silly');
    } catch (ModuleNotDefinedException $exception) {
      $exceptionCaught = true;
    }
    $this->assertTrue($exceptionCaught);
  }

}
