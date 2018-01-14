<?php
/**
 * Control.php
 *
 * @copyright      Vice v copyright.php
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Widgets!
 * @subpackage     Components
 * @since          1.0.0
 *
 * @date           24.07.13
 */

declare(strict_types = 1);

namespace IPub\Widgets\Components;

use Nette;
use Nette\Application;
use Nette\ComponentModel;
use Nette\Localization;
use Nette\Utils;

use IPub;
use IPub\Widgets\Decorators;
use IPub\Widgets\Entities;
use IPub\Widgets\Exceptions;
use IPub\Widgets\Managers;
use IPub\Widgets\Widgets;

/**
 * Widgets container control definition
 *
 * @package        iPublikuj:Widgets!
 * @subpackage     Components
 *                 
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 *
 * @method onAttached(Application\UI\Control $component)
 *
 * @property Application\UI\ITemplate $template
 */
class Control extends IPub\Widgets\Application\UI\BaseControl
{
	/**
	 * @var array
	 */
	public $onAttached = [];

	/**
	 * @var Managers\WidgetsManager
	 */
	protected $widgetsManager;

	/**
	 * @var Managers\DecoratorsManager
	 */
	protected $decoratorsManager;

	/**
	 * @var Managers\FiltersManager
	 */
	protected $filtersManager;

	/**
	 * @var string
	 */
	protected $position;

	/**
	 * @var Decorators\IFactory
	 */
	protected $decorator;

	/**
	 * @var string
	 */
	protected $group = 'default';

	/**
	 * @param string $position
	 * @param Managers\WidgetsManager $widgetsManager
	 * @param Managers\DecoratorsManager $decoratorsManager
	 * @param Managers\FiltersManager $filtersManager
	 * @param ComponentModel\IContainer $parent
	 * @param string|NULL $name
	 */
	public function __construct(
		string $position = 'default',
		Managers\WidgetsManager $widgetsManager,
		Managers\DecoratorsManager $decoratorsManager,
		Managers\FiltersManager $filtersManager,
		ComponentModel\IContainer $parent = NULL,
		string $name = NULL
	) {
		parent::__construct($parent, $name);

		// Store info about widgets position
		$this->position = $position;

		// Extension managers
		$this->widgetsManager = $widgetsManager;
		$this->decoratorsManager = $decoratorsManager;
		$this->filtersManager = $filtersManager;

		// Register widgets container
		$this->addComponent(new ComponentModel\Container(), 'widgets');
	}

	/**
	 * Attach component to presenter
	 *
	 * @param Application\UI\Presenter $presenter
	 *
	 * @throws Exceptions\DecoratorNotRegisteredException
	 */
	protected function attached($presenter)
	{
		parent::attached($presenter);

		if (!$presenter instanceof Application\UI\Presenter) {
			return;
		}

		// Register default raw widget decorator
		$this->setDecorator('widgets.decorator.raw');

		// Call attached event
		$this->onAttached($this);
	}

	/**
	 * Render widgets in selected position
	 */
	public function render()
	{
		foreach (func_get_args() as $arg) {
			// Check if decorator name is provided
			if (is_string($arg)) {
				$this->setDecorator($arg);

			} elseif (is_array($arg)) {
				if (array_key_exists('group', $arg)) {
					$this->setGroup($arg['group']);

				} elseif (array_key_exists('decorator', $arg)) {
					$this->setDecorator($arg['decorator']);
				}
			}
		}

		// Check if control has template
		if ($this->template instanceof Nette\Bridges\ApplicationLatte\Template) {
			// Assign vars to template
			$this->template->add('widgets', $this->getWidgets());

			// Check if translator is available
			if ($this->getTranslator() instanceof Localization\ITranslator) {
				$this->template->setTranslator($this->getTranslator());
			}

			// If template was not defined before...
			if ($this->template->getFile() === NULL) {
				// Get component actual dir
				$dir = dirname($this->getReflection()->getFileName());

				// ...try to get base component template file
				$templateFile = $this->templateFile !== NULL && is_file($this->templateFile) ? $this->templateFile : $dir . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . 'default.latte';
				$this->template->setFile($templateFile);
			}

			// Render component template
			$this->template->render();

		} else {
			throw new Exceptions\InvalidStateException('Widgets container control is without template.');
		}
	}

