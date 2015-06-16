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
use IPub\Widgets\Exceptions;
use IPub\Widgets\Filters;
use IPub\Widgets\Loaders;
use IPub\Widgets\Repository;

class WidgetsExtension extends DI\Extensions\ExtensionsExtension
{
	// Define tag string for widgets
	const TAG_WIDGET = 'ipub.widgets.widget';

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
		 * Register services
		 */
		$widgetsManager = $builder->addDefinition($this->prefix('manager'))
			->setClass(Widgets\WidgetsManager::CLASSNAME, [
				'repository'	=> $repository,
				'installer'		=> $installer
			])
			->addTag('cms.widgets');

		// Widgets filter manager
		$widgetsFilter = $builder->addDefinition($this->prefix('filter'))
			->setClass(Widgets\FiltersManager::CLASSNAME);

		// Register widgets filters
		$widgetsFilter->addSetup('register', ['access', Filters\AccessFilter::CLASSNAME, 16]);
		$widgetsFilter->addSetup('register', ['priority', Filters\PriorityFilter::CLASSNAME]);

		// Register widgets
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
			if (!file_exists($packageEntity->getPath() .'/widget.neon')) {
				throw new Exceptions\WidgetLoadException(sprintf('Widget definition does not exist (%s).', $packageEntity->getPath()));
			}

			$definition = (!($definition = $this->loadFromFile($packageEntity->getPath() .'/widget.neon')) || 1 === $definition) ? [] : $definition;
			// Get extension class name for widget
			$class = isset($definition['extension']) ? $definition['extension'] : 'IPub\Widgets\WidgetExtension';

			// Register widget extension
			$this->compiler->addExtension($extensionName, new $class($packageEntity, $definition));

			// Assign widget package to manager
			$widgetsManager->addSetup('addPackage', [$packageEntity]);
		}
	}

	public function beforeCompile()
	{
		// Get container builder
		$builder = $this->getContainerBuilder();

		// Get widget provider
		$manager = $builder->getDefinition($this->prefix('manager'));

		// Get all registered widgets components
		foreach (array_keys($builder->findByTag(self::TAG_WIDGET)) as $serviceName) {
			// Register widget to provider
			$manager->addSetup('register', ['@' .$serviceName]);
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