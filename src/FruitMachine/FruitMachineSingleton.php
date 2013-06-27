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

  public static function getInstance() {
    if (self::$_instance === null ) {

      // A little wasteful to create a model just
      // to throw it away but we want to make
      // sure any model used implements the
      // proper interface.  Perhaps in future we
      // could use a ModelFactory instead.
      $model = new \MattAndrews\Model();
      self::$_instance = new FruitMachine($model);
    }
    return self::$_instance;
  }

}
