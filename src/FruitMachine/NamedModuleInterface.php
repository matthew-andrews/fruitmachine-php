<?php
namespace FruitMachine;

/**
 * Modules that have a name
 *
 * @author Matt Andrews <matt@mattandre.ws>
 * @copyright The Financial Times Limited [All Rights Reserved]
 */

interface NamedModuleInterface {

  /**
   * Getter for the module's name
   *
   * @return string A friendly name of the module
   */
  public static function name();

}