<?php
namespace FruitMachine;

/**
 * Abstract Module
 *
 * Your project's modules should extend from this
 *
 * @author Matt Andrews <matt@mattandre.ws>
 * @copyright The Financial Times Limited [All Rights Reserved]
 */

abstract class AbstractModule {

  public $children = array();
  public $classes = array();
  public $slots = array();
  public $slot;
  public $parent;
  public $tag;
  public $model;

  private $_fruitmachine;
  private $_modules = array();
  private $_ids = array();
  private $_id;
  private $_fmid;

  /**
   * The name of the module
   * @var string
   */
  protected $_module;

  /**
   * Module constructor
   *
   * Options:
   *
   *  - `id {String}` a unique id to query by
   *  - `model {Object|Model}` the data with which to associate this module
   *  - `tag {String}` tagName to use for the root element
   *  - `classes {Array}` list of classes to add to the root element
   *  - `template {Function}` a template to use for rendering
   *  - `helpers {Array}`a list of helper function to use on this module
   *  - `children {Object|Array}` list of child modules
   *
   * @constructor
   * @param FruitMachine $machine
   * @param array        $options
   */
  final public function __construct(FruitMachine $machine, array $options) {
    $this->_fruitmachine = $machine;
    $this->_configure($options);
    if (!empty($options['children'])) {
      $this->_add($options['children']);
    }
  }

  /**
   * Adds a child view(s) to another Module.
   *
   * Options:
   *
   *  - `at` The child index at which to insert
   *  - `inject` Injects the child's view element into the parent's
   *  - `slot` The slot at which to insert the child
   *
   * @param AbstractModule|array $children
   * @param array|string|number   $options
   */
  final public function add($child = null, $options = null) {
    if (!$child) {
      return $this;
    }

    // If it's not a Module, make it one.
    if (!($child instanceof AbstractModule)) {
      $child = $this->_fruitmachine->create($child['module'], $child);
    }

    // Options
    $at = is_array($options) && !empty($options['at'])
      ? $options['at']
      : count($this->children);
    $slot = is_array($options) && !empty($options['slot'])
      ? $options['slot']
      : (!is_array($options) ? $options : null);

    // Remove this view first if it already has a parent
    if (!empty($child->parent)) {
      $child->remove();
    }

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
    foreach ($this->children as $child) {
      $result = $fn($child);
      if ($result) {
        return $result;
      }
    }
  }

  final public function id($id = null) {
    if (func_num_args() === 0) {
      return $this->_id;
    }

    if (isset($this->_ids[$id])) {
      return $this->_ids[$id];
    }

    return $this->each(function($view) use ($id) {
      return $view->id($id);
    });
  }

  /**
   * Creates a lookup reference for the child view passed.
   *
   * @param  string                $key The key to search for
   * @return string|AbstractModule      The module string or AbstractModule
   */
  final public function module($key = null) {
    if (func_num_args() === 0) {
      return $this->_module;
    }

    if (isset($this->_modules[$key]) && isset($this->_modules[$key][0])) {
      return $this->_modules[$key][0];
    }

    return $this->each(function($view) use ($key) {
      return $view->module($key);
    });
  }

  /**
   * Returns a list of descendent Modules
   * that match the module type given
   * (Similar to Element.querySelectorAll();)
   *
   * @param  string $key Search by this
   * @return array       A list of modules matching the key
   */
  final public function modules($key) {
    $list = isset($this->_modules[$key])
      ? $this->_modules[$key]
      : array();

    // Then loop each child and run the
    // same opperation, appending the result
    // onto the list.
    $this->each(function(AbstractModule $view) use (&$list, $key) {
      $list = $list + $view->modules($key);
    });

    return $list;
  }

  public function name() {
    return $this->_module;
  }

  final public function remove($param1 = array(), $param2 = array()) {

    // Don't do anything if the first arg is null
    if (func_num_args() === 1 && is_null($param1)) {
      return $this;
    }

    // Allow view.remove(child[, options]) and view.remove([options]);
    if ($param1 instanceof AbstractModule) {
      return $param1->remove($param2);
    }

    // Unless stated otherwise, remove the view element from its
    // parent node.
    if ($this->parent) {

      // Remove reference from views array
      $index = array_search($this, $this->parent->children, true);
      array_splice($this->parent->children, $index, 1);

      // Remove references from the lookup
      $this->parent->removeLookup($this);
    }

    return $this;
  }

