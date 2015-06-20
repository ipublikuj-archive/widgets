<?php
/**
 * WidgetsManager.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Widgets!
 * @subpackage	common
 * @since		5.0
 *
 * @date		16.09.14
 */

namespace IPub\Widgets;

use IPub\Packages;

class WidgetsManager extends Packages\PackagesManager
{
	const CLASSNAME = __CLASS__;

	/**
	 * @var Widgets\IFactory[]
	 */
	protected $widgets = [
		'default' => []
	];

	/**
	 * @param Packages\Repository\IInstalledRepository $repository
	 * @param Packages\Installers\IInstaller $installer
	 */
	public function __construct(
		Packages\Repository\IInstalledRepository $repository,
		Packages\Installers\IInstaller $installer
	) {
		parent::__construct($repository, $installer);
	}

	/**
	 * Checks whether a widget is registered
	 *
	 * @param string $type
	 * @param string $group
	 *
	 * @return bool
	 */
	public function has($type, $group = 'default')
	{
		return isset($this->widgets[(string) $group]) && isset($this->widgets[(string) $group][(string) $type]);
	}

	/**
	 * Gets a widget
	 *
	 * @param string $type
	 * @param string $group
	 *
	 * @return Widgets\IFactory|NULL
	 */
	public function get($type, $group = 'default')
	{
		// Only lower case chars are allowed
		$type = strtolower($type);

		// Check if widget exists or not...
		if ($this->has($type, $group)) {
			return $this->widgets[$group][$type];
		}

		return NULL;
	}

	/**
	 * Helper method for widgets factories registering
	 *
	 * @param Widgets\IFactory $factory
	 * @param string $group
	 *
	 * @return $this
	 */
	public function register(Widgets\IFactory $factory, $group = 'default')
	{
		// Get widget type
		$type = $factory::WIDGET_TYPE;

		// Check if widget is already registered
		if (!$this->has($type, $group)) {
			$this->widgets[$group][$type] = $factory;
		}

		return $this;
	}

	/**
	 * Implements the \IteratorAggregate
	 *
	 * @return \Iterator
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this->widgets);
	}

	/**
	 * Implements the \ArrayAccess
	 *
	 * @param  string $offset
	 *
	 * @return bool
	 */
	public function offsetExists($offset)
	{
		return isset($this->widgets[$offset]);
	}

	/**
	 * Implements the \ArrayAccess
	 *
	 * @param string $offset
	 *
	 * @return Widgets\IFactory
	 */
	public function offsetGet($offset)
	{
		return $this->widgets[$offset];
	}

	/**
	 * Implements the \ArrayAccess
	 *
	 * @param string $offset
	 * @param Packages\Entities\IPackage $value
	 *
	 * @return $this
	 */
	public function offsetSet($offset, $value)
	{
		$this->widgets[$offset] = $value;

		return $this;
	}

	/**
	 * Implements the \ArrayAccess
	 *
	 * @param string $offset
	 *
	 * @return $this
	 */
	public function offsetUnset($offset)
	{
		unset($this->widgets[$offset]);

		return $this;
	}
}