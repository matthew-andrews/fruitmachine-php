<?php
namespace FruitMachine;

/**
 * Util
 *
 * @author Matt Andrews <matt@mattandre.ws>
 * @copyright The Financial Times Limited [All Rights Reserved]
 */

class Util {
  static $i = 0;

  public static function uniqueId($prefix = null) {
    $prefix = is_null($prefix)
      ? $prefix
      : 'id';
    return $prefix . ((++static::$i) * round(rand() / getrandmax() * 100000));
  }

}
