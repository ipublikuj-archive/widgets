<?php
/**
 * FiltersManager.php
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

class FiltersManager implements \IteratorAggregate
{
	const CLASSNAME = __CLASS__;

	/**
	 * @var string[]
	 */
	protected $filters = [];

	/**
	 * Check if a filter is registered
	 *
	 * @param string $name
	 *
	 * @return boolean
	 */
	public function has($name)
	{
		return isset($this->filters[$name]);
	}

	/**
	 * Returns a registered filter class
	 *
	 * @param string $name
	 *
	 * @return string
	 */
	public function get($name)
	{
		return $this->has($name) ? $this->filters[$name] : null;
	}

	/**
	 * Register a filter class name
	 *
	 * @param string $name
	 * @param string $filter
	 * @param int $priority
	 *
	 * @throws \InvalidArgumentException
	 */
	public function register($name, $filter, $priority = 0)
	{
		if (!is_string($filter) || !is_subclass_of($filter, 'IPub\Widgets\Filters\FilterIterator')) {
			throw new \InvalidArgumentException(sprintf('Given filter "%s" is not of type IPub\Widgets\Filters\FilterIterator', (string) $filter));
		}

		$this->unregister($name);

		$this->filters[$priority][$name] = $filter;

		krsort($this->filters);
	}

	/**
	 * Unregisters a filter
	 *
	 * @param string $name
	 */
	public function unregister($name)
	{
		foreach($this->filters as $priority => $filters) {
			if (array_key_exists($name, $filters)) {
				unset($this->filters[$priority][$name]);
			}
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this->filters);
	}
}