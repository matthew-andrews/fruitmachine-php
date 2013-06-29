<?php
namespace FruitMachine;

class ModuleAddTest extends \PHPUnit_Framework_TestCase {

  public function setUp() {
    Singleton::getInstance()->define('apple', '\Test\Apple');
    Singleton::getInstance()->define('layout', '\Test\Layout');
    Singleton::getInstance()->define('orange', '\Test\Orange');
  }

  public function tearDown() {
    Singleton::getInstance()->reset();
  }

  public function test_should_accept_module_instance() {
    $layout = Singleton::getInstance()->create('layout');
    $apple = Singleton::getInstance()->create('apple');
    $orange = Singleton::getInstance()->create('orange');

    $apple->add($orange);
    $layout->add($apple);

    $this->assertEquals(1, count($layout->children), 'Layout has one child');
    $this->assertEquals(1, count($apple->children), 'Apple has one child');
    $this->assertEquals(0, count($orange->children), 'Orange has no children');
  }

  public function test_should_store_a_reference_to_the_child_via_slot_if_the_view_added_has_a_slot() {
    $apple = Singleton::getInstance()->create('apple', array('slot' => 1));
    $layout = Singleton::getInstance()->create('layout');

    $layout->add($apple);

    $this->assertEquals($layout->slots[1], $apple);
  }

  public function test_should_accept_json() {
    $layout = Singleton::getInstance()->create('layout');
    $layout->add(array("module" => "orange"));
    $this->assertEquals(1, count($layout->children));
  }

  public function test_the_second_param_should_define_the_slot() {
    $apple = Singleton::getInstance()->create('apple');
    $layout = Singleton::getInstance()->create('layout');

    $layout->add($apple, 1);
    $this->assertEquals($layout->slots[1], $apple);
  }

  public function test_should_be_able_to_define_the_slot_in_the_options_object() {
    $apple = Singleton::getInstance()->create('apple');
    $layout = Singleton::getInstance()->create('layout');

    $layout->add($apple, array( "slot" => 1 ));
    $this->assertEquals($layout->slots[1], $apple);
  }

  public function test_should_remove_a_module_if_it_already_occupies_this_slot() {
    $apple = Singleton::getInstance()->create('apple');
    $orange = Singleton::getInstance()->create('orange');
    $layout = Singleton::getInstance()->create('layout');

    $layout->add($apple, 1);

    $this->assertEquals($layout->slots[1], $apple);

    $layout->add($orange, 1);

    $this->assertEquals($layout->slots[1], $orange);

    $this->assertNull($layout->module('apple'));
  }

  public function test_should_remove_the_module_if_it_already_has_parent_before_being_added() {
    $apple = Singleton::getInstance()->create('apple');
    $layout = Singleton::getInstance()->create('layout');

    $layout->add($apple, 1);

    $this->assertEquals($layout->slots[1], $apple);

    $layout->add($apple, 2);

    $this->assertFalse(isset($layout->slots[1]));
    $this->assertEquals($apple, $layout->slots[2]);
  }

}
