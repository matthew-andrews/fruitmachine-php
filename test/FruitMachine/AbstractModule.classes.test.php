<?php
namespace FruitMachine;

class AbstractModuleClassesTest extends \PHPUnit_Framework_TestCase {

  public function setUp() {
    Singleton::getInstance()->define('\Test\Apple');
  }

  public function tearDown() {
    Singleton::getInstance()->reset();
  }

  public function test_should_be_able_to_define_classes_on_the_base_class() {
    $view = Singleton::getInstance()->create('apple');
    $this->assertContains('class="apple foo bar"', $view->toHTML());
  }

  public function test_should_be_able_to_manipulate_the_classes_array_at_any_time() {
    $apple = Singleton::getInstance()->create('apple');
    $apple->classes = array('a', 'b', 'c');
    $this->assertContains('class="apple a b c"', $apple->toHTML());
  }

  public function test_should_be_able_to_define_classes_at_instantiation() {
    $apple = Singleton::getInstance()->create('apple', array(
      'classes' => array('fizz', 'buzz')
    ));
    $this->assertContains('class="apple fizz buzz"', $apple->toHTML());
  }

}