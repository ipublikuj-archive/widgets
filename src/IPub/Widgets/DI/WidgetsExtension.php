<?php
/**
 * WidgetsManagerExtension.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Widgets!
 * @subpackage     DI
 * @since          1.0.0
 *
 * @date           15.09.14
 */

namespace IPub\Widgets\DI;

use Nette;
use Nette\DI;
use Nette\Utils;

use IPub;
use IPub\Widgets;
use IPub\Widgets\Components;
use IPub\Widgets\Decorators;
use IPub\Widgets\Exceptions;
use IPub\Widgets\Filter;

/**
 * Widgets extension container
 *
 * @package        iPublikuj:Widgets!
 * @subpackage     DI
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
class WidgetsExtension extends DI\CompilerExtension
{
	// Define tag string for widgets
	const TAG_WIDGET_CONTROL = 'ipub.widgets.widget';

	// Define tag string for widgets decorators
	const TAG_WIDGET_DECORATOR = 'ipub.widgets.decorator';

	public function loadConfiguration()
	{
		// Get container builder
		$builder = $this->getContainerBuilder();

		/**
		 * Widgets services
		 */

		// Widgets manager
		$builder->addDefinition($this->prefix('widgets.manager'))
			->setClass(Widgets\WidgetsManager::CLASS_NAME)
			->addTag('cms.widgets');

		// Widgets filter manager
		$filtersManager = $builder->addDefinition($this->prefix('filters.manager'))
			->setClass(Widgets\FiltersManager::CLASS_NAME)
			->addTag('cms.widgets');

		// Register widget filters
		$filtersManager->addSetup('register', ['priority', Filter\PriorityFilter::CLASS_NAME]);
		$filtersManager->addSetup('register', ['status', Filter\StatusFilter::CLASS_NAME, 16]);

		$builder->addDefinition($this->prefix('widgets.component'))
			->setClass(Components\Control::CLASS_NAME)
			->setImplement(Components\IControl::INTERFACE_NAME)
			->setArguments([new Nette\PhpGenerator\PhpLiteral('$position')])
			->setInject(TRUE)
			->addTag('cms.widgets');

		/**
		 * Widgets decorators
		 */

		// Widgets decorators manager
		$builder->addDefinition($this->prefix('decorators.manager'))
			->setClass(Widgets\DecoratorsManager::CLASS_NAME)
			->addTag('cms.widgets');

		// Widgets raw decorator
		$builder->addDefinition('widgets.decorator.raw')
			->setClass(Decorators\Raw\Control::CLASS_NAME)
			->setImplement(Decorators\Raw\IControl::CLASS_NAME)
			->setInject(TRUE)
			->addTag('cms.widgets')
			->addTag(self::TAG_WIDGET_DECORATOR);
	}

	public function beforeCompile()
	{
		// Get container builder
		$builder = $this->getContainerBuilder();

		// Get widgets manager
		$service = $builder->getDefinition($this->prefix('widgets.manager'));

		// Get all registered widgets components
		foreach ($builder->findByTag(self::TAG_WIDGET_CONTROL) as $serviceName => $groups) {
			// Check for valid group format
			$groups = is_array($groups) ? $groups : (is_string($groups) ? [$groups] : ['default']);

			// Register widget to manager and group
			foreach ($groups as $group) {
				$service->addSetup('register', ['@' . $serviceName, $serviceName, $group]);
			}
		}

		// Get widgets decorators manager
		$service = $builder->getDefinition($this->prefix('decorators.manager'));

		// Get all registered widgets decorators
		foreach (array_keys($builder->findByTag(self::TAG_WIDGET_DECORATOR)) as $serviceName) {
			// Register decorator to manager
			$service->addSetup('register', ['@' . $serviceName, $serviceName]);
		}

		// Get widgets control provider
		$service = $builder->getDefinition($this->prefix('widgets.component'));

		// Search for widgets provider extensions
		/** @var IWidgetsProvider $extension */
		foreach ($this->compiler->getExtensions(IWidgetsProvider::INTERFACE_NAME) as $extension) {
			// Get widget groups & widgets from extension
			foreach ($extension->getWidgets() as $group => $widgets) {
				foreach ($widgets as $id => $properties) {
					if (!isset($properties['priority'])) {
						$properties['priority'] = 100;
					}

					$service->addSetup('addWidget', [$properties['type'], $properties, $group, $properties['position']]);
				}
			}
		}
	}

	/**
	 * @param Nette\Configurator $config
	 * @param string $extensionName
	 */
	public static function register(Nette\Configurator $config, $extensionName = 'widgets')
	{
		$config->onCompile[] = function (Nette\Configurator $config, Nette\DI\Compiler $compiler) use ($extensionName) {
			$compiler->addExtension($extensionName, new WidgetsExtension());
		};
	}
}
