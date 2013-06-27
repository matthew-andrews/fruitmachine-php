<?php
package FruitMachine;

class FruitMachine {

  private $_modelClass;
  private $_configTemplateIterator = 'children';
  private $_configTemplateInstance = 'child';

  /**
   * Creates a fruitmachine
   *
   * QUESTION: Perhaps we should be passing in a ModelFactory instead?
   *
   * @param ModelInterface $model [A php object that implements the model interface]
   */
  public function __construct(\MattAndrews\ModelInterface $model) {
    $this->_setModel($model);
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

}
