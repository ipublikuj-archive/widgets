<?php
/**
 * FilterManager.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:Widgets!
 * @subpackage     Managers
 * @since          1.0.0
 *
 * @date           26.06.15
 */

declare(strict_types = 1);

namespace IPub\Widgets\Managers;

use Nette;

use IPub\Widgets\Exceptions;
use IPub\Widgets\Filters;

/**
 * Registered widgets filters manager
 *
 * @package        iPublikuj:Widgets!
 * @subpackage     Managers
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
final class FiltersManager implements \ArrayAccess, \IteratorAggregate
{
	/**
	 * Implement nette smart magic
	 */
	use Nette\SmartObject;

	/**
	 * @var \SplPriorityQueue
	 */
	private $filters = [];

	/**
	 * @var Filters\IFactory[]
	 */
	private $factories = [];

	public function __construct()
	{
		$this->filters = new \SplPriorityQueue();
	}

	/**
	 * Check if a filter is registered
	 *
	 * @param string $name
	 *
	 * @return boolean
	 */
	public function has(string $name) : bool
	{
		return isset($this->factories[$name]);
	}

	/**
	 * Returns a registered filter class
	 *
	 * @param string $name
	 *
	 * @return Filters\IFactory|NULL
	 */
	public function get(string $name) : ?Filters\IFactory
	{
		return $this->has($name) ? $this->factories[$name] : NULL;
	}

	/**
	 * Register a filter class name
	 *
	 * @param Filters\IFactory $filter
	 * @param string $name
	 * @param int $priority
	 *
	 * @return void
	 */
	public function register(Filters\IFactory $filter, string $name, int $priority = 0) : void
	{
		$this->unregister($name);

		$this->filters->insert($name, $priority);
		$this->factories[$name] = $filter;
	}

	/**
	 * Unregisters a filter
	 *
	 * @param string $name
	 *
	 * @return void
	 */
	public function unregister(string $name) : void
	{
		if ($this->has($name)) {
			unset($this->factories[$name]);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getIterator()
	{
		$filters = [];

		$this->filters->rewind();

		while ($this->filters->valid()) {
			$filter = $this->filters->current();

			if ($this->has($filter)) {
				$filters[$filter] = $this->get($filter);
			}

			$this->filters->next();
		}

		return new \ArrayIterator($filters);
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
		return isset($this->factories[$offset]);
	}

	/**
	 * Implements the \ArrayAccess
	 *
	 * @param string $offset
	 *
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		return $this->factories[$offset];
	}

	/**
	 * Implements the \ArrayAccess
	 *
	 * @param string $offset
	 * @param string[]|NULL $value
	 *
	 * @throws Exceptions\InvalidStateException
	 */
	public function offsetSet($offset, $value)
	{
		throw new Exceptions\InvalidStateException('Use "register" method for adding item.');
	}

	/**
	 * Implements the \ArrayAccess
	 *
	 * @param string $offset
	 *
	 * @throws Exceptions\InvalidStateException
	 */
	public function offsetUnset($offset)
	{
		throw new Exceptions\InvalidStateException('Use "unregister" method for adding item.');
	}
}
