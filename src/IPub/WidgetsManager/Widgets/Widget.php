<?php
/**
 * Widget.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:WidgetsManager!
 * @subpackage	Widgets
 * @since		5.0
 *
 * @date		15.09.14
 */

namespace IPub\WidgetsManager\Widgets;

use Nette;
use Nette\Application;
use Nette\ComponentModel;
use Nette\Localization;
use Nette\Security;
use Nette\Utils;

use IPub;
use IPub\WidgetsManager;
use IPub\WidgetsManager\Decorators;
use IPub\WidgetsManager\Entities;

abstract class Widget extends Application\UI\Control implements IWidget
{
	/**
	 * @var WidgetsManager\WidgetsProvider
	 */
	protected $widgetsProvider;

	/**
	 * @var Security\User
	 */
	protected $user;

	/**
	 * @var Localization\ITranslator
	 */
	protected $translator;

	/**
	 * @var Entities\IData
	 */
	protected $data;

	/**
	 * @var Decorators\IDecorator
	 */
	protected $decorator;

	/**
	 * @var Application\UI\ITemplateFactory
	 */
	protected $templateFactory;

	/**
	 * @var WidgetsManager\Providers\ITemplateProvider
	 */
	protected $templateProvider;

	/**
	 * @var Application\UI\ITemplate
	 */
	protected $template;

	/**
	 * @param WidgetsManager\WidgetsProvider $widgetsProvider
	 * @param Security\User $user
	 * @param Application\UI\ITemplateFactory $templateFactory
	 * @param WidgetsManager\Providers\ITemplateProvider $templateProvider
	 * @param Localization\ITranslator $translator
	 * @param ComponentModel\IContainer $parent
	 * @param string $name
	 */
	public function __construct(
		WidgetsManager\WidgetsProvider $widgetsProvider,
		Security\User $user,
		Application\UI\ITemplateFactory $templateFactory,
		WidgetsManager\Providers\ITemplateProvider $templateProvider = NULL,
		Localization\ITranslator $translator = NULL,
		ComponentModel\IContainer $parent = NULL, $name = NULL
	) {
		parent::__construct($parent, $name);

		// Widgets provider service
		$this->widgetsProvider = $widgetsProvider;

		// Application user
		$this->user = $user;

		// Application translator
		$this->translator = $translator;

		// Application template factory
		$this->templateFactory	= $templateFactory;
		// Application template path provider
		$this->templateProvider	= $templateProvider;
		// Create widget template
		$this->template			= $this->templateFactory->createTemplate($this);
	}

	/**
	 * {@inheritdoc}
	 */
	public function setData(Entities\IData $data)
	{
		$this->data = $data;

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		// Widget data must be loaded
		if (!$this->data instanceof Entities\IData) {
			throw new \LogicException('Missing call ' . get_called_class()  . '::setData($entity)');
		}

		return $this->data->getName();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDescription()
	{
		// Widget data must be loaded
		if (!$this->data instanceof Entities\IData) {
			throw new \LogicException('Missing call ' . get_called_class()  . '::setData($entity)');
		}

		return $this->data->getDescription();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPriority()
	{
		// Widget data must be loaded
		if (!$this->data instanceof Entities\IData) {
			throw new \LogicException('Missing call ' . get_called_class()  . '::setData($entity)');
		}

		return $this->data->getPriority();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPosition()
	{
		// Widget data must be loaded
		if (!$this->data instanceof Entities\IData) {
			throw new \LogicException('Missing call ' . get_called_class()  . '::setData($entity)');
		}

		return $this->data->getPosition();
	}

	/**
	 * @return mixed
	 *
	 * @param mixed $data
	 *
	 * @throws \LogicException
	 */
	public function render($data = NULL)
	{
		// Check if data are provided during rendering
		$this->data = $data != NULL ? $this->createData($data) : $this->data;

		// Widget data must be loaded
		if (!$this->data instanceof Entities\IData) {
			throw new \LogicException('Missing call ' . get_called_class()  . '::setData($entity)');
		}

		// Process actions before render
		$this->beforeRender();

		// Render widget
		ob_start();
		$this->template->render();
		$widget = ob_get_clean();

		// Empty current widget data
		$this->data = NULL;

		// Return rendered widget
		return $widget;
	}

	/**
	 * Before render actions
	 */
	protected function beforeRender()
	{
		// Get widget decorator style
		$style	= $this->data->getStyle();
		// Get widget badge
		$badge	= $this->data->getBadge();
		// Get widget icon
		$icon	= $this->data->getIcon();
		// Get widget title
		$name	= $this->data->getName();

		// If widget name has space...
		$pos = mb_strpos($this->data->getName(), ' ');
		if ($pos !== FALSE && mb_strpos($this->data->getName(), '||') === FALSE) {
			$name = Utils\Html::el("span")
				->addAttributes(array('class' => 'color'))
				->setText(mb_substr($this->data->getName(), 0, $pos))
				->render();

			// Modify widget name
			$name = $name . mb_substr($this->data->getName(), $pos);
		}

		// If widget name has subtitle...
		$pos = mb_strpos($this->data->getName(), '||');
		if ($pos !== FALSE) {
			$title = Utils\Html::el("span")
				->addAttributes(array('class' => 'title'))
				->setText(mb_substr($this->data->getName(), 0, $pos))
				->render();

			$subtitle = Utils\Html::el("span")
				->addAttributes(array('class' => 'subtitle'))
				->setText(mb_substr($this->data->getName(), $pos + 2))
				->render();

			// Split name to title & subtitle
			$name = $title . $subtitle;
		}

		// Set badge if exists
		if ($badge) {
			$badge = Utils\Html::el("span")
				->addAttributes(array('class' => 'badge badge-'. $badge))
				->render();
		}

		// Set icon if exists
		if ($icon) {
			$icon = Utils\Html::el("span")
				->addAttributes(array('class' => 'icon icon-'. $icon))
				->render();
		}

		// Check in wich position is this widget displayed...
		if (in_array($this->getPosition(), array('absolute', 'breadcrumbs', 'logo', 'banner', 'search'))) {
			// ... and if the position is special, override decorator style
			$style = 'raw';
			// ... and disable display name
			$this->data->setParam('widget.title.insert', FALSE);
		}

		// Overide decorator for specified widget position
		if ($this->getPosition() == 'menu') {
			$style = $style == 'menu' ? 'raw' : 'dropdown';
		}

		// Set widget template using the style
		switch ($style)
		{
			case 'raw':
				$this->decorator = $this->widgetsProvider->getDecorator('raw');
				break;

			case 'line':
				$this->decorator = $this->widgetsProvider->getDecorator('line');
				break;

			case 'default':
			default:
				$this->decorator = $this->widgetsProvider->getDecorator('panel');
				break;
		}

		// Assign basic widget data to template
		$this->template->badge	= $badge;
		$this->template->icon	= $icon;
		$this->template->title	= [
			'text'		=> $name,
			'insert'	=> $this->data->getParam('widget.title.insert', TRUE),
			'hidden'	=> $this->data->getParam('widget.title.hidden', FALSE)
		];
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
	 * Convert widget name to string representation
	 *
	 * @return string
	 */
	public function __toString()
	{
		$class = explode('\\', get_class($this));

		return end($class);
	}
}