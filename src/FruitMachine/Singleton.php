<?php
namespace FruitMachine;

/**
 * Singleton
 *
 * Like with it's javascript equivalent, the
 * PHP version of FruitMachine provides a
 * convenience singleton that uses a PHP
 * port of @WilsonPage's model class
 *
 * @author Matt Andrews <matt@mattandre.ws>
 * @copyright The Financial Times Limited [All Rights Reserved]
 */

class Singleton {

  private static $_instance;

  private function __construct() {

  }

  public static function getInstance() {
    if (!self::$_instance) {
      self::$_instance = new FruitMachine('\MattAndrews\Model');
    }
    return self::$_instance;
  }

}
