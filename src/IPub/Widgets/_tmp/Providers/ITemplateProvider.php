<?php
/**
 * ITemplateProvider.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Widgets!
 * @subpackage	Providers
 * @since		5.0
 *
 * @date		17.09.14
 */

namespace IPub\Widgets\Providers;

interface ITemplateProvider
{
	/**
	 * Provide widget template path
	 *
	 * @return string
	 */
	public function getTemplatePath();

	/**
	 * Provide widget decorator template path
	 *
	 * @return string
	 */
	public function getDecoratorTemplatePath();
}