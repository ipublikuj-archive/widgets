<?php
/**
 * WidgetsManager.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Widgets!
 * @subpackage     common
 * @since          1.0.0
 *
 * @date           16.09.14
 */

namespace IPub\Widgets;

use Nette;

use IPub;
use IPub\Widgets\Widgets;

final class WidgetsManager extends Nette\Object implements \ArrayAccess, \IteratorAggregate
{
	const CLASSNAME = __CLASS__;

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
	 *
	 * @return $this
	 */
	public function register(Widgets\IFactory $factory, $type, $group = 'default')
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
