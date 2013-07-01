<?php
namespace FruitMachine;

class AbstractModuleIdTest extends \PHPUnit_Framework_TestCase {

  public function setUp() {
    Singleton::getInstance()->define('\Test\Apple');
    Singleton::getInstance()->define('\Test\Layout');
  }

  public function tearDown() {
    Singleton::getInstance()->reset();
  }

  public function test_should_return_a_child_by_id() {
    $layout = Singleton::getInstance()->create('layout', array(
      "children" => array(
        1 => array(
          "module" => 'layout',
          "id" => 'some_id'
        )
      )
    ));
    $child = $layout->id('some_id');
    $this->assertInstanceOf('\Test\Layout', $child);
  }

  public function test_should_return_the_views_own_id_if_no_arguments_given_() {
    $id = 'a_view_id';
    $view = Singleton::getInstance()->create('apple', array("id" => $id));
    $this->assertEquals($id, $view->id());
  }

  public function test_should_not_return_the_views_own_id_the_first_argument_is_undefined() {
    $id = 'a_view_id';
    $view = Singleton::getInstance()->create('apple', array("id" => $id));
    $this->assertNull($view->id(null));
  }

  public function test_can_find_nested_children() {
    $layout = Singleton::getInstance()->create('layout', array(
      "children" => array(
        1 => array(
          "module" => 'layout',
          "id" => 'some_id',
          "children" => array(
            array(
              "module" => "apple",
              "id" => "deeply_nested"
            )
          )
        )
      )
    ));
    $child = $layout->id('deeply_nested');
    $this->assertInstanceOf('\Test\Apple', $child);
  }

}
