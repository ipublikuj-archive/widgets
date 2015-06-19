<?php
/**
 * ITemplateProvider.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Widgets!
 * @subpackage	Templating
 * @since		5.0
 *
 * @date		17.09.14
 */

namespace IPub\Widgets\Templating;

interface ITemplateProvider
{
	/**
	 * Provide widget template path
	 *
	 * @return string
	 */
	public function getWidgetTemplatePath();

	/**
	 * Provide widget decorator template path
	 *
	 * @return string
	 */
	public function getDecoratorTemplatePath();
}