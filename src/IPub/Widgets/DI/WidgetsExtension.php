<?php
/**
 * WidgetsManagerExtension.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Widgets!
 * @subpackage	DI
 * @since		5.0
 *
 * @date		15.09.14
 */

namespace IPub\Widgets\DI;

use Nette;
use Nette\DI;
use Nette\Utils;
use Nette\PhpGenerator as Code;

use IPub;
use IPub\Widgets;
use IPub\Widgets\Components;
use IPub\Widgets\Decorators;
use IPub\Widgets\Exceptions;
use IPub\Widgets\Loaders;
use IPub\Widgets\Repository;

class WidgetsExtension extends DI\Extensions\ExtensionsExtension
{
	// Define tag string for widgets
	const TAG_WIDGET = 'ipub.widgets.widget';
	// Define tag string for widgets decorators
	const TAG_DECORATOR = 'ipub.widgets.decorator';

	/**
	 * Extension default configuration
	 *
	 * @var array
	 */
	protected $defaults = [
		'path' => NULL
	];

	public function loadConfiguration()
	{
		// Get container builder
		$builder = $this->getContainerBuilder();
		// Get extension configuration
		$configuration = $this->getConfig($this->defaults);

		// Path have to be filled
		Utils\Validators::assert($configuration['path'], 'string', 'Widgets packages path');

		// Init packages loader
		$loader		= new Widgets\Loaders\WidgetLoader;
		// Init packages repository
		$repository	= new Widgets\Repository\WidgetsRepository($configuration['path'], $loader);
		// Init package installer
		$installer	= new Widgets\Installers\WidgetsInstaller($loader, $repository);

		/**
		 * Widgets services
		 */

		// Widgets manager
		$widgetsManager = $builder->addDefinition($this->prefix('widgets.manager'))
			->setClass(Widgets\WidgetsManager::CLASSNAME, [
				'repository'	=> $repository,
				'installer'		=> $installer
			])
			->addTag('cms.widgets');

		// Register widgets extensions
		foreach($repository->getPackages() as $packageEntity) {
			// Store info about widget root folder
			$packageEntity->setPath($repository->getPath() .'/'. $packageEntity->getName());

			// Create extension name from package name
			$extensionName = $packageEntity->getExtensionName();

			// Check if widget is already initialized
			if (array_key_exists($extensionName, $this->compiler->getExtensions())) {
				throw new Exceptions\WidgetLoadException(sprintf('Widget already loaded %s.', $packageEntity->getName()));
			}

			// Try to find widget definition file
			if (!file_exists($packageEntity->getPath() . DIRECTORY_SEPARATOR .'widget.neon')) {
				throw new Exceptions\WidgetLoadException(sprintf('Widget definition does not exist (%s).', $packageEntity->getPath()));
			}

			$definition = (!($definition = $this->loadFromFile($packageEntity->getPath() . DIRECTORY_SEPARATOR .'widget.neon')) || 1 === $definition) ? [] : $definition;
			// Get extension class name for widget
			$class = isset($definition['extension']) ? $definition['extension'] : 'IPub\Widgets\WidgetExtension';

			// Register widget extension
			$this->compiler->addExtension($extensionName, new $class($packageEntity, $definition));

			// Assign widget package to manager
			$widgetsManager->addSetup('addPackage', [$packageEntity]);
		}

		$builder->addDefinition($this->prefix('widgets.component'))
			->setClass(Components\Control::CLASSNAME)
			->setImplement(Components\IControl::CLASSNAME)
			->setInject(TRUE)
			->addTag('cms.widgets');

		/**
		 * Widgets decorators
		 */

		// Widgets decorators manager
		$builder->addDefinition($this->prefix('decorators.manager'))
			->setClass(Widgets\DecoratorsManager::CLASSNAME)
			->addTag('cms.widgets');

		// Widgets raw decorator
		$builder->addDefinition($this->prefix('decorator.raw'))
			->setClass(Decorators\Raw\Control::CLASSNAME)
			->setImplement(Decorators\Raw\IControl::CLASSNAME)
			->setInject(TRUE)
			->addTag('cms.widgets')
			->addTag(self::TAG_DECORATOR);
	}

	public function beforeCompile()
	{
		// Get container builder
		$builder = $this->getContainerBuilder();

		// Get widgets manager
		$service = $builder->getDefinition($this->prefix('widgets.manager'));

		// Get all registered widgets components
		foreach (array_keys($builder->findByTag(self::TAG_WIDGET)) as $serviceName) {
			// Register widget to manager
			$service->addSetup('register', ['@' .$serviceName]);
		}

		// Get widgets manager
		$service = $builder->getDefinition($this->prefix('decorators.manager'));

		// Get all registered widgets decorators
		foreach (array_keys($builder->findByTag(self::TAG_DECORATOR)) as $serviceName) {
			// Register decorator to manager
			$service->addSetup('register', ['@' .$serviceName]);
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