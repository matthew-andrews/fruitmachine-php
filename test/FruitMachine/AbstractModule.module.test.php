<?php
namespace FruitMachine;

class AbstractModuleModuleTest extends \PHPUnit_Framework_TestCase {

  private $_view;

  public function setUp() {
    Singleton::getInstance()->define('apple', '\Test\Apple');
    Singleton::getInstance()->define('orange', '\Test\Orange');
    Singleton::getInstance()->define('pear', '\Test\Pear');
    Singleton::getInstance()->define('layout', '\Test\Layout');

    $layout = Singleton::getInstance()->create('layout', array());
    $apple = Singleton::getInstance()->create('apple', array("slot" => 1));
    $orange = Singleton::getInstance()->create('orange', array("slot" => 2));
    $pear = Singleton::getInstance()->create('pear', array("slot" => 3));

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

  public function tearDown() {
    Singleton::getInstance()->reset();
  }

}