<?php
/**
 * WidgetManager.php
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

class WidgetManager extends Application\UI\Control
{
	const CLASSNAME = __CLASS__;

	/**
	 * @var array
	 */
	protected $widgets = array();

	/**
	 * Attach component to presenter
	 *
	 * @param Application\UI\Presenter $presenter
	 */
	protected function attached($presenter)
	{
		parent::attached($presenter);

		if (!$presenter instanceof Application\UI\Presenter) return;

		$this->addComponent(new ComponentModel\Container(), "widgetManager");
	}

	public function loadWidgets()
	{
		// Get all availible widgets
		$this->widgets = $this->designWidgetModel->getItemsForDisplay($this->roles, $this->languageEntity, $this->position);
	}

	protected function createComponent($name)
	{
		if ( !isset($this->widgets[$name]) ) {
			return parent::createComponent($name);
		}

		// Get widget entity
		$widgetEntity = $this->widgets[$name];

		// Create widget
		$widget = new $widgetEntity->class;

		// Widget is created
		if ( is_object($widget) ) {
			// Set widget entity
			$widget->setWidget($widgetEntity);
			// Set language
			$widget->setLanguage($this->languageEntity);
			// Activate inject methods
			$this->presenter->getContext()->callInjects($widget); //tohle neni moc hezky, lepsi by byly nejaky tovarnicky :)

			return $widget;
		}

		return parent::createComponent($name);
	}
}