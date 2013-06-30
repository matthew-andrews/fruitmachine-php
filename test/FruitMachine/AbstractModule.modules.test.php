<?php
namespace FruitMachine;

class AbstractModuleModulesTest extends \PHPUnit_Framework_TestCase {

  private $_view;

  public function setUp() {
    Singleton::getInstance()->define('apple', '\Test\Apple');
    Singleton::getInstance()->define('orange', '\Test\Orange');
    Singleton::getInstance()->define('pear', '\Test\Pear');
    Singleton::getInstance()->define('layout', '\Test\Layout');

    $fm = Singleton::getInstance();
    $layout = $fm->create('layout');
    $apple = $fm->create('apple', array("id" => "slot_1"));
    $orange = $fm->create('orange', array("id" => "slot_2"));
    $pear = $fm->create('pear', array("id" => "slot_3"));

    $layout
      ->add($apple)
      ->add($orange)
      ->add($pear);

    $this->_view = $layout;
  }

  public function tearDown() {
    Singleton::getInstance()->reset();
  }

  public function test_should_return_all_descendant_views_matching_the_given_module_type() {
    $oranges = $this->_view->modules('orange');
    $pears = $this->_view->modules('pear');

    $this->assertCount(1, $oranges);
    $this->assertCount(1, $pears);
  }

  public function test_should_return_multiple_views_if_they_exist() {
    $this->_view->add(array("module" => "pear"));
    $this->assertCount(2, $this->_view->modules("pear"));
  }

}