<?php
/**
 * IWidgetsProvider.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:Widgets!
 * @subpackage     DI
 * @since          1.0.0
 *
 * @date           22.06.15
 */

declare(strict_types = 1);

namespace IPub\Widgets\DI;

/**
 * Widgets extension interface for providing list of installed widgets
 *
 * @package        iPublikuj:Widgets!
 * @subpackage     DI
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IWidgetsProvider
{
	/**
	 * Return array of widgets
	 *
	 * @return array
	 */
	function getWidgets() : array;
}
