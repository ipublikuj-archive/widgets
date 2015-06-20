<?php
/**
 * WidgetExtension.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Widgets!
 * @subpackage	common
 * @since		5.0
 *
 * @date		16.06.15
 */

namespace IPub\Widgets;

use Nette\DI;
use IPub;

class WidgetExtension extends DI\CompilerExtension
{
	/**
	 * @var Entities\IWidget
	 */
	protected $package;

	/**
	 * @var array
	 */
	protected $definition = [];

	/**
	 * @param Entities\IWidget $package
	 * @param array $definition
	 */
	public function __construct(Entities\IWidget $package, array $definition = [])
	{
		$this->package = $package;
		$this->definition = $definition;
	}

	public function loadConfiguration()
	{
		// Get container builder
		$builder = $this->getContainerBuilder();
		// Get extension configuration
		$configuration = $this->getConfig();

		// Load module configuration file & register services
		$this->compiler->parseServices($builder, $configuration, $this->name);

		// Set widget default configuration
		if (isset($configuration['defaults'])) {
			$this->package->setConfig($configuration['defaults']);
		}
	}

	/**
	 * Returns extension configuration
	 *
	 * @param array $defaults
	 *
	 * @return array
	 */
	public function getConfig(array $defaults = [])
	{
		// Merge extension configuration
		$defaults = $this->getContainerBuilder()->expand($this->definition);

		$config = $this->compiler->getConfig();
		$config = [
			'defaults' => (isset($config[$this->name]) ? $config[$this->name] : []),
		];

		unset($config['services'], $config['factories']);

		return DI\Config\Helpers::merge($config, $this->compiler->getContainerBuilder()->expand($defaults));
	}

	/**
	 * @return Entities\IWidget
	 */
	public function getPackage()
	{
		return $this->package;
	}
}