<?php
namespace FruitMachine;

class FruitMachineTest extends \PHPUnit_Framework_TestCase {

  public function tearDown() {
    Singleton::getInstance()->reset();
  }

  /**
   * @covers \FruitMachine\FruitMachine::define
   * @covers \FruitMachine\FruitMachine::create
   * @covers \FruitMachine\AbstractModule::__construct
   */
  public function test_define_allows_module_to_be_built_via_create() {
    Singleton::getInstance()->define('apple', '\Test\Apple');
    $apple = Singleton::getInstance()->create('apple');
    $this->assertInstanceOf('\Test\Apple', $apple);
  }

  /**
   * @covers \FruitMachine\FruitMachine::create
   * @covers \FruitMachine\FruitMachine::_create
   * @covers \FruitMachine\AbstractModule::__construct
   */
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

  public function test_create_should_be_able_to_understand_json_encodable_array() {
    $fm = Singleton::getInstance();
    $fm->define('apple', '\Test\Apple');
    $fm->define('orange', '\Test\Orange');
    $fm->define('layout', '\Test\Layout');

    $json = '{
      "module": "layout",
      "children": {
        "1": {
          "module": "apple"
        },
        "2": {
          "module": "orange"
        }
      }
    }';

    $layout = $fm->create(json_decode($json, true));
    $this->assertEquals("layout", $layout->module());
    $this->assertEquals("apple", $layout->slots[1]->module());
    $this->assertEquals("orange", $layout->slots[2]->module());
  }

}
