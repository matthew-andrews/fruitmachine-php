<?php
namespace FruitMachine;

class AbstractModuleModulesTest extends \PHPUnit_Framework_TestCase {

  private $_view;
  private $_fm;

  public function setUp() {
    $this->_fm = Singleton::getInstance();
    $this->_fm->define('\Test\Apple');
    $this->_fm->define('\Test\Orange');
    $this->_fm->define('\Test\Pear');
    $this->_fm->define('\Test\Layout');

    $fm = $this->_fm;
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
    $this->_fm->reset();
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