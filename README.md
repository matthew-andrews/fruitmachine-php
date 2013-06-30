# fruitmachine-php [![Build Status](https://travis-ci.org/matthew-andrews/fruitmachine-php.png?branch=master)](https://travis-ci.org/matthew-andrews/fruitmachine-php)

A lightweight component layout engine for [client](//github.com/ftlabs/fruitmachine) and server.

PHP port of [@FTLabs](//github.com/FTLabs)' [fruitmachine](//github.com/ftlabs/fruitmachine).

FruitMachine is designed to build rich interactive layouts from modular, reusable components. It's light and unopinionated so that it can be applied to almost any layout problem. FruitMachine is currently powering the [FT Web App](http://apps.ft.com/ftwebapp/).

```php
// Define a module
class Apple extends \FruitMachine\AbstractModule

  public funciton template() {
    return 'hello'
  }

}

\FruitMachine\Singleton::getInstance()->define('apple', '\Apple');

// Create a module
$apple = \FruitMachine::Singleton->getInstance()->create('apple');

// Render it
$apple->toHTML();
//=> <div class="apple">hello</div>

## Installation

```
$ composer install fruitmachine-php # coming soon
```

## Author

- **Matt Andrews** - [@matthew-andrews](http://github.com/matthew-andrews)

## Credits and collaboration

The lead developer of FruitMachine-php is [Matt Andrews](http://github.com/matthew-andrews) at FT Labs. All open source code released by FT Labs is licenced under the MIT licence. We welcome comments, feedback and suggestions. Please feel free to raise an issue or pull request. Enjoy.
