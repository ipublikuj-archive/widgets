# Quickstart

This extension adds support for dynamical page content components called **widgets**. 

## Installation

The best way to install ipub/widgets is using  [Composer](http://getcomposer.org/):

```sh
$ composer require ipub/widgets
```

### Classic assignment into application without package support

If you don't want to use widgets in packages, you can install this extension in normal way

```neon
extensions:
	widgets: IPub\Widgets\DI\WidgetsExtension
```

### Using with packages

If you want to use this extension with packages system, you have to register it in you bootstrap.php file

```php
// Content of your nette bootstrap.php...

// Create app configurator
$configurator = new \Nette\Configurator();

// Here you can configure nette configurator

// Register widgets container
$configurator->defaultExtensions['widgets'] = '\IPub\Widgets\DI\WidgetsExtension';

// Create app container
$container = $configurator->createContainer();

return $container;
```

As you can see, this extension is assigned into Nette default extensions, to be loaded before all other custom extensions.