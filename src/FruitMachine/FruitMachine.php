<?php
namespace FruitMachine;

/**
 * Fruit Machine
 *
 * Extend this, use it directly or use the Singleton
 *
 * @author Matt Andrews <matt@mattandre.ws>
 * @copyright The Financial Times Limited [All Rights Reserved]
 */

class FruitMachine {

  public $config = array(
    'templateIterator' => 'children',
    'templateInstance' => 'child'
  );

  private $_model;
  private $_modules;

  /**
   * Creates a fruitmachine
   *
   * @param ModelInterface $model A php object that implements the model interface
   */
  final public function __construct($model) {
    $this->reset();

    // If it isn't already loaded trigger the autoloading of model class
    if (!class_exists($model)) {
      spl_autoload_call($model);
    }

    // ... and throw an exception if it wasn't found
    if (!class_exists($model)) {
      throw new Exception\ModelNotFound("Class '$model' passed into FruitMachine cannot be found");
    }

    // But store the classname internally if it was.
    $this->_model = $model;
  }

  /**
   * Defines a module
   *
   * @param  string $class The name of the PHP classname that the fruit corresponds to
   * @param  string $name  The internal name of the module
   * @throws Exception\ModuleNotDefined If the class described in $class does not exist
   * @return void
   */
  private function _define($class, $name) {

    // Manually trigger the autoloading of the specified class...
    if (!class_exists($class)) {
      spl_autoload_call($class);
    }

    // ... and throw an exception if it wasn't found
    if (!class_exists($class)) {
      throw new Exception\ModuleNotDefined("Class '$class' passed into FruitMachine#define cannot be found");
    }

    // Fallback to default name if $name is null
    $name = $name === null
      ? $class::$name
      : $name;

    $this->_modules[$name] = $class;
  }

  /**
   * Defines a module
   *
   * @param string|array $class A string (or array of strings) of template(s) to define
   * @throws Exception\ModuleNotDefined If a class doesn't exist
   */
  final public function define($classes, $name = null) {
    if (!is_array($classes)) {
      $classes = array($name => $classes);
    }
    foreach ($classes as $name => $class) {
      if (!is_string($name)) {
        $name = null;
      }
      $this->_define($class, $name);
    }
  }

  /**
   * Factory method for fruit
   *
   * @param  string|array $name    The name of the module to be created
   * @param  array        $options Options to be passed into the FM Module
   * @return AbstractModule  A fully instantiated FM module
   */
  final public function create($name, array $options = array()) {
    if (is_array($name)) {
      $options = $name;
      $name = $options['module'];
      return $this->create($name, $options);
    } else {
      $options['module'] = $name;
    }

    if (!isset($this->_modules[$name])) {
      throw new Exception\ModuleNotDefined("Module '$name' specified cannot be found");
    }

    return new $this->_modules[$name]($this, $options);
  }

  /**
   * Convert a PHP associative array into a model
   *
   * @param  array                       $data Data
   * @return \MattAndrews\ModelInterface An object of the type passed into the FM's constructor
   */
  final public function model($data) {
    if (is_a($data, $this->_model)) {
      return $data;
    }
    return new $this->_model($data);
  }

  /**
   * Reset the FM to its original state
   *
   * @return void
   */
  final public function reset() {
    $this->_modules = array();
  }

}
