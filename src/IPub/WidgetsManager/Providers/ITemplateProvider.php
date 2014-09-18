<?php
/**
 * ITemplateProvider.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:WidgetsManager!
 * @subpackage	Providers
 * @since		5.0
 *
 * @date		17.09.14
 */

namespace IPub\WidgetsManager\Providers;

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
	public function getWidgetDecoratorTemplatePath();
}