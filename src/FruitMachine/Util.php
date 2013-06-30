<?php
namespace FruitMachine;

/**
 * Util
 *
 * TODO: Move this back into AbstractModule?
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
    return $prefix . ((++self::$i) * round(rand() / getrandmax() * 100000));
  }

}
