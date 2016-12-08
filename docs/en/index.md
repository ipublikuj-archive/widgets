# Quickstart

This extension adds support for dynamical page content components called **widgets**. 

## Installation

The best way to install ipub/widgets is using  [Composer](http://getcomposer.org/):

```sh
$ composer require ipub/widgets
```

After that you have to register extension in config.neon.

```neon
extensions:
	widgets: IPub\Widgets\DI\WidgetsExtension
```

## Creating widget

### Registering widget

Widget is special type of component. So you can create component as usual, but you have to use widget base control class

So now create component as usual:

```php
use IPub\Widgets\Widgets;

class SuperCoolWidget extends Widgets\Component implements Widgets\IWidget
{

}
```

And its [factory](http://pla.nette.org/cs/create-components-with-autowiring) (info in czech):

```php
use IPub\Widgets\Widgets;
use IPub\Widgets\Entities;

interface ISuperCoolWidgetFactory extends Widgets\IFactory
{
	/**
	 * @param Entities\IData|array $data
	 *
	 * @return SuperCoolWidget
	 */
	public function create($data);
}
```

Now you have to register this component in your application. You can do it in you neon config file:

```php
services:
	yourWidgetName:
		class: Your\Namespace\Widgets\SuperCoolWidget
		implement: Your\Namespace\Widgets\ISuperCoolWidgetFactory
		inject: true
		tags: [ipub.widgets.widget]
```

Don't forget to put **ipub.widgets.widget** tag, this tag is used to identify widget component by extension.

And that is all for basic setup. This widget is now registered in widget manager.

### Configuring widget

All widgets have to be configured before they could be rendered on your page. This configuration must have some mandatory fields:

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
