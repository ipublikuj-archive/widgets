<?php
/**
 * ITemplateProvider.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:Widgets!
 * @subpackage     Templating
 * @since          1.0.0
 *
 * @date           17.09.14
 */

declare(strict_types = 1);

namespace IPub\Widgets\Templating;

/**
 * Widgets system template provider interface
 *
 * @package        iPublikuj:Widgets!
 * @subpackage     Templating
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface ITemplateProvider
{
	/**
	 * Provide widget template path
	 *
	 * @return string
	 */
	function getWidgetTemplateFile() : string;

	/**
	 * Provide widget decorator template path
	 *
	 * @return string
	 */
	function getDecoratorTemplateFile() : string;

	/**
	 * Provide widget container template path
	 *
	 * @return string
	 */
	function getContainerTemplateFile() : string;
}
