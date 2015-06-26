<?php
/**
 * FilterManager.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Widgets!
 * @subpackage	common
 * @since		5.0
 *
 * @date		26.06.15
 */

namespace IPub\Widgets;

use Nette;
use Nette\Application;

use IPub;
use IPub\Widgets\Exceptions;
use IPub\Widgets\Filter;

class FiltersManager implements \IteratorAggregate
{
	const CLASSNAME = __CLASS__;

	/**
	 * @var string[]
	 */
	protected $filters = [];

	/**
	 * @param Application\Application $application
	 */
	public function __construct(Application\Application $application)
	{
		// Add application to filter iterator
		Filter\FilterIterator::setApplication($application);
	}

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
		return $this->has($name) ? $this->filters[$name] : NULL;
	}

	/**
	 * Register a filter class name
	 *
	 * @param string $name
	 * @param string $filter
	 * @param int $priority
	 *
	 * @throws Exceptions\InvalidArgumentException
	 */
	public function register($name, $filter, $priority = 0)
	{
		if (!is_string($filter) || !is_subclass_of($filter, 'IPub\Widgets\Filter\FilterIterator')) {
			throw new Exceptions\InvalidArgumentException(sprintf('Given filter "%s" is not of type IPub\Widgets\Filter\FilterIterator', (string) $filter));
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