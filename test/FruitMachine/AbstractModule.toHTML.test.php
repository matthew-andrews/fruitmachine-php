<?php
namespace FruitMachine;

class AbstractModuleToHTMLTest extends \PHPUnit_Framework_TestCase {

  public function setUp() {
    Singleton::getInstance()->define('\Test\Layout');
    Singleton::getInstance()->define('\Test\Apple');
  }

  public function tearDown() {
    Singleton::getInstance()->reset();
  }

  /**
   * @covers \FruitMachine\AbstractModule::toHTML
   */
  public function test_should_return_a_string() {
    $layout = Singleton::getInstance()->create('layout');
    $html = $layout->toHTML();
    $this->assertTrue(is_string($html));
  }

  /**
   * @covers \FruitMachine\AbstractModule::toHTML
   */
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

  /**
   * @covers \FruitMachine\AbstractModule::toHTML
   */
  public function test_should_print_the_child_html_by_id_if_no_slot_is_found() {
    $apple = Singleton::getInstance()->create('apple', array("id" => 1));
    $layout = Singleton::getInstance()->create('layout', array(
      "children" => array($apple)
    ));

    $appleHtml = $apple->toHTML();
    $layoutHtml = $layout->toHTML();

    $this->assertContains($appleHtml, $layoutHtml);
  }

  /**
   * @covers \FruitMachine\AbstractModule::toHTML
   */
  public function test_should_fallback_to_printing_children_by_id_if_no_slot_is_present() {
    $layout = Singleton::getInstance()->create('layout', array(
      "children" => array(
        array(
          "module" => 'apple',
          "id" => 1
        )
      )
    ));

    $layoutHtml = $layout->toHTML();

    $this->assertContains('apple', $layoutHtml);
  }

}
