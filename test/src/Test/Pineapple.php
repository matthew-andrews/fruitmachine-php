<?php
namespace Test;

class Pineapple extends \FruitMachine\AbstractModule {

  public static $name = 'pineapple';
  private $_counter = 0;

  protected function _getTagAttributes() {
    return array(
      'data-counter' => $this->_counter
    );
  }

  protected function _getTagClasses() {
    return array_merge(array('pineapple-' . $this->_counter), $this->classes);
  }

  public function template(array $data) {
    $this->_counter++;
    return (isset($data[0]) ? $data[0] : '');
  }

}
