<?php
/**
 * Widget.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Widgets!
 * @subpackage	Widgets
 * @since		5.0
 *
 * @date		15.09.14
 */

namespace IPub\Widgets\Widgets;

use Nette;
use Nette\Application;
use Nette\ComponentModel;
use Nette\Localization;
use Nette\Security;
use Nette\Utils;

use IPub;
use IPub\Widgets;
use IPub\Widgets\Decorators;
use IPub\Widgets\Entities;

abstract class Control extends Application\UI\Control implements IControl
{
	const CLASSNAME = __CLASS__;

	/**
	 * @var Entities\IData
	 */
	protected $data;

	/**
	 * @param Entities\IData $data
	 * @param ComponentModel\IContainer $parent
	 * @param string $name
	 */
	public function __construct(
		Entities\IData $data,
		ComponentModel\IContainer $parent = NULL, $name = NULL
	) {
		parent::__construct($parent, $name);

		$this->data = $data;
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
			throw new \LogicException('Missing call ' . get_called_class() . '::setData($entity)');
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
			throw new \LogicException('Missing call ' . get_called_class() . '::setData($entity)');
		}

		return $this->data->getDescription();
	}

	public function render()
	{
		// Process actions before render
		$this->beforeRender();

		$this->template->render();
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