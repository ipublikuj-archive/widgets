<?php
/**
 * DecoratorsManager.php
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

use IPub;
use IPub\Widgets\Decorators;

class DecoratorsManager implements \IteratorAggregate
{
	const CLASSNAME = __CLASS__;

	/**
	 * @var Decorators\IFactory[]
	 */
	protected $decorators = [];

	/**
	 * Check if a decorator is registered
	 *
	 * @param string $name
	 *
	 * @return boolean
	 */
	public function has($name)
	{
		return isset($this->decorators[$name]);
	}

	/**
	 * Returns a registered decorator class
	 *
	 * @param string $name
	 *
	 * @return Decorators\IFactory|null
	 */
	public function get($name)
	{
		return $this->has($name) ? $this->decorators[$name] : NULL;
	}

	/**
	 * Register a decorator component factory
	 *
	 * @param Decorators\IFactory $decorator
	 *
	 * @return $this
	 */
	public function register(Decorators\IFactory $decorator)
	{
		// Get widget type
		$name = $decorator::DECORATOR_NAME;

		$this->unregister($name);

		$this->decorators[$name] = $decorator;

		krsort($this->decorators);

		return $this;
	}

	/**
	 * Un-registers a decorator
	 *
	 * @param string $name
	 *
	 * @return $this
	 */
	public function unregister($name)
	{
		if (array_key_exists($name, $this->decorators)) {
			unset($this->decorators[$name]);
		}

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this->decorators);
	}
}