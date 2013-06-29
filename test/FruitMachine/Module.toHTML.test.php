<?php
namespace FruitMachine;

class ModuleToHTMLTest extends \PHPUnit_Framework_TestCase {

  public function setUp() {
    Singleton::getInstance()->define('layout', '\Test\Layout');
    Singleton::getInstance()->define('apple', '\Test\Apple');
  }

  public function tearDown() {
    Singleton::getInstance()->reset();
  }

  public function test_should_return_a_string() {
    $layout = Singleton::getInstance()->create('layout');
    $html = $layout->toHTML();
    $this->assertTrue(is_string($html));
  }

  public function test_should_print_the_child_html_into_the_corresponding_slot() {
    $apple = Singleton::getInstance()->create('apple', array("slot" => 1));
    $layout = Singleton::getInstance()->create('layout', array(
      "children" => array(
        $apple
      )
    ));

    $this->assertEquals(1, $apple->slot);
    $this->assertEquals($apple, $layout->module('apple'));
    $this->assertEquals($apple, $layout->slots[1]);

    $appleHtml = $apple->toHTML();
    $layoutHtml = $layout->toHTML();

    $this->assertContains($appleHtml, $layoutHtml);
  }

}
