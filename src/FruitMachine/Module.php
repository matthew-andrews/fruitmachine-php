<?php
namespace FruitMachine;

class Module {

  public $children = array();

  public function add(Module $module) {
    array_push($this->children, $module);
  }

}