<?php
namespace FruitMachine;

class FruitMachine {

  private $_model;
  public $config = array(
    'templateIterator' => 'children',
    'templateInstance' => 'child'
  );
  private $_fruit;

  /**
   * Creates a fruitmachine
   *
   * @param ModelInterface $model A php object that implements the model interface
   */
  final public function __construct($model) {
    $this->reset();

    // If it isn't already loaded trigger the autoloading of model class
    if (!class_exists($model)) spl_autoload_call($model);

    // ... and throw an exception if it wasn't found
    if (!class_exists($model)) throw new ModelNotDefinedException("Class passed into FruitMachine cannot be found");

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
    if (!class_exists($class)) spl_autoload_call($class);

    // ... and throw an exception if it wasn't found
    if (!class_exists($class)) throw new ModuleNotDefinedException("Class passed into FruitMachine#define cannot be found");

    $this->_fruit[$name] = $class;
  }

  /**
   * Factory method for fruit
   * @param  String $name    The name of the module to be created
   * @param  array  $options Options to be passed into the FM Module
   * @return AbstractModule  A fully instantiated FM module
   */
  final public function create($name, $options = array()) {
    if (!isset($this->_fruit[$name])) throw new ModuleNotDefinedException("Module specified cannot be found");

    $module = new $this->_fruit[$name]($this, $options);
    return $module;
  }

  final public function reset() {
    $this->_fruit = array();
  }

  final public function model(array $data) {
    return new $this->_model($data);
  }

}
