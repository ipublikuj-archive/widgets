<?php
/**
 * BaseControl.php
 *
 * @copyright      Vice v copyright.php
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:Widgets!
 * @subpackage     Application
 * @since          1.0.0
 *
 * @date           24.07.13
 */

declare(strict_types = 1);

namespace IPub\Widgets\Application\UI;

use Nette\Application;
use Nette\Localization;

use IPub\Widgets\Exceptions;

/**
 * Extensions base control definition
 *
 * @package        iPublikuj:Widgets!
 * @subpackage     Application
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 *
 * @property Application\UI\ITemplate $template
 */
abstract class BaseControl extends Application\UI\Control
{
	/**
	 * @var string
	 */
	protected $templateFile;

	/**
	 * @var Localization\ITranslator
	 */
	protected $translator;

	/**
	 * @param Localization\ITranslator $translator
	 *
	 * @return void
	 */
	public function injectTranslator(Localization\ITranslator $translator = NULL) : void
	{
		$this->translator = $translator;
	}

	/**
	 * Change default control template path
	 *
	 * @param string $templateFile
	 *
	 * @return void
	 *
	 * @throws Exceptions\FileNotFoundException
	 */
	public function setTemplateFile($templateFile) : void
	{
		// Check if template file exists...
		if (!is_file($templateFile)) {
			// Get component actual dir
			$dir = dirname($this->getReflection()->getFileName());

			// ...check if extension template is used
			if (is_file($dir . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . $templateFile)) {
				$templateFile = $dir . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . $templateFile;

			} else if (is_file($dir . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . $templateFile . '.latte')) {
				$templateFile = $dir . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . $templateFile . '.latte';

			} else {
				// ...if not throw exception
				throw new Exceptions\FileNotFoundException(sprintf('Template file "%s" was not found.', $templateFile));
			}
		}

		$this->templateFile = $templateFile;
	}

	/**
	 * @param Localization\ITranslator $translator
	 */
	public function setTranslator(Localization\ITranslator $translator) : void
	{
		$this->translator = $translator;
	}

	/**
	 * @return Localization\ITranslator|NULL
	 */
	public function getTranslator() : ?Localization\ITranslator
	{
		if ($this->translator instanceof Localization\ITranslator) {
			return $this->translator;
		}

		return NULL;
	}
}
