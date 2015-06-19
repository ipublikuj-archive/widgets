<?php
/**
 * Control.php
 *
 * @copyright	Vice v copyright.php
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Widgets!
 * @subpackage	Components
 * @since		5.0
 *
 * @date		24.07.13
 */

namespace IPub\Widgets\Components;

use Nette;
use Nette\Application;
use Nette\ComponentModel;
use Nette\Utils;

use IPub;
use IPub\Widgets\Decorators;
use IPub\Widgets\Entities;
use IPub\Widgets\Exceptions;
use IPub\Widgets\Widgets;

use IPub\Widgets\WidgetsManager;
use IPub\Widgets\DecoratorsManager;

/**
 * @method onAttached(Nette\Application\UI\Control $component)
 */
class Control extends Application\UI\Control
{
	const CLASSNAME = __CLASS__;

	/**
	 * @var array
	 */
	public $onAttached = [];

	/**
	 * @var WidgetsManager
	 */
	protected $widgetsManager;

	/**
	 * @var DecoratorsManager
	 */
	protected $decoratorsManager;

	/**
	 * @var string
	 */
	protected $position;

	/**
	 * @var Decorators\IFactory
	 */
	protected $decorator;

	/**
	 * @param string $position
	 * @param WidgetsManager $widgetsManager
	 * @param DecoratorsManager $decoratorsManager
	 * @param ComponentModel\IContainer $parent
	 * @param null $name
	 */
	public function __construct(
		$position,
		WidgetsManager $widgetsManager,
		DecoratorsManager $decoratorsManager,
		ComponentModel\IContainer $parent = NULL, $name = NULL
	) {
		parent::__construct($parent, $name);

		// Store info about widgets position
		$this->position = $position;

		// Extension managers
		$this->widgetsManager = $widgetsManager;
		$this->decoratorsManager = $decoratorsManager;
	}

	/**
	 * Attach component to presenter
	 *
	 * @param Application\UI\Presenter $presenter
	 */
	protected function attached($presenter)
	{
		parent::attached($presenter);

		if (!$presenter instanceof Application\UI\Presenter) return;

		// Register widgets container
		$this->addComponent(new ComponentModel\Container(), 'widgets');

		// Register default raw widget decorator
		$this->setDecorator($this->decoratorsManager->get('raw'));

		// Call attached event
		$this->onAttached($this);
	}

	/**
	 * Set widgets outer decorator
	 *
	 * @param Decorators\IFactory $decorator
	 *
	 * @return $this
	 */
	public function setDecorator(Decorators\IFactory $decorator)
	{
		// Register decorator component
		$this->addComponent($decorator->create(), 'decorator');

		return $this;
	}

	/**
	 * Get all registered widgets in position
	 *
	 * @return array
	 */
	public function getWidgets()
	{
		if ($container = $this->getComponent('widgets')->getComponent($this->position, TRUE) AND $widgets = $container->getComponents()) {
			return $widgets;
		}

		return [];
	}

	/**
	 * Add widget to container
	 *
	 * @param string $name
	 * @param array $data
	 *
	 * @return $this
	 *
	 * @throws Exceptions\WidgetNotRegisteredException
	 */
	public function addWidget($name, array $data = [])
	{
		// Prepare widget settings data
		$data = $this->createData($data);

		if (!$factory = $this->widgetsManager->get($name)) {
			throw new Exceptions\WidgetNotRegisteredException(sprintf('Widget of type %s is not registered.', $name));
		}

		// Check container exist
		$container = $this->getComponent('widgets')->getComponent($this->position, FALSE);
		if (!$container) {
			$this->getComponent('widgets')->addComponent(new Nette\ComponentModel\Container, $this->position);
			$container = $this->getComponent('widgets')->getComponent($this->position);
		}

		// Create component
		$widget = $factory->create($data);

		// Add widget component to container/position
		$container->addComponent($widget, ($widget->getName() . spl_object_hash($data)));

		return $this;
	}

	/**
	 * Render widgets in selected position
	 */
	public function render()
	{
		$template = $this->template;
		$template->setFile(__DIR__ . '/template/default.latte');

		$template->render();
	}

	/**
	 * Create widget decorator component
	 *
	 * @return Decorators\IDecorator
	 */
	protected function createComponentDecorator()
	{
		return $this->decorator->create();
	}

	/**
	 * Convert data to object
	 *
	 * @param mixed $data
	 *
	 * @return Entities\Data|null
	 */
	protected function createData($data)
	{
		// Data are in required object
		if ($data instanceof Entities\IData) {
			return $data;

			// or data are in array
		} else if (is_array($data)) {
			// Create new data object
			return (new Entities\Data($data));
		}

		return NULL;
	}

	/**
	 * @param string $name
	 * @param mixed $args
	 *
	 * @return mixed
	 */
	public function __call($name, $args)
	{
		if (Utils\Strings::startsWith($name, 'render')) {
			// Get component name
			if ($decorator = Utils\Strings::capitalize(Utils\Strings::substring($name, 6))) {
				// Set template name
				//$this->setDecorator($this->decoratorsManager->get($decorator));
			}

			// Call component rendering
			$this->render();

		} else {
			return parent::__call($name, $args);
		}
	}
}