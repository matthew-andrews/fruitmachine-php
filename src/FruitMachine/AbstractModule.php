<?php
namespace FruitMachine;

abstract class AbstractModule {

  public $children;
  public $classes = array();
  public $slots;
  public $slot;
  public $parent;
  public $tag;
  public $model;

  private $_fruitmachine;
  private $_modules;
  private $_ids;
  private $_id;
  private $_fmid;
  private $_template;

  final public function __construct(FruitMachine $machine, $options = array()) {
    $this->_fruitmachine = $machine;
    $this->_configure($options);
    if (!empty($options['children'])) $this->_add($options['children']);
  }

  final public function add($child = null, $options = null) {
    if (!$child) return $this;

    // If it's not a Module, make it one.
    if (!($child instanceof AbstractModule)) $child = $this->_fruitmachine->create($child['module'], $child);

    // Options
    $at = is_array($options) && !empty($options['at'])
      ? $options['at']
      : count($this->children);
    $slot = is_array($options) && !empty($options['slot'])
      ? $options['slot']
      : (!is_array($options) ? $options : null);

    // Remove this view first if it already has a parent
    if (!empty($child->parent)) $child->remove();

    // Assign a slot (prefering defined option)
    $slot = $child->slot = !is_null($slot) ? $slot : $child->slot;

    // Remove any module that already occupies this slot
    if ($slot && !empty($this->slots[$slot])) {
      $this->slots[$slot]->remove();
    }

    array_splice($this->children, $at, 0, array($child));
    $this->_addLookup($child);

    // Allow chaining
    return $this;
  }

  final public function each($fn) {
    $l = count($this->children);
    $result;

    for ($i = 0; $i < $l; $i++) {
      $result = $fn($this->children[$i]);
      if ($result) return $result;
    }
  }

  final public function id() {
    return $this->_id;
  }

  final public function module($key = null) {
    if (!$key) return $this->_module();

    if (isset($this->_modules[$key]) && isset($this->_modules[$key][0])) {
      return $this->_modules[$key][0];
    }

    return $this->each(function($view) use ($key) {
      return $view->module($key);
    });
  }

  final public function remove($param1 = array(), $param2 = array()) {

    // Don't do anything if the first arg is null
    if (func_num_args() === 1 && is_null($param1)) return $this;

    // Allow view.remove(child[, options])
    // and view.remove([options]);
    if ($param1 instanceof AbstractModule) {
      return $param1->remove($param2);
    }

    // Options and aliases
    $options = $param1;
    $parent = $this->parent;

    // Unless stated otherwise,
    // remove the view element
    // from its parent node.
    if ($parent) {

      // Remove reference from views array
      $index = array_search($this, $parent->children, true);
      array_splice($parent->children, $index, 1);

      // Remove references from the lookup
      $parent->removeLookup($this);
    }

    return $this;
  }

  final public function removeLookup($child) {
    $module = $child->module();

    // Remove the module lookup
    $index = array_search($child, $this->_modules[$module], true);
    array_splice($this->_modules[$module], $index);

    // Remove the id and slot lookup
    unset($this->_ids[$child->id()]);
    unset($this->slots[$child->slot]);
    unset($child->parent);
  }

  final public function toHTML() {
    $data = array();
    $templateInstance = $this->_fruitmachine->config['templateInstance'];

    // Create an array for view
    // children data needed in template.
    $data[$this->_fruitmachine->config['templateIterator']] = array();

    // Loop each child
    $this->each(function($child) use (&$data, $templateInstance) {
      $tmp = array();
      $html = $child->toHTML();
      $slot = $child->slot ? $child->slot : $child->id();
      $data[$slot] = $html;
      $tmp[$templateInstance] = $html;
      array_push($data['children'], array_merge($tmp, $child->model->toJSON()));
    });

    // Run the template render method
    // passing children data (for looping
    // or child views) mixed with the
    // view's model data.
    $html = $this->template($data + $this->model->toJSON());

    // Wrap the html in a FruitMachine
    // generated root element and return.
    return $this->_wrapHTML($html);
  }

  abstract public function template(array $data);

  /**
   * A private add method
   * that accepts a list of
   * children.
   *
   * @param array|AbstractModule $children children
   */
  private function _add($children) {
    if (!$children) return;

    $isArray = is_array($children);

    foreach ($children as $key => $child) {
      if (!$isArray) $child->slot = $key;
      $this->add($child);
    }
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
    // $this->classes = $this->classes || options.classes || [];
    // $this->helpers = $this->helpers || options.helpers || [];
    // $this->template = $this->_setTemplate(options.template || $this->template);
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
    $model = isset($options['model'])
      ? $options['model']
      : (isset($options['data']) ? $options['data'] : array());

    // Would json_encode turn array into a JS array (or object)?
    $this->model = array_values($model) === $model
      ? $this->_fruitmachine->model($model)
      : $model;
  }


  /**
   * Detech the module's name by looking at the class
   *
   * @private
   * @return [String] The name of the module
   */
  private function _module() {
    $class = get_class($this);
    return strtolower(array_pop(explode('\\', $class)));
  }

  private function _wrapHTML($html) {
    return '<'. $this->tag
      . ' class="' . $this->module() . ' ' . implode(' ', $this->classes) . '"'
      . ' id="' . $this->_fmid . '">'
      . $html
      . '</' . $this->tag . '>';
  }

}