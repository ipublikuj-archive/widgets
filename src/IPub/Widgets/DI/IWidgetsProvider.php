<?php
/**
 * IWidgetsProvider.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Widgets!
 * @subpackage     DI
 * @since          1.0.0
 *
 * @date           22.06.15
 */

namespace IPub\Widgets\DI;

/**
 * Widgets extension interface for providing list of enabled widgets
 *
 * @package        iPublikuj:Widgets!
 * @subpackage     DI
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IWidgetsProvider
{
	const INTERFACENAME = __CLASS__;

	/**
	 * Return array of widgets
	 *
	 * @return array
	 */
	function getWidgets();
}
