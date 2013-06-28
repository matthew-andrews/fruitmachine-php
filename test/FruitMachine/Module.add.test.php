<?php
namespace Test;

class ModuleAddTest extends \PHPUnit_Framework_TestCase {

  public function test_should_accept_module_instance() {
    $layout = new Layout();
    $apple = new Apple();
    $orange = new Orange();

    $apple->add($orange);
    $layout->add($apple);

    $this->assertEquals(1, count($layout->children), 'Layout has one child');
    $this->assertEquals(1, count($apple->children), 'Apple has one child');
    $this->assertEquals(0, count($orange->children), 'Orange has no children');
  }

  public function test_should_store_a_reference_to_the_child_via_slot_if_the_view_added_has_a_slot() {
    $apple = new Apple(array('slot' => 1));
    $layout = new Layout();

    $layout->add($apple);

    $this->assertEquals($layout->slots[1], $apple);
  }

  public function test_should_accept_json() {
    $layout = new Layout();
    $layout->add(array("module" => "orange"));
    $this->assertEquals(1, count($layout->children));
  }

  public function test_the_second_param_should_define_the_slot() {
    $apple = new Apple();
    $layout = new Layout();

    $layout->add($apple, 1);
    $this->assertEquals($layout->slots[1], $apple);
  }

  public function test_should_be_able_to_define_the_slot_in_the_options_object() {
    $apple = new Apple();
    $layout = new Layout();

    $layout->add($apple, array( "slot" => 1 ));
    $this->assertEquals($layout->slots[1], $apple);
  }

  public function test_should_remove_a_module_if_it_already_occupies_this_slot() {
    $apple = new Apple();
    $orange = new Orange();
    $layout = new Layout();

    $layout->add($apple, 1);

    $this->assertEquals($layout->slots[1], $apple);

    $layout->add($orange, 1);

    $this->assertEquals($layout->slots[1], $orange);

    $this->assertNull($layout->module('apple'));
  }

  public function test_should_remove_the_module_if_it_already_has_parent_before_being_added() {
    $apple = new Apple();
    $layout = new Layout();

    $layout->add($apple, 1);

    $this->assertEquals($layout->slots[1], $apple);

    $layout->add($apple, 2);

    $this->assertFalse(isset($layout->slots[1]));
    $this->assertEquals($apple, $layout->slots[2]);
  }

}
