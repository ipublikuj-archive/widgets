<?php
/**
 * WidgetsManagerExtension.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec https://www.ipublikuj.eu
 * @package        iPublikuj:Widgets!
 * @subpackage     DI
 * @since          1.0.0
 *
 * @date           15.09.14
 */

declare(strict_types = 1);

namespace IPub\Widgets\DI;

use Nette;
use Nette\DI;
use Nette\PhpGenerator as Code;
use Nette\Security as NS;
use Nette\Utils;

use IPub\Widgets;
use IPub\Widgets\Components;
use IPub\Widgets\Decorators;
use IPub\Widgets\Exceptions;
use IPub\Widgets\Filters;

/**
 * Widgets extension container
 *
 * @package        iPublikuj:Widgets!
 * @subpackage     DI
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
final class WidgetsExtension extends DI\CompilerExtension
{
	// Define tag string for widgets
	const TAG_WIDGET_CONTROL = 'ipub.widgets.widget';

	// Define tag string for widgets decorators
	const TAG_WIDGET_DECORATOR = 'ipub.widgets.decorator';

	// Define tag string for widgets decorators
	const TAG_WIDGET_FILTER = 'ipub.widgets.filter';

	public function loadConfiguration() : void
	{
		// Get container builder
		$builder = $this->getContainerBuilder();

		/**
		 * Widgets services
		 */

		// Widgets manager
		$builder->addDefinition($this->prefix('widgets.manager'))
			->setType(Widgets\Managers\WidgetsManager::class)
			->addTag('cms.widgets');

		$builder->addDefinition($this->prefix('widgets.component'))
			->setType(Components\Control::class)
			->setImplement(Components\IControl::class)
			->setArguments([new Code\PhpLiteral('$position')])
			->setInject(TRUE)
			->addTag('cms.widgets');

		/**
		 * Widgets filters
		 */

		// Widgets filter manager
		$builder->addDefinition($this->prefix('filters.manager'))
			->setType(Widgets\Managers\FiltersManager::class)
			->addTag('cms.widgets');

		// Widgets priority filter
		$builder->addDefinition('widgets.filters.priority')
			->setType(Filters\Priority\Filter::class)
			->setImplement(Filters\Priority\IFilter::class)
			->setInject(TRUE)
			->addTag('cms.widgets')
			->addTag(self::TAG_WIDGET_FILTER);

		// Widgets status filter
		$builder->addDefinition('widgets.filters.status')
			->setType(Filters\Status\Filter::class)
			->setImplement(Filters\Status\IFilter::class)
			->setInject(TRUE)
			->addTag('cms.widgets')
			->addTag(self::TAG_WIDGET_FILTER);

		/**
		 * Widgets decorators
		 */

		// Widgets decorators manager
		$builder->addDefinition($this->prefix('decorators.manager'))
			->setType(Widgets\Managers\DecoratorsManager::class)
			->addTag('cms.widgets');

		// Widgets raw decorator
		$builder->addDefinition('widgets.decorator.raw')
			->setType(Decorators\Raw\Control::class)
			->setImplement(Decorators\Raw\IControl::class)
			->setInject(TRUE)
			->addTag('cms.widgets')
			->addTag(self::TAG_WIDGET_DECORATOR);
	}

	/**
	 * {@inheritdoc}
	 */
	public function beforeCompile() : void
	{
		parent::beforeCompile();

		// Get container builder
		$builder = $this->getContainerBuilder();

		// Get widgets manager
		$widgetsManager = $builder->getDefinition($this->prefix('widgets.manager'));

		// Get all registered widgets components
		foreach ($builder->findByTag(self::TAG_WIDGET_CONTROL) as $serviceName => $groups) {
			// Check for valid group format
			$groups = is_array($groups) ? $groups : (is_string($groups) ? [$groups] : ['default']);

			// Register widget to manager and group
			foreach ($groups as $group) {
				$widgetsManager->addSetup('register', ['@' . $serviceName, $serviceName, $group]);
			}
		}

		// Get widgets decorators manager
		$decoratorsManager = $builder->getDefinition($this->prefix('decorators.manager'));

		// Get all registered widgets decorators
		foreach (array_keys($builder->findByTag(self::TAG_WIDGET_DECORATOR)) as $serviceName) {
			// Register decorator to manager
			$decoratorsManager->addSetup('register', ['@' . $serviceName, $serviceName]);
		}

		// Get widgets filters manager
		$filtersManager = $builder->getDefinition($this->prefix('filters.manager'));

		// Get all registered widgets decorators
		foreach (array_keys($builder->findByTag(self::TAG_WIDGET_FILTER)) as $serviceName) {
			$priority = 999;

			// Register filter to manager
			$filtersManager->addSetup('register', ['@' . $serviceName, $serviceName, $priority]);
		}

		// Get widgets control provider
		$widgetsContainer = $builder->getDefinition($this->prefix('widgets.component'));

		// Search for widgets provider extensions
		/** @var IWidgetsProvider $extension */
		foreach ($this->compiler->getExtensions(IWidgetsProvider::class) as $extension) {
			// Get widget groups & widgets from extension
			foreach ($extension->getWidgets() as $group => $widgets) {
				foreach ($widgets as $id => $properties) {
					if (!isset($properties['type'])) {

					}

					if (!isset($properties['position'])) {

					}

					$type = $properties['type'];
					$position = $properties['position'];

					unset($properties['type']);

					if (!isset($properties['priority'])) {
						$properties['priority'] = 100;
					}

					$widgetsContainer->addSetup('addWidget', [$type, $properties, $group, $position]);
				}
			}
		}

		// Install extension latte macros
		$latteFactory = $builder->getDefinition($builder->getByType(Nette\Bridges\ApplicationLatte\ILatteFactory::class) ?: 'nette.latteFactory');
		$latteFactory->addSetup('IPub\Widgets\Latte\Macros::install(?->getCompiler())', ['@self']);
	}

	/**
	 * @param Nette\Configurator $config
	 * @param string $extensionName
	 *
	 * @return void
	 */
	public static function register(Nette\Configurator $config, string $extensionName = 'widgets') : void
	{
		$config->onCompile[] = function (Nette\Configurator $config, Nette\DI\Compiler $compiler) use ($extensionName) {
			$compiler->addExtension($extensionName, new WidgetsExtension());
		};
	}
}