	/**
	 * Set widgets outer decorator
	 *
	 * @param string $decorator
	 *
	 * @throws Exceptions\DecoratorNotRegisteredException
	 */
	public function setDecorator(string $decorator)
	{
		// Try to find decorator factory
		if ($factory = $this->decoratorsManager->get($decorator)) {
			// Check if some decorator is registered...
			if ($component = $this->getComponent('decorator', FALSE)) {
				// ... if yes, remove it
				$this->removeComponent($component);
			}

			// Register decorator component
			$this->addComponent($factory->create(), 'decorator');

		} else {
			throw new Exceptions\DecoratorNotRegisteredException(sprintf('Widgets decorator: "%s" is not registered.', $decorator));
		}
	}

	/**
	 * Set widgets group
	 *
	 * @param string $group
	 */
	public function setGroup(string $group)
	{
		$this->group = $group;
	}

	/**
	 * Get widgets group
	 *
	 * @return string
	 */
	public function getGroup() : string
	{
		return $this->group;
	}

	/**
	 * Get all registered widgets in position
	 *
	 * @return array
	 */
	public function getWidgets()
	{
		if (
			($container = $this->getComponent('widgets')->getComponent($this->group, FALSE))
			&& ($positionContainer = $container->getComponent($this->position, FALSE))
			&& ($widgets = $positionContainer->getComponents())
		) {
			// Apply widgets filters
			foreach ($this->filtersManager as $filter) {
					$widgets = $filter->create($widgets, [
						'access' => TRUE,
						'active' => TRUE,
						'status' => 1,
					]);
			}

			return $widgets;
		}

		return [];
	}

	/**
	 * Add widget to container
	 *
	 * @param string|Widgets\IFactory $name
	 * @param array $data
	 * @param string|NULL $group
	 * @param string|NULL $position
	 *
	 * @return Widgets\IWidget
	 *
	 * @throws Exceptions\WidgetNotRegisteredException
	 * @throws Exceptions\InvalidStateException
	 */
	public function addWidget($name, array $data = [], string $group = NULL, string $position = NULL) : Widgets\IWidget
	{
		if ($position === NULL) {
			$position = $this->position;
		}

		if ($group === NULL) {
			$group = $this->group;
		}

		// Prepare widget settings data
		$data = $this->createData($data);

		if (is_string($name)) {
			if (!$factory = $this->widgetsManager->get($name, $group)) {
				throw new Exceptions\WidgetNotRegisteredException(sprintf('Widget of type "%s" in group "%s" is not registered.', $name, $group));
			}

		} elseif (!$name instanceof Widgets\IFactory) {
			throw new Exceptions\InvalidArgumentException(sprintf('Provided service is not valid widgets factory service. Instance of IPub\Widgets\Widgets\IFactory expected, instance of %s provided', get_class($name)));

		} else {
			$factory = $name;
		}

		// Check container exist
		$container = $this->getComponent('widgets')->getComponent($group, FALSE);

		if (!$container) {
			$this->getComponent('widgets')->addComponent(new Nette\ComponentModel\Container, $group);
			$container = $this->getComponent('widgets')->getComponent($group);
		}

		// Check container exist
		$positionContainer = $container->getComponent($position, FALSE);

		if (!$positionContainer) {
			$container->addComponent(new Nette\ComponentModel\Container, $position);
			$positionContainer = $container->getComponent($position);
		}

		// Create component
		$widget = $factory->create($data);

		// Add widget component to container/position
		$positionContainer->addComponent($widget, ($widget->getName() . spl_object_hash($data)));

		return $widget;
	}

	/**
	 * Convert data to object
	 *
	 * @param mixed $data
	 *
	 * @return Entities\IData
	 *
	 * @throws Exceptions\InvalidStateException
	 */
	private function createData($data) : Entities\IData
	{
		// Data are in required object
		if ($data instanceof Entities\IData) {
			return $data;

		// or data are in array
		} elseif (is_array($data)) {
			// Create new data object
			return (new Entities\Data($data));

		// or data are in ArrayHash object
		} elseif ($data instanceof Utils\ArrayHash) {
			// Create new data object
			return (new Entities\Data((array) $data));
		}

		throw new Exceptions\InvalidStateException('Widget data could not be converted to data entity.');
	}

	/**
	 * @param string $name
	 * @param mixed $args
	 *
	 * @return mixed
	 *
	 * @throws Exceptions\DecoratorNotRegisteredException
	 */
	public function __call($name, $args)
	{
		if (Utils\Strings::startsWith($name, 'render')) {
			// Get decorator name
			if ($decoratorName = Utils\Strings::capitalize(Utils\Strings::substring($name, 6))) {
				// Set widget decorator
				$this->setDecorator($decoratorName);
			}

			// Call component rendering
			$this->render();

		} else {
			return parent::__call($name, $args);
		}
	}
}
