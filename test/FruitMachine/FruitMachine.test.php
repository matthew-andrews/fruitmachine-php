<?php
namespace FruitMachine;

class FruitMachineTest extends \PHPUnit_Framework_TestCase {

  private $_fm;

  public function setUp() {
    $this->_fm = Singleton::getInstance();
  }

  public function tearDown() {
    $this->_fm->reset();
  }

  /**
   * @covers \FruitMachine\FruitMachine::define
   * @covers \FruitMachine\FruitMachine::create
   * @covers \FruitMachine\AbstractModule::__construct
   */
  public function test_can_define_fallback_class() {
	$this->_fm->define('\Test\Pear', FruitMachine::DEFAULT_MODULE);
	$apple = $this->_fm->create('apple');
	$this->assertInstanceOf('\Test\Pear', $apple);
  }

  /**
   * @covers \FruitMachine\FruitMachine::define
   * @covers \FruitMachine\FruitMachine::create
   * @covers \FruitMachine\AbstractModule::__construct
   * @covers \FruitMachine\AbstractModule::name
   */
  public function test_define_allows_module_to_be_built_via_create() {
    $this->_fm->define('\Test\Apple');
    $apple = $this->_fm->create('apple');
    $this->assertInstanceOf('\Test\Apple', $apple);
  }

  /**
   * @covers \FruitMachine\FruitMachine::define
   * @covers \FruitMachine\FruitMachine::create
   * @covers \FruitMachine\AbstractModule::__construct
   */
  public function test_define_allows_accepts_array() {
    $this->_fm->define(array('\Test\Apple', '\Test\Orange'));
    $apple = $this->_fm->create('apple');
    $orange = $this->_fm->create('orange');
    $this->assertInstanceOf('\Test\Apple', $apple);
    $this->assertInstanceOf('\Test\Orange', $orange);
  }

  /**
   * @covers \FruitMachine\FruitMachine::create
   * @covers \FruitMachine\AbstractModule::__construct
   */
  public function test_creating_an_undefined_module_throws_error() {
    $exceptionCaught = false;
    try {
      $apple = $this->_fm->create('apple');
    } catch (Exception\ModuleNotDefined $exception) {
      $exceptionCaught = true;
    }
    $this->assertTrue($exceptionCaught);
  }

  public function test_defining_an_non_existent_class_throws_error() {
    $exceptionCaught = false;
    try {
      $apple = $this->_fm->define('\Test\Silly');
    } catch (Exception\ModuleNotDefined $exception) {
      $exceptionCaught = true;
    }
    $this->assertTrue($exceptionCaught);
  }

  public function test_create_should_be_able_to_understand_json_encodable_array() {
    $fm = $this->_fm;
    $fm->define('\Test\Apple');
    $fm->define('\Test\Orange');
    $fm->define('\Test\Layout');

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
    $this->assertEquals("layout", $layout->name());
    $slot1 = $layout->slots[1];
    $slot2 = $layout->slots[2];
    $this->assertEquals("apple", $slot1->name());
    $this->assertEquals("orange", $slot2->name());
  }

  public function test_can_build_your_own_fruitmachines() {
    $myFM = new \Test\MyFruitMachine('\Test\MyModel');
    $myFM->define('\Test\Apple');
    $apple = $myFM->create('apple');
    $this->assertInstanceOf('\Test\Apple', $apple);
  }

  public function test_exception_throw_if_build_your_own_fruitmachine_with_bad_model() {
    $thrown = false;
    try {
      $myFM = new \Test\MyFruitMachine('\Test\MyBadModel');
    } catch(Exception\ModelNotFound $exception) {
      $thrown = true;
    }
    $this->assertTrue($thrown);
  }

  public function test_model_always_instantiated() {
    $myFM = new \Test\MyFruitMachine('\Test\MyModel');
    $myFM->define('\Test\Apple');
    $apple = $myFM->create('apple', array(
      'model' => array(
        'collection' => array(1, 2, 3))
      )
    );
    $this->assertInstanceOf('\MattAndrews\Model', $apple->model);
  }

  public function test_model_already_instantiated() {
    $this->_fm->define('\Test\Apple');
    $model = new \MattAndrews\Model(array(
        'collection' => array(1, 2, 3)
      ));
    $apple = $this->_fm->create('apple', array(
      'model' => $model
    ));
    $this->assertInstanceOf('\MattAndrews\Model', $apple->model);
  }

  public function test_should_be_able_two_define_same_module_twice() {

    // Default
    $this->_fm->define('\Test\Apple');

    // Simple
    $this->_fm->define('\Test\Apple', 'crabapple');

    // Array
    $this->_fm->define(array(
        'toffee-apple' => '\Test\Apple',
        'apple-pie' => '\Test\Apple'
      ));

    $apple = $this->_fm->create('crabapple');
    $this->assertEquals('crabapple', $apple->name());

    $apple = $this->_fm->create('toffee-apple');
    $this->assertEquals('toffee-apple', $apple->name());

    $apple = $this->_fm->create('apple-pie');
    $this->assertEquals('apple-pie', $apple->name());
  }

}
