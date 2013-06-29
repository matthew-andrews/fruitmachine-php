<?php
namespace FruitMachine;

class FruitMachine {

  private $_modelClass;
  public $config = array(
    'templateIterator' => 'children',
    'templateInstance' => 'child'
  );
  private $_fruit;

  /**
   * Creates a fruitmachine
   *
   * QUESTION: Perhaps we should be passing in a ModelFactory instead?
   *
   * @param ModelInterface $model [A php object that implements the model interface]
   */
  final public function __construct(\MattAndrews\ModelInterface $model) {
    $this->_setModel($model);
    $this->reset();
  }

  /**
   * Set the type of model.
   *
   * @private
   * @param \MattAndrews\ModelInterface $model [description]
   */
  private function _setModel(\MattAndrews\ModelInterface $model) {
    $this->_modelClass = get_class($model);
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
    if (!class_exists($class)) {

      // Manually trigger the autoloading of the specified class...
      spl_autoload_call($class);

      // ... and throw an exception if it wasn't found
      throw new ModuleNotDefinedException("Class passed into FruitMachine#define does not exist");
    }
    $this->_fruit[$name] = $class;
  }

  /**
   * Factory method for fruit
   * @param  String $name    The name of the module to be created
   * @param  array  $options Options to be passed into the FM Module
   * @return AbstractModule  A fully instantiated FM module
   */
  final public function create($name, $options = array()) {
    if (!isset($this->_fruit[$name])) throw new ModuleNotDefinedException("Module specified does not exist");

    $module = new $this->_fruit[$name]($this, $options);
    return $module;
  }

  final public function reset() {
    $this->_fruit = array();
  }

  final public function model(array $data) {
    return new $this->_modelClass($data);
  }

}
