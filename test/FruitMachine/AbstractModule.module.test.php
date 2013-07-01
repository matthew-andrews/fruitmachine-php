<?php
namespace FruitMachine;

class AbstractModuleModuleTest extends \PHPUnit_Framework_TestCase {

  private $_view;
  private $_fm;

  public function setUp() {
    $this->_fm = $this->_fm = Singleton::getInstance();
    $this->_fm->define('\Test\Apple');
    $this->_fm->define('\Test\Orange');
    $this->_fm->define('\Test\Pear');
    $this->_fm->define('\Test\Layout');

    $layout = $this->_fm->create('layout', array());
    $apple = $this->_fm->create('apple', array("slot" => 1));
    $orange = $this->_fm->create('orange', array("slot" => 2));
    $pear = $this->_fm->create('pear', array("slot" => 3));

    $layout
      ->add($apple)
      ->add($orange)
      ->add($pear);

    $this->_view = $layout;
  }

  public function test_should_return_module_type_if_no_arguments_given() {
    $this->assertEquals($this->_view->module(), 'layout');
  }

  public function test_should_return_the_first_child_module_with_the_specified_type() {
    $child = $this->_view->module('pear');
    $this->assertEquals($child, $this->_view->children[2]);
  }

  public function test_if_there_is_more_than_one_child_of_this_module_type_only_the_first_is_returned() {
    $this->_view->add(array("module" => 'apple'));

    $child = $this->_view->module('apple');
    $firstChild = $this->_view->children[0];
    $lastChild = $this->_view->children[count($this->_view->children)-1];

    $this->assertEquals($child, $firstChild);
    $this->assertNotEquals($child, $lastChild);
  }

  public function test_can_find_nested_children() {
    $layout = $this->_fm->create('layout', array(
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
    $child = $layout->module('apple');
    $this->assertEquals('deeply_nested', $child->id());
  }

  public function tearDown() {
    $this->_fm->reset();
  }

}