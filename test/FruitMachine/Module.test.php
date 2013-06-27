<?php
namespace Test;

class ModuleTest extends \PHPUnit_Framework_TestCase {

  public function testAdd() {
    $layout = new Layout();
    $apple = new Apple();
    $orange = new Orange();

    $apple->add($orange);
    $layout->add($apple);

    $this->assertEquals(1, count($layout->children));
    $this->assertEquals(1, count($apple->children));
    $this->assertEquals(0, count($orange->children));
  }

}
