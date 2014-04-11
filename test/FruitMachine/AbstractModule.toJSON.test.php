<?php
namespace FruitMachine;

class AbstractModuleToJSONTest extends \PHPUnit_Framework_TestCase {

  private $_fm;

  public function setUp() {
    $this->_fm = Singleton::getInstance();
    $this->_fm->define('\Test\Layout');
    $this->_fm->define('\Test\Apple');
  }

  public function tearDown() {
    $this->_fm->reset();
  }

  public function test_toJSON_should_return_an_fmid() {
    $apple = $this->_fm->create('apple');
    $json = $apple->toJSON();
    $this->assertArrayHasKey('fmid', $json);
  }

  public function test_toJSON_should_return_fmid_on_children() {
    $apple = $this->_fm->create('apple');
    $layout = $this->_fm->create('layout');
    $layout->add($apple);
    $json = $layout->toJSON();
    $this->assertArrayHasKey('fmid', $json['children'][0]);
  }

}
