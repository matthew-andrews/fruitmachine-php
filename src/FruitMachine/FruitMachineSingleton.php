<?php
namespace FruitMachine;

/**
 * FruitMachineSingleton
 *
 * Like with it's javascript equivalent, the
 * PHP version of FruitMachine provides a
 * convenience singleton that uses a PHP
 * port of @WilsonPage's model class
 *
 * @author Matt Andrews <matt@mattandre.ws>
 * @copyright The Financial Times Limited [All Rights Reserved]
 */

class FruitMachineSingleton {

  private static $_instance;

  private function __construct() {

  }

  public function getInstance() {
    if (self::$_instance === null ) {
      self::$_instance = new FruitMachine('\MattAndrews\Model');
    }
    return self::$_instance;
  }

}
