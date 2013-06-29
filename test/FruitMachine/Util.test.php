<?php
namespace FruitMachine;

class UtilTest extends \PHPUnit_Framework_TestCase {

  public function test_uniqueId_returns_string() {
    $first = Util::uniqueId();

    $this->assertTrue(is_string($first));
  }

  public function test_uniqueId_returns_differently() {
    $first = Util::uniqueId();
    $second = Util::uniqueId();

    $this->assertNotEquals($first, $second);
  }

}
