<?php
/**
 * WidgetsManagerExtension.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:WidgetsManager!
 * @subpackage	DI
 * @since		5.0
 *
 * @date		15.09.14
 */

namespace IPub\WidgetsManager\DI;

use Nette;
use Nette\Configurator;
use Nette\DI\Compiler;
use Nette\PhpGenerator as Code;

use IPub;
use IPub\WidgetsManager;

if (!class_exists('Nette\DI\CompilerExtension')) {
	class_alias('Nette\Config\CompilerExtension', 'Nette\DI\CompilerExtension');
	class_alias('Nette\Config\Compiler', 'Nette\DI\Compiler');
	class_alias('Nette\Config\Helpers', 'Nette\DI\Config\Helpers');
}

if (isset(Nette\Loaders\NetteLoader::getInstance()->renamed['Nette\Configurator']) || !class_exists('Nette\Configurator')) {
	unset(Nette\Loaders\NetteLoader::getInstance()->renamed['Nette\Configurator']);
	class_alias('Nette\Config\Configurator', 'Nette\Configurator');
}

class WidgetsManagerExtension extends Nette\DI\CompilerExtension
{
	// Define tag string for widgets
	const TAG_WIDGET = 'ipub.widgets.widget';

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		// Widgets provider
		$widgetsProvider = $builder->addDefinition($this->prefix('provider'))
			->setClass('IPub\WidgetsManager\WidgetsProvider');

		// Register widgets filters
		$widgetsProvider->addSetup('registerFilter', array('access', 'IPub\WidgetsManager\Filters\AccessFilter', 16));
		$widgetsProvider->addSetup('registerFilter', array('priority', 'IPub\WidgetsManager\Filters\PriorityFilter'));
	}

	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();

		// Get widget provider
		$provider = $builder->getDefinition($this->prefix('provider'));

		// Get all registered widgets
		foreach (array_keys($builder->findByTag(self::TAG_WIDGET)) as $serviceName) {
			// Register widget to provider
			$provider->addSetup('registerWidget', array('@' .$serviceName));
		}
	}

	/**
	 * @param \Nette\Configurator $config
	 * @param string $extensionName
	 */
	public static function register(Nette\Configurator $config, $extensionName = 'widgetsManager')
	{
		$config->onCompile[] = function (Configurator $config, Compiler $compiler) use ($extensionName) {
			$compiler->addExtension($extensionName, new WidgetsManagerExtension());
		};
	}
}