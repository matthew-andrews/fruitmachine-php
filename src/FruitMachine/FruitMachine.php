<?php
namespace FruitMachine;

class FruitMachine {

  private $_model;
  private $_fruit;

  public $config = array(
    'templateIterator' => 'children',
    'templateInstance' => 'child'
  );

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
      throw new ModelNotDefinedException("Class '$model' passed into FruitMachine cannot be found");
    }

    // But store the classname internally if it was.
    $this->_model = $model;
  }

  /**
   * Defines a module
   *
   * @param  String $name  The name of the fruit being defined
   * @param  String $class The name of the PHP classname that the fruit corresponds to
   * @throws ModuleNotDefinedException If the class described in $class does not exist
   * @return void
   */
  final public function define($name, $class) {

    // Manually trigger the autoloading of the specified class...
    if (!class_exists($class)) {
      spl_autoload_call($class);
    }

    // ... and throw an exception if it wasn't found
    if (!class_exists($class)) {
      throw new ModuleNotDefinedException("Class '$class' passed into FruitMachine#define cannot be found");
    }

    $this->_fruit[$name] = $class;
  }

  /**
   * Factory method for fruit
   *
   * @param  string|array $name    The name of the module to be created
   * @param  array        $options Options to be passed into the FM Module
   * @return AbstractModule  A fully instantiated FM module
   */
  final public function create($name, $options = array()) {
    if (is_array($name)) {
      $options = $name;
      $module = $options['module'];
      unset($options['module']);
      return $this->create($module, $options);
    }
    return $this->_create($name, $options);
  }

  final public function model(array $data) {
    return new $this->_model($data);
  }

  final public function reset() {
    $this->_fruit = array();
  }

  private function _create($name, array $options) {
    if (!isset($this->_fruit[$name])) {
      throw new ModuleNotDefinedException("Module '$name' specified cannot be found");
    }

    $module = new $this->_fruit[$name]($this, $options);
    return $module;
  }

}
