<?php
/**
 * WidgetsProvider.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:WidgetsManager!
 * @subpackage	common
 * @since		5.0
 *
 * @date		16.09.14
 */

namespace IPub\WidgetsManager;

use Nette;
use Nette\Application;
use Nette\Localization;
use Nette\Security;

use IPub\WidgetsManager\Filters;

class WidgetsProvider extends Nette\Object implements \IteratorAggregate, \ArrayAccess
{
	/**
	 * @var Application\UI\Presenter
	 */
	protected $presenter;

	/**
	 * @var Security\User
	 */
	protected $user;

	/**
	 * @var Widgets\IWidget[]
	 */
	protected $widgets = [];

	/**
	 * @var Widgets\IFactory[]
	 */
	protected $factories = [];

	/**
	 * @var FilterManager
	 */
	protected $filters;

	/**
	 * @param Application\Application $application
	 * @param Security\User $user
	 * @param FilterManager $filters
	 */
	public function __construct(
		Application\Application $application,
		Security\User $user,
		FilterManager $filters = NULL
	) {
		// Register filters
		$this->filters = $filters ?: new FilterManager;

		// Application actual presenter
		$this->presenter = $application->getPresenter();

		// Application user
		$this->user = $user;

		// Add application to filter iterator
		Filters\FilterIterator::setApplication($application);
	}

	/**
	 * Checks whether a widget is registered
	 *
	 * @param string $type
	 *
	 * @return bool
	 */
	public function has($type)
	{
		return isset($this->factories[(string) $type]);
	}

	/**
	 * Gets a widget
	 *
	 * @param  string $type
	 *
	 * @return Widgets\IWidget|null
	 */
	public function get($type)
	{
		// Only lower case chars are allowed
		$type = strtolower($type);

		// Check if widget exists or not...
		if ($this->has($type)) {
			return $this->factories[$type]->create();
		}

		return NULL;
	}

	/**
	 * Helper method for widgets factories registering
	 *
	 * @param Widgets\IFactory $factory
	 *
	 * @return $this
	 */
	public function registerWidget(Widgets\IFactory $factory)
	{
		// Get widget type
		$type = $factory::WIDGET_TYPE;

		// Check if widget is already registered
		if ($this->has($type)) {


		// If not, register new widget
		} else {
			$this->factories[$type] = $factory;
		}

		return $this;
	}

	/**
	 * {@see FilterManager::register}
	 */
	public function registerFilter($name, $filter, $priority = 0)
	{
		$this->filters->register($name, $filter, $priority);
	}

	/**
	 * Get widgets filter manager
	 *
	 * @return FilterManager
	 */
	public function getFilterManager()
	{
		return $this->filters;
	}

	/**
	 * Get widgets decorator
	 *
	 * @param string $decorator
	 *
	 * @return Decorators\IDecorator
	 */
	public function getDecorator($decorator)
	{
		return $decorator;
	}

	/**
	 * Implements the IteratorAggregate.
	 *
	 * @return \ArrayIterator
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this->factories);
	}

	/**
	 * Whether an application parameter or an object exists
	 *
	 * @param  string $offset
	 * @return mixed
	 */
	public function offsetExists($offset)
	{
		return isset($this->factories[$offset]);
	}

	/**
	 * Gets an application parameter or an object
	 *
	 * @param  string $offset
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		return $this->factories[$offset];
	}

	/**
	 * Sets an application parameter or an object
	 *
	 * @param  string $offset
	 * @param  mixed  $value
	 */
	public function offsetSet($offset, $value)
	{
		$this->factories[$offset] = $value;
	}

	/**
	 * Unsets an application parameter or an object
	 *
	 * @param  string $offset
	 */
	public function offsetUnset($offset)
	{
		unset($this->factories[$offset]);
	}
}