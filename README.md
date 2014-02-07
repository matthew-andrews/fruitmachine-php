# FruitMachine-php [![Build Status](https://travis-ci.org/matthew-andrews/fruitmachine-php.png?branch=master)](https://travis-ci.org/matthew-andrews/fruitmachine-php)

PHP port of [@FTLabs](//github.com/FTLabs)' [fruitmachine](//github.com/ftlabs/fruitmachine), a lightweight component layout engine for [client](//github.com/ftlabs/fruitmachine) and server.

FruitMachine is designed to build rich interactive layouts from modular, reusable components. It's light and unopinionated so that it can be applied to almost any layout problem. FruitMachine is currently powering the [FT Web App](http://apps.ft.com/ftwebapp/).

```php
// Define a module
class Apple extends \FruitMachine\AbstractModule {

  public static $name = 'apple';

  public function template() {
    return 'hello'
  }

}

$fm = \FruitMachine\Singleton::getInstance();

// Define a module
$fm->define('\Apple');

// Create a module
$apple = $fm->create('apple');

// Render it
echo $apple->toHTML();
//=> <div class="apple">hello</div>
```

## Installation

To install from packagist either add `mattandrews/fruitmachine` to your project's `composer.json` file or enter the following on the command line:-
```
composer require mattandrews/fruitmachine
```

## Compatability

Unit tests are run on each build against PHP 5.3, 5.4 and 5.5.

## Author

- **Matt Andrews** - [@matthew-andrews](http://github.com/matthew-andrews)

## Credits and collaboration

The lead developer of FruitMachine-php is [Matt Andrews](http://github.com/matthew-andrews) at FT Labs. All open source code released by FT Labs is licenced under the MIT licence. We welcome comments, feedback and suggestions. Please feel free to raise an issue or pull request. Enjoy.
