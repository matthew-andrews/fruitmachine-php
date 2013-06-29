<?php
namespace Test;

class ModuleToHTMLTest extends \PHPUnit_Framework_TestCase {

  public function setUp() {
    \FruitMachine\Singleton::getInstance()->define('layout', '\Test\Layout');
  }

  public function tearDown() {
    \FruitMachine\Singleton::getInstance()->reset();
  }

  public function test_should_return_a_string() {
    $layout = \FruitMachine\Singleton::getInstance()->create('layout');
    $html = $layout->toHTML();
    $this->assertTrue(is_string($html));
  }

}