  public function removeLookup(AbstractModule $child) {
    $module = $child->module();

    // Remove the module lookup
    $index = array_search($child, $this->_modules[$module], true);
    array_splice($this->_modules[$module], $index);

    // Remove the id and slot lookup
    unset($this->_ids[$child->id()]);
    unset($this->slots[$child->slot]);
    unset($child->parent);
  }


  /**
   * Templates the view, including any descendent views returning an
   * html string. All data in the views model is made accessible to
   * the template.
   *
   * Child views are printed into the parent template by `id`.
   * Alternatively children can be iterated over a a list and printed
   * with `{{{child}}}}`.
   *
   * Example:
   *
   *   <div class="slot-1">{{{<slot>}}}</div>
   *   <div class="slot-2">{{{<slot>}}}</div>
   *
   *   // or
   *
   *   {{#children}}
   *     {{{child}}}
   *   {{/children}}
   *
   * @return string
   */
  final public function toHTML() {
    $data = array();
    $templateInstance = $this->_fruitmachine->config['templateInstance'];

    // Create an array for view children data needed in template.
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

    // Run the template render method passing children data (for
    // looping or child views) mixed with the view's model data.
    $html = $this->template($data + $this->model->toJSON());

    // Wrap the html in a FruitMachine generated root element and
    // return.
    return $this->_wrapHTML($html);
  }

  abstract public function template(array $data);

  /**
   * A private add method that accepts a list of children.
   *
   * @param array|AbstractModule $children children
   */
  private function _add(array $children) {
    $isArray = array_values($children) === $children;

    foreach ($children as $key => $child) {
      if (!$isArray) {
        $this->add($child, $key);
      } else {
        $this->add($child);
      }
    }
  }

  private function _addLookup(AbstractModule $child) {
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

  /**
   * Configures the new Module with the
   * options passed to the constructor.
   *
   * @param  array $options An array of options
   * @return void
   */
  private function _configure(array $options) {

    // Setup properties
    $this->_id = !empty($options['id']) ? $options['id'] : Util::uniqueId();
    $this->_fmid = !empty($options['fmid']) ? $options['fmid'] : Util::uniqueId('fmid');
    $this->tag = !empty($options['tag']) ? $options['tag'] : 'div';
    $this->classes = isset($options['classes'])
      ? $options['classes']
      : (isset($this->classes) ? $this->classes : array());
    $this->slot = !empty($options['slot']) ? $options['slot'] : null;
    $this->_module = !empty($options['module']) ? $options['module'] : self::$name;

    // Use the model passed in,
    // or create a model from
    // the data passed in.
    $model = isset($options['model'])
      ? $options['model']
      : (isset($options['data']) ? $options['data'] : array());

    // Ensure model is a model
    $this->model = $this->_fruitmachine->model($model);
  }

  /**
   * Wraps the module html in a root element.
   *
   * @param  string $html The HTML to be wrapped
   * @return string       Wrapped HTML
   */
  private function _wrapHTML($html) {

    $attrs = $this->_getTagAttributes();
    $classes = $this->_getTagClasses();
    array_unshift($classes, $this->module());

    $attrs['class'] = implode(' ', $classes);
    $attrs['id'] = $this->_fmid;

    array_walk($attrs, function($v, $k) use (&$attrs) {
      $attrs[$k] = sprintf('%s="%s"', $this->_encodeHTML($k), $this->_encodeHTML($v));
    });

    return '<'. $this->tag . ' ' . implode(' ', $attrs) . '>'
      . $html
      . '</' . $this->tag . '>';
  }

  private function _encodeHTML($html) {
    return htmlspecialchars($html, ENT_COMPAT, 'UTF-8');
  }

  /**
   * HTML attributes for the module tag.
   * Can be overridden in implementations of this abstract module.
   *
   * @return array Array of key:val HTML attribute pairs
   */
  protected function _getTagAttributes() {
    return array();
  }

  /**
   * HTML classes for the module tag.
   * Can be overridden in implementations of this abstract module.
   *
   * @return array Array of key:val HTML attribute pairs
   */
  protected function _getTagClasses() {
    return $this->classes;
  }

}