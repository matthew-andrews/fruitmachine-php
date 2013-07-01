<?php
namespace FruitMachine;

class AbstractModuleClassesTest extends \PHPUnit_Framework_TestCase {

  private $_fm;

  public function setUp() {
    $this->_fm = Singleton::getInstance();
    $this->_fm->define('\Test\Apple');
  }

  public function tearDown() {
    $this->_fm->reset();
  }

  public function test_should_be_able_to_define_classes_on_the_base_class() {
    $view = $this->_fm->create('apple');
    $this->assertContains('class="apple foo bar"', $view->toHTML());
  }

  public function test_should_be_able_to_manipulate_the_classes_array_at_any_time() {
    $apple = $this->_fm->create('apple');
    $apple->classes = array('a', 'b', 'c');
    $this->assertContains('class="apple a b c"', $apple->toHTML());
  }

  public function test_should_be_able_to_define_classes_at_instantiation() {
    $apple = $this->_fm->create('apple', array(
      'classes' => array('fizz', 'buzz')
    ));
    $this->assertContains('class="apple fizz buzz"', $apple->toHTML());
  }

}