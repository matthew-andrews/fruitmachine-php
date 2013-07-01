<?php
namespace FruitMachine;

class AbstractModuleToHTMLTest extends \PHPUnit_Framework_TestCase {

  private $_fm;

  public function setUp() {
    $this->_fm = Singleton::getInstance();
    $this->_fm->define('\Test\Layout');
    $this->_fm->define('\Test\Apple');
  }

  public function tearDown() {
    $this->_fm->reset();
  }

  /**
   * @covers \FruitMachine\AbstractModule::toHTML
   */
  public function test_should_return_a_string() {
    $layout = $this->_fm->create('layout');
    $html = $layout->toHTML();
    $this->assertTrue(is_string($html));
  }

  /**
   * @covers \FruitMachine\AbstractModule::toHTML
   */
  public function test_should_print_the_child_html_into_the_corresponding_slot() {
    $apple = $this->_fm->create('apple', array("slot" => 1));
    $layout = $this->_fm->create('layout', array(
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
    $apple = $this->_fm->create('apple', array("id" => 1));
    $layout = $this->_fm->create('layout', array(
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
    $layout = $this->_fm->create('layout', array(
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
