<?php
namespace FruitMachine;

class Module {

  public $children;
  public $slots;
  public $slot;
  public $parent;
  public $tag;
  public $model;

  private $_modules;
  private $_ids;
  private $_id;
  private $_fmid;

  public function __construct($options = array()) {

    // Various config steps
    $this->_configure($options);
    if (!empty($options['children'])) $this->_add($options['children']);
  }

  public function add(Module $child = null, $options = null) {
    if (!$child) return $this;

    // Options
    $at = is_array($options) && !empty($options['at'])
      ? $options['at']
      : count($this->children);
    $slot = is_array($options) && !empty($options['slot'])
      ? $options['slot']
      : !is_array($options) ? $options : null;

    // Remove this view first if it already has a parent
    if (!empty($child->parent)) $child->remove();

    // Assign a slot (prefering defined option)
    $slot = $child->slot = !is_null($slot) ? $slot : $child->slot;

    // Remove any module that already occupies this slot
    if (!empty($this->slots[$slot])) {
      $this->slots[$slot]->remove();
    }

    // If it's not a Module, make it one.
    if (!($child instanceof Module)) $child = new Module($child);

    array_splice($this->children, $at, 0, array($child));
    $this->_addLookup($child);

    // Allow chaining
    return $this;
  }

  private function _addLookup($child) {
    $module = $child->module();

    // Add a lookup for module
    if (empty($this->_modules[$module])) $this->_modules[$module] = array();
    array_push($this->_modules[$module], $child);

    // Add a lookup for id
    $this->_ids[$child->id()] = $child;

    // Store a reference by slot
    if ($child->slot) $this->slots[$child->slot] = $child;

    // Add a reference to the child's parent
    $child->parent = $this;
  }

  private function _configure($options) {

    // Setup static properties
    $this->_id = !empty($options['id']) ? $options['id'] : Util::uniqueId();
    $this->_fmid = !empty($options['fmid']) ? $options['fmid'] : Util::uniqueId('fmid');
    $this->tag = !empty($options['tag']) ? $options['tag'] : 'div';
    // this.classes = this.classes || options.classes || [];
    // this.helpers = this.helpers || options.helpers || [];
    // this.template = this._setTemplate(options.template || this.template);
    $this->slot = !empty($options['slot']) ? $options['slot'] : null;

    // Create id and module
    // lookup objects
    $this->children = array();
    $this->_ids = array();
    $this->_modules = array();
    $this->slots = array();

    // Use the model passed in,
    // or create a model from
    // the data passed in.
    // model = options.model || options.data || {};
    // this.model = util.isPlainObject(model)
      // ? new this.Model(model)
      // : model;
  }

  final public function module() {
    return get_class($this);
  }

  final public function id() {
    return $this->_id;
  }

}