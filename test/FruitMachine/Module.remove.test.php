<?php
namespace Test;

class ModuleRemoveTest extends \PHPUnit_Framework_TestCase {

  public function test_should_remove_the_child_passed_from_the_parents_children_array() {
    $list = new Layout();
    $apple1 = new Apple();
    $apple2 = new Apple();

    $list
      ->add($apple1)
      ->add($apple2);

    $list->remove($apple1);

    $this->assertFalse(in_array($apple1, $list->children));
  }

  public function test_should_remove_itself_if_called_with_no_arguments() {
    $list = new Layout();
    $apple = new Apple(array("id" => "foo"));

    $list->add($apple);
    $apple->remove();

    $this->assertFalse(in_array($apple, $list->children));
  }

  public function test_should_remove_reference_back_to_parent_view() {
    $layout = new Layout();
    $apple = new Apple(array("slot" => 1));

    $layout->add($apple);

    $this->assertEquals($apple->parent, $layout);

    $layout->remove($apple);

    $this->assertFalse(isset($apple->parent));
  }

  public function test_should_remove_slot_reference() {
    $layout = new Layout();
    $apple = new Apple(array("slot" => 1));

    $layout->add($apple);

    $this->assertEquals($layout->slots[1], $apple);

    $layout->remove($apple);

    $this->assertFalse(isset($layout->slots[1]));
  }

  public function test_should_not_remove_itself_if_first_argument_is_null() {
    $layout = new Layout();
    $apple = new Apple(array("slot" => 1));

    $layout->add($apple);

    $this->assertEquals($apple, $layout->module('apple'));

    $apple->remove(null);

    $this->assertEquals($apple, $layout->module('apple'));
  }

}
