<?php
/**
 * Control.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Widgets!
 * @subpackage	Decorators
 * @since		5.0
 *
 * @date		18.06.15
 */

namespace IPub\Widgets\Decorators\Raw;

use Nette;
use Nette\Application;
use Nette\Localization;

use IPub;
use IPub\Widgets;
use IPub\Widgets\Decorators;
use IPub\Widgets\Exceptions;

/**
 * Widgets decorator control definition
 *
 * @package		iPublikuj:Widgets!
 * @subpackage	Decorators
 *
 * @property-read Application\UI\ITemplate $template
 */
class Control extends Widgets\Application\UI\BaseControl implements Decorators\IDecorator
{
	const CLASSNAME = __CLASS__;

	/**
	 * Render widget with decorator
	 *
	 * @param Widgets\Widgets\IControl $widget
	 */
	public function render(Widgets\Widgets\IControl $widget)
	{
		// Check if control has template
		if ($this->template instanceof Nette\Bridges\ApplicationLatte\Template) {
			// Assign vars to template
			$this->template->widget = $widget;

			// Check if translator is available
			if ($this->getTranslator() instanceof Localization\ITranslator) {
				$this->template->setTranslator($this->getTranslator());
			}

			// If template was not defined before...
			if ($this->template->getFile() === NULL) {
				// ...try to get base component template file
				$templatePath = !empty($this->templatePath) ? $this->templatePath : __DIR__ . DIRECTORY_SEPARATOR .'template'. DIRECTORY_SEPARATOR .'default.latte';
				$this->template->setFile($templatePath);
			}

			// Render component template
			$this->template->render();

		} else {
			throw new Exceptions\InvalidStateException('Widgets decorator control is without template.');
		}
	}
}