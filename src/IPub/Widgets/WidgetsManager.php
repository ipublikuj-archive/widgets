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

use Nette;
use Nette\Application;
use Nette\Localization;
use Nette\Security;

use IPub\Widgets\Filters;

use IPub\Packages;

class WidgetsManager extends Packages\PackagesManager
{
	const CLASSNAME = __CLASS__;

	/**
	 * @var Widgets\IWidget[]
	 */
	protected $widgets = [];

	/**
	 * @var Application\UI\Presenter
	 */
	protected $presenter;

	/**
	 * @var Security\User
	 */
	protected $user;

	/**
	 * @var Widgets\IFactory[]
	 */
	protected $factories = [];

	/**
	 * @var FiltersManager
	 */
	protected $filters;

	/**
	 * @var DecoratorsManager
	 */
	protected $decorators;

	/**
	 * @param Packages\Repository\IInstalledRepository $repository
	 * @param Packages\Installers\IInstaller $installer
	 * @param Application\Application $application
	 * @param Security\User $user
	 * @param FiltersManager $filters
	 * @param DecoratorsManager $decorators
	 */
	public function __construct(
		Packages\Repository\IInstalledRepository $repository,
		Packages\Installers\IInstaller $installer,
		Application\Application $application,
		Security\User $user,
		FiltersManager $filters = NULL,
		DecoratorsManager $decorators = NULL
	) {
		parent::__construct($repository, $installer);

		// Register filters
		$this->filters = $filters ?: new FiltersManager;

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
	 * @return Widgets\IControl|NULL
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
	public function register(Widgets\IFactory $factory)
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
	 * Get widgets filter manager
	 *
	 * @return FiltersManager
	 */
	public function getFiltersManager()
	{
		return $this->filters;
	}

	/**
	 * Get widgets decorators manager
	 *
	 * @return DecoratorsManager
	 */
	public function getDecoratorManager()
	{
		return $this->decorators;
	}

	/**
	 * Implements the \IteratorAggregate
	 *
	 * @return \Iterator
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this->factories);
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
	 * @return Packages\Entities\IPackage|NULL
	 */
	public function offsetGet($offset)
	{
		return $this->factories[$offset];
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
		$this->factories[$offset] = $value;

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
		unset($this->factories[$offset]);

		return $this;
	}
}