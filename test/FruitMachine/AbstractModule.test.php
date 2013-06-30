<?php
namespace FruitMachine;

class AbstractModuleTest extends \PHPUnit_Framework_TestCase {

  public function setUp() {
    Singleton::getInstance()->define('apple', '\Test\Apple');
    Singleton::getInstance()->define('orange', '\Test\Orange');
    Singleton::getInstance()->define('pear', '\Test\Pear');
    Singleton::getInstance()->define('layout', '\Test\Layout');
  }

  public function tearDown() {
    Singleton::getInstance()->reset();
  }

  public function test_should_add_any_children_passed_into_the_constructor() {
    $children = array(
      array(
        "module" => "pear"
      ),
      array(
        "module" => "orange"
      )
    );

    $view = Singleton::getInstance()->create('apple', array(
      "children" => $children
    ));

    $this->assertEquals(count($view->children), 2);
  }

  public function test_should_store_a_reference_to_the_slot_if_passed() {
    $view = Singleton::getInstance()->create('apple', array(
      "children" => array(
        array(
          "module" => "pear",
          "slot" => 1
        ),
        array(
          "module" => "orange",
          "slot" => 2
        )
      )
    ));

    $this->assertEquals("pear", $view->slots[1]->module());
    $this->assertEquals("orange", $view->slots[2]->module());
  }

  public function test_should_store_a_reference_to_the_slot_if_slot_is_passed_as_key_of_children_object() {
    $view = Singleton::getInstance()->create('apple', array(
      "children" => array(
        1 => array("module" => "pear"),
        2 => array("module" => "orange")
      )
    ));

    $this->assertEquals("pear", $view->slots[1]->module());
    $this->assertEquals("orange", $view->slots[2]->module());

  }

  public function test_should_store_a_reference_to_the_slot_if_the_view_is_instantiated_with_a_slot() {
    $apple = Singleton::getInstance()->create('apple', array("slot" => 1));
    $this->assertEquals($apple->slot, 1);
  }

  public function test_should_prefer_the_slot_on_the_children_object_in_case_of_conflict() {
    $apple = Singleton::getInstance()->create('apple', array("slot" => 1));
    $layout = Singleton::getInstance()->create('layout', array(
      "children" => array(
        2 => $apple
      )
    ));

    $this->assertEquals($layout->module('apple')->slot, 2);
  }

  public function test_should_create_a_model() {
    $view = Singleton::getInstance()->create('apple');
    $this->assertInstanceOf('\MattAndrews\ModelInterface', $view->model);
  }

  public function test_should_adopt_the_fmid_if_passed() {
    $view = Singleton::getInstance()->create("apple", array("fmid" => "1234"));
    $this->assertContains('id="1234"', $view->toHTML());
  }

}