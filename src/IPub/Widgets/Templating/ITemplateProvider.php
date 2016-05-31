<?php
/**
 * ITemplateProvider.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Widgets!
 * @subpackage     Templating
 * @since          1.0.0
 *
 * @date           17.09.14
 */

namespace IPub\Widgets\Templating;

interface ITemplateProvider
{
	/**
	 * Provide widget template path
	 *
	 * @return string
	 */
	public function getWidgetTemplateFile();

	/**
	 * Provide widget decorator template path
	 *
	 * @return string
	 */
	public function getDecoratorTemplateFile();

	/**
	 * Provide widget container template path
	 *
	 * @return string
	 */
	public function getContainerTemplateFile();
}
