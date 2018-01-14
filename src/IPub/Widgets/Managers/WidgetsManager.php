<?php
/**
 * WidgetsManager.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Widgets!
 * @subpackage     Managers
 * @since          1.0.0
 *
 * @date           16.09.14
 */

declare(strict_types = 1);

namespace IPub\Widgets\Managers;

use Nette;

use IPub;
use IPub\Widgets\Widgets;
use Tracy\Debugger;

/**
 * Registered widgets manager
 *
 * @package        iPublikuj:Widgets!
 * @subpackage     Managers
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
final class WidgetsManager extends Nette\Object implements \ArrayAccess, \IteratorAggregate
{
	/**
	 * @var Widgets\IFactory[][]
	 */
	private $widgets = [
		'default' => []
	];

	/**
	 * Checks whether a widget is registered
	 *
	 * @param string $type
	 * @param string $group
	 *
	 * @return bool
	 */
	public function has(string $type, string $group = 'default'): bool
	{
		return isset($this->widgets[$group]) && isset($this->widgets[$group][$type]);
	}

	/**
	 * Gets a widget
	 *
	 * @param string $type
	 * @param string $group
	 *
	 * @return Widgets\IFactory|NULL
	 */
	public function get(string $type, string $group = 'default')
	{
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
	 * @param string $type
	 * @param string $group
	 */
	public function register(Widgets\IFactory $factory, string $type, string $group = 'default')
	{
		// Check if widget is already registered
		if (!$this->has($type, $group)) {
			$this->widgets[$group][$type] = $factory;
		}
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
	 * @return Widgets\IFactory[]
	 */
	public function offsetGet($offset)
	{
		return $this->widgets[$offset];
	}

	/**
	 * Implements the \ArrayAccess
	 *
	 * @param string $offset
	 * @param Widgets\IFactory[]|NULL $value
	 */
	public function offsetSet($offset, $value)
	{
		$this->widgets[$offset] = $value;
	}

	/**
	 * Implements the \ArrayAccess
	 *
	 * @param string $offset
	 */
	public function offsetUnset($offset)
	{
		unset($this->widgets[$offset]);
	}
}
