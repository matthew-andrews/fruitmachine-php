<?php
namespace FruitMachine;

class AbstractModuleTest extends \PHPUnit_Framework_TestCase {

  private $_fm;

  public function setUp() {
    $this->_fm = Singleton::getInstance();
    $this->_fm->define('\Test\Apple');
    $this->_fm->define('\Test\Orange');
    $this->_fm->define('\Test\Pear');
    $this->_fm->define('\Test\Layout');
  }

  public function tearDown() {
    $this->_fm->reset();
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

    $view = $this->_fm->create('apple', array(
      "children" => $children
    ));

    $this->assertCount(2, $view->children);
  }

  public function test_should_store_a_reference_to_the_slot_if_passed() {
    $view = $this->_fm->create('apple', array(
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
    $view = $this->_fm->create('apple', array(
      "children" => array(
        1 => array("module" => "pear"),
        2 => array("module" => "orange")
      )
    ));

    $this->assertEquals("pear", $view->slots[1]->module());
    $this->assertEquals("orange", $view->slots[2]->module());

  }

  public function test_should_store_a_reference_to_the_slot_if_the_view_is_instantiated_with_a_slot() {
    $apple = $this->_fm->create('apple', array("slot" => 1));
    $this->assertEquals(1, $apple->slot);
  }

  public function test_should_prefer_the_slot_on_the_children_object_in_case_of_conflict() {
    $apple = $this->_fm->create('apple', array("slot" => 1));
    $layout = $this->_fm->create('layout', array(
      "children" => array(
        2 => $apple
      )
    ));

    $this->assertEquals($layout->module('apple')->slot, 2);
  }

  public function test_should_create_a_model() {
    $view = $this->_fm->create('apple');
    $this->assertInstanceOf('\MattAndrews\ModelInterface', $view->model);
  }

  public function test_should_adopt_the_fmid_if_passed() {
    $view = $this->_fm->create("apple", array("fmid" => "1234"));
    $this->assertContains('id="1234"', $view->toHTML());
  }

  public function test_each() {
    $fm = $this->_fm;
    $apple1 = $fm->create('apple');
    $apple2 = $fm->create('apple');
    $orange = $fm->create('orange');
    $view = $fm->create("layout", array(
      'children' => array(
        1 => $apple1,
        2 => $orange,
        3 => $apple2
      )
    ));

    // Find the first apple
    $search = $view->each(function($child) {
      if ($child->module() === 'apple') {
        return $child;
      }
    });

    $this->assertEquals($apple1, $search);
    $this->assertNotEquals($apple2, $search);

    // Find the orange
    $search = $view->each(function($child) {
      if ($child->module() === 'orange') {
        return $child;
      }
    });

    $this->assertEquals($orange, $search);
  }

}