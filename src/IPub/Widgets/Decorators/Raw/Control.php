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
class Control extends Application\UI\Control implements Decorators\IDecorator
{
	const CLASSNAME = __CLASS__;

	/**
	 * @var string
	 */
	protected $templatePath;

	/**
	 * @var Localization\ITranslator
	 */
	protected $translator;

	/**
	 * @param Localization\ITranslator $translator
	 */
	public function injectTranslator(Localization\ITranslator $translator = NULL)
	{
		$this->translator = $translator;
	}

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

	/**
	 * Change default control template path
	 *
	 * @param string $templatePath
	 *
	 * @return $this
	 *
	 * @throws Exceptions\FileNotFoundException
	 */
	public function setTemplateFile($templatePath)
	{
		// Check if template file exists...
		if (!is_file($templatePath)) {
			// Remove extension
			$template = basename($templatePath, '.latte');

			// ...check if extension template is used
			if (is_file(__DIR__ . DIRECTORY_SEPARATOR .'template'. DIRECTORY_SEPARATOR . $template .'.latte')) {
				$templatePath = __DIR__ . DIRECTORY_SEPARATOR .'template'. DIRECTORY_SEPARATOR . $template .'.latte';

			} else {
				// ...if not throw exception
				throw new Exceptions\FileNotFoundException(sprintf('Template file "%s" was not found.', $templatePath));
			}
		}

		$this->templatePath = $templatePath;

		return $this;
	}

	/**
	 * @param Localization\ITranslator $translator
	 *
	 * @return $this
	 */
	public function setTranslator(Localization\ITranslator $translator)
	{
		$this->translator = $translator;

		return $this;
	}

	/**
	 * @return Localization\ITranslator|null
	 */
	public function getTranslator()
	{
		if ($this->translator instanceof Localization\ITranslator) {
			return $this->translator;
		}

		return NULL;
	}
}