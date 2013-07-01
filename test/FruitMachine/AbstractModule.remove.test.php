<?php
namespace FruitMachine;

class AbstractModuleRemoveTest extends \PHPUnit_Framework_TestCase {

  private $_fm;

  public function setUp() {
    $this->_fm = Singleton::getInstance();
    $this->_fm->define('\Test\Apple');
    $this->_fm->define('\Test\Layout');
    $this->_fm->define('\Test\Orange');
  }

  public function tearDown() {
    $this->_fm->reset();
  }

  /**
   * @covers \FruitMachine\AbstractModule::remove
   */
  public function test_should_remove_the_child_passed_from_the_parents_children_array() {
    $list = $this->_fm->create('layout');
    $apple1 = $this->_fm->create('apple');
    $apple2 = $this->_fm->create('apple');

    $list
      ->add($apple1)
      ->add($apple2);

    $list->remove($apple1);

    $this->assertFalse(in_array($apple1, $list->children));
  }

  /**
   * @covers \FruitMachine\AbstractModule::remove
   */
  public function test_should_remove_itself_if_called_with_no_arguments() {
    $list = $this->_fm->create('layout');
    $apple = $this->_fm->create('apple', array("id" => "foo"));

    $list->add($apple);
    $apple->remove();

    $this->assertFalse(in_array($apple, $list->children));
  }

  /**
   * @covers \FruitMachine\AbstractModule::remove
   */
  public function test_should_remove_reference_back_to_parent_view() {
    $layout = $this->_fm->create('layout');
    $apple = $this->_fm->create('apple', array("slot" => 1));

    $layout->add($apple);

    $this->assertEquals($apple->parent, $layout);

    $layout->remove($apple);

    $this->assertFalse(isset($apple->parent));
  }

  /**
   * @covers \FruitMachine\AbstractModule::remove
   */
  public function test_should_remove_slot_reference() {
    $layout = $this->_fm->create('layout');
    $apple = $this->_fm->create('apple', array("slot" => 1));

    $layout->add($apple);

    $this->assertEquals($layout->slots[1], $apple);

    $layout->remove($apple);

    $this->assertFalse(isset($layout->slots[1]));
  }

  /**
   * @covers \FruitMachine\AbstractModule::remove
   */
  public function test_should_not_remove_itself_if_first_argument_is_null() {
    $layout = $this->_fm->create('layout');
    $apple = $this->_fm->create('apple', array("slot" => 1));

    $layout->add($apple);

    $this->assertEquals($apple, $layout->module('apple'));

    $apple->remove(null);

    $this->assertEquals($apple, $layout->module('apple'));
  }

}
