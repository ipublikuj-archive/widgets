<?php
/**
 * IWidgetsProvider.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Widgets!
 * @subpackage	DI
 * @since		5.0
 *
 * @date		22.06.15
 */

namespace IPub\Widgets\DI;

interface IWidgetsProvider
{
	/**
	 * Return array of widgets
	 *
	 * @return array
	 */
	function getWidgets();
}
