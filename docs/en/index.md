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

## Creating widget

### Registering widget

Widget is special type of component. So you can create component as usual, but you have to use widget base control class

So now create basic component:

```php
use IPub\Widgets\Widgets;

class Control extends Widgets\Control implements Widgets\IControl
{

}
```

And its [factory](http://pla.nette.org/cs/create-components-with-autowiring) (info in czech):

```php
use IPub\Widgets\Widgets;
use IPub\Widgets\Entities;

interface IControl extends Widgets\IFactory
{
	/**
	 * Define widget name
	 */
	const WIDGET_TYPE = 'widget.name';

	/**
	 * @param Entities\IData $data
	 *
	 * @return Control
	 */
	public function create(Entities\IData $data);
}
```

Now you have to register this component in your application. You can do it in you neon config file:

```php
services:
	yourWidgetName:
		class: Your\Namespace\Widgets\Control
		implement: Your\Namespace\Widgets\IControl
		inject: true
		tags: [ipub.widgets.widget]
```

Don't forget to put **ipub.widgets.widget** tag, this tag is used to identify widget component by extension.

And that is all for basic setup. This widget is now registered in widget manager.

### Configuring widget

All widgets have to be configured before they could be rendered on you page. This configuration must have some mandatory fields:

* **widget title**
* **widget description** for describing widget content.
* **position** which tell where widget should be rendered in page.
* **status** with bool value defining if widget is visible or hidden.
* **priority** which define widgets ordering in one position. Widget with higher number will be first.

So now we have to put widgets manager into your page.

```php
namespace Your\Coool\Namespace\Presenter;

use IPub\Widgets;

class SomePresenter
{
	/**
	 * Insert extension trait (only for PHP 5.4+)
	 */
	use Widgets\TWidgets;
	
	public function beforeRender()
	{
		parent::beforeRender()

		// Load widgets config via your model or from any array etc.
		foreach($this->widgetsModel->loadWidgets() as $widgetConfig) {
			// And register every widget in somePosition with configuration array
			$this['widgets-somePosition']->addWidget($widgetConfig->type, $widget->data);
		}
	}
}
```

And in your presenter template just render component:

```html
<body>
	<div class="some-other">
		{control widgets-somePosition}
	</div>
</body>
```

And that's it, now your widget will be rendered in selected position.