<?php
namespace FruitMachine;

class AbstractModuleClassesTest extends \PHPUnit_Framework_TestCase {

  private $_fm;

  public function setUp() {
    $this->_fm = Singleton::getInstance();
    $this->_fm->define('\Test\Apple');
    $this->_fm->define('\Test\Pineapple');
  }

  public function tearDown() {
    $this->_fm->reset();
  }

  public function assertClasses(array $classes, $html) {
    array_walk($classes, function($v) use ($html) {
      $this->assertRegExp('/class="[^"]*' . preg_quote($v) . '[^"]*"/', $html);
    });
  }

  public function test_should_be_able_to_define_classes_on_the_base_class() {
    $view = $this->_fm->create('apple');
    $this->assertClasses(array('apple', 'foo', 'bar'), $view->toHTML());
 }

  public function test_should_be_able_to_manipulate_the_classes_array_at_any_time() {
    $apple = $this->_fm->create('apple');
    $apple->classes = array('a', 'b', 'c');
    $this->assertClasses(array('apple', 'a', 'b', 'c'), $apple->toHTML());
 }

  public function test_should_be_able_to_define_classes_at_instantiation() {
    $apple = $this->_fm->create('apple', array(
      'classes' => array('fizz', 'buzz')
    ));
    $this->assertClasses(array('apple', 'fizz', 'buzz'), $apple->toHTML());
  }

  public function test_should_contain_module_name_even_when_overridden() {
    $pineapple = $this->_fm->create('pineapple');
    $this->assertClasses(array('pineapple'), $pineapple->toHTML());
  }

  public function test_should_be_able_to_manipulate_the_classes_array_even_when_overridden() {
    $pineapple = $this->_fm->create('pineapple');
    $pineapple->classes = array('a', 'b', 'c');
    $this->assertClasses(array('pineapple', 'a', 'b', 'c'), $pineapple->toHTML());
  }

  public function test_should_be_able_to_add_dynamic_classes() {
    $pineapple = $this->_fm->create('pineapple');
    $this->assertClasses(array('pineapple-1'), $pineapple->toHTML());
    $this->assertClasses(array('pineapple-2'), $pineapple->toHTML());
  }

  public function test_should_be_able_to_add_dynamic_attributes() {
    $pineapple = $this->_fm->create('pineapple');
    $this->assertContains('data-counter="1"', $pineapple->toHTML());
    $this->assertContains('data-counter="2"', $pineapple->toHTML());
  }

}