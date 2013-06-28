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

}
