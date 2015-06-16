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

class DecoratorsManager implements \IteratorAggregate
{
	const CLASSNAME = __CLASS__;

	/**
	 * @var string[]
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
	 * @return string
	 */
	public function get($name)
	{
		return $this->has($name) ? $this->decorators[$name] : null;
	}

	/**
	 * Register a decorator class name
	 *
	 * @param string $name
	 * @param string $decorator
	 *
	 * @throws \InvalidArgumentException
	 */
	public function register($name, $decorator)
	{
		if (!is_string($decorator) || !is_subclass_of($decorator, 'IPub\Widgets\Decorators\DecoratorIterator')) {
			throw new \InvalidArgumentException(sprintf('Given decorator "%s" is not of type IPub\Widgets\Decorators\DecoratorIterator', (string) $decorator));
		}

		$this->unregister($name);

		$this->decorators[$name] = $decorator;

		krsort($this->decorators);
	}

	/**
	 * Unregisters a decorator
	 *
	 * @param string $name
	 */
	public function unregister($name)
	{
		if (array_key_exists($name, $this->decorators)) {
			unset($this->decorators[$name]);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this->decorators);
	}
}